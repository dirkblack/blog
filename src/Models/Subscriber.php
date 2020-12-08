<?php

namespace DarkBlog\Models;

use DarkBlog\Factories\SubscriberFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'name'];

    protected static function newFactory()
    {
        return new SubscriberFactory();
    }

    public function verify()
    {
        $this->setAttribute('verified', true);
        $this->save();
    }
}
