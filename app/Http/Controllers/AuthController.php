<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $email = Input::get('email');
        $password = Input::get('password');

        $user = User::where('email', '=', $email)->where('suspended', '=', false)->first();

        if ($user && Hash::check($password, $user->password)) {
            return [
                'token' => $user->password,
                'user' => $user
            ];
        }

        return new Response(['error' => 'Login Failed'], Response::HTTP_UNAUTHORIZED);
    }

}
