<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{

    /**
     * Get the requesting users details
     *
     * @param Request $request
     * @return User
     */
    public function me(Request $request)
    {
        $token = $request->header('x-api-token');
        $user = User::where('password', '=', $token)->first();

        return $user;
    }


}
