<?php

namespace DarkBlog\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function getPermittedUserOrAbort( User $user, $ability) : User
    {

        if(self::isUserPermitted($user, $ability)) {
            return $user;
        }

        abort(403, "Forbidden");
    }

    public static function isUserPermitted( User $user, $ability) : Bool
    {

        if ($user->can($ability) && $user->isEnabled()) {
            return true;
        }

        return $user->isMaster();
    }
}
