<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Add new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $request->validate(
            [
                'first_name' => 'required|between:2,32',
                'last_name' => 'required|between:2,32',
                'email' => 'required|unique:users|email',
                'password' => 'required|confirmed|min:8',
            ],
            [
                'first_name.required' => 'First name is required',
                'first_name.between' => 'First name must be between 2-32 characters',
                'last_name.required' => 'Last name is required',
                'last_name.between' => 'Last name must be between 2-32 characters',
                'email.required' => 'E-mail is required',
                'email.unique' => 'User with this e-mail already exists',
                'email.email' => 'E-mail is invalid',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Passwords must match',
                'password.min' => 'Password must be at least 8 characters long'
            ]
        );

        $user = User::create(
            [
                'picture_uri' => url('https://via.placeholder.com/128x128.png/4287f5?text=' . $request->first_name),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

        if (!$user) {
            return response()->json(
                [
                    'message' => 'Server Error'
                ],
                500
            );
        }

        $token = Auth::login($user);

        return response()->json(
            [
                'message' => 'User added successfully!',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'Bearer',
                ]
            ],
            201,
        );
    }

    /**
     * Login user and return JWT
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'E-mail is required',
                'email.email' => 'E-mail is invalid',
                'password.required' => 'Password is required',
            ]
        );

        $token = Auth::attempt($request->only('email', 'password'));

        if (!$token) {
            return response()->json([
                'message' => 'Provided email and password do not match'
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'Bearer',
            ]
        ], 200);
    }
}
