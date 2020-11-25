<?php

namespace DarkBlog\Models;

class Slug
{
    public static function generate($title)
    {
        // replace non letter or digits by -
        $title = preg_replace('~[^\pL\d]+~u', '-', $title);

        // remove unwanted characters
        $title = preg_replace('~[^-\w]+~', '', $title);

        // trim
        $title = trim($title, '-');

        // remove duplicate -
        $title = preg_replace('~-+~', '-', $title);

        // lowercase
        $title = strtolower($title);

        return $title;
    }
}
