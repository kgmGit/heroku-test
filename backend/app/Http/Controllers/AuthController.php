<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = Auth::user();
            \Log::debug('***********************');
            \Log::debug(print_r($user, true));
            return new UserResource($user);
        }

        return response()->json(null, 404);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(null, 200);
    }
}
