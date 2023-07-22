<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Add new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $data = $request->validate(
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

        if ($errors = $data->errors()) {
            return response()->json($errors, 422);
        }

        $data['picture_uri'] = url('https://via.placeholder.com/128x128.png/4287f5?text=' . $data['first_name']);

        $data['password'] = Hash::make($data['password']);

        $users = User::create($data);

        if ($users) {
            return response()->json(
                [
                    'message' => 'User added successfully!'
                ],
                201,
            );
        } else {
            return response()->json(
                [
                    'message' => 'Server Error'
                ],
                500
            );
        }
    }
}
