<?php

namespace DarkBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $fillable = ['name'];

    public function tagged()
    {
        return $this->morphMany();
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'tagged');
    }

//    public function scopePublishedPosts($query)
//    {
//        return $query->posts()
//            ->where('status', 'published')
//            ->where('published', '<', Carbon::now()->toDateTimeString())
//            ->orderBy('published', 'dsc');
//    }
}
