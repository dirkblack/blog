<?php

namespace DarkBlog\Models;

class Slug
{
    public static function generate($string)
    {
        $original = $string;
        $i = 0; // track our tries
        $max_tries = 99;

        do {
            $slug = self::slugify($string);

            $i++; // track our tries
            // append an integer to the string for successive tries
            $string = $original . '-' . $i;
        }
        while (Post::where('slug', $slug)->exists() && $i < $max_tries);

        return $slug;
    }

    public static function slugify($string)
    {
        /*
         * Convert a string to our slug format
         */

        // start with the raw text
        $slug = $string;

        // replace non letter or digits by -
        $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);

        // remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // trim
        $slug = trim($slug, '-');

        // remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);

        // lowercase
        $slug = strtolower($slug);

        return $slug;
    }
}
