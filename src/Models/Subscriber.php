<?php

namespace DarkBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email', 'name'];
}
