<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SingupRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * This function is used for singup the user
     * @param SingupRequest $request
     */
    public function singup(SingupRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * This function is used for login the user
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * This function is used for login the user
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    /**
     * This function is used for get the information of current user
     * @param Request $request
     */
    public function me(Request $request)
    {
        return $request->user();
    }
}
