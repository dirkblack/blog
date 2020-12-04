<?php

namespace DarkBlog\Models;

use DarkBlog\Factories\TagFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;

    public $fillable = ['name'];

    protected static function newFactory()
    {
        return new TagFactory();
    }

    public function tagged()
    {
        return $this->morphMany();
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'tagged');
    }
}
