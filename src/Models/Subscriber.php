<?php

namespace DarkBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
     protected $fillable = ['email', 'name'];

    public function verify()
    {
        $this->setAttribute('verified', true);
        $this->save();
    }
}
