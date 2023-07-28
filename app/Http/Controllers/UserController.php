<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
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
     * @return JsonResponse
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

    /**
     * Get user by id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        $userId = $request->route('id');
        $user = User::find($userId);

        if ($user) {
            return response()->json([
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Update user by id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $userId = $request->id;
        if (Auth::id() !== $userId) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $request->validate([
            'first_name' => 'nullable|between:2,32',
            'last_name' => 'nullable|between:2,32',
            'email' => 'nullable|unique:users|email',
            'password' => 'nullable|confirmed|min:8',
        ], [
            'first_name.between' => 'First name must be between 2-32 characters',
            'last_name.between' => 'Last name must be between 2-32 characters',
            'email.unique' => 'User with this e-mail already exists',
            'email.email' => 'E-mail is invalid',
            'password.confirmed' => 'Passwords must match',
            'password.min' => 'Password must be at least 8 characters long',
        ]);

        $user->fill($request->only(['first_name', 'last_name', 'email']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            return response()->json([
                'message' => 'User updated successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Delete user by id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $userId = $request->id;
        if (Auth::id() !== $userId) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if ($user->delete()) {
            return response()->json([], 204);
        } else {
            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
