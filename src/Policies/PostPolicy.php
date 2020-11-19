<?php

namespace DarkBlog\Policies;

use App\Models\User;
use DarkBlog\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Post $post)
    {
        return $user->id == $post->user_id;
    }
}
