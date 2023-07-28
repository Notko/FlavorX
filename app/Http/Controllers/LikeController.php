<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Like recipe by given recipe id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|numeric'
        ], [
            'recipe_id.required' => 'Recipe ID is required',
            'recipe_id.numeric' => 'Recipe ID has to be numeric',
        ]);

        $recipeId = $request->recipe_id;
        $userId = Auth::id();

        $existingLike = Like::where('user_id', $userId)->where('recipe_id', $recipeId)->first();
        if ($existingLike) {
            return response()->json([
                'message' => 'Recipe already liked',
            ], 409);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $recipe = Recipe::find($recipeId);
        if (!$recipe) {
            return response()->json([
                'message' => 'Recipe not found'
            ], 404);
        }

        $like = Like::create([
            'user_id' => $userId,
            'recipe_id' => $recipeId
        ]);

        if (!$like) {
            return response()->json(
                [
                    'message' => 'Server Error'
                ],
                500
            );
        } else {
            return response()->json(
                [
                    'message' => 'Liked successfully'
                ],
                201
            );
        }
    }


    /**
     * Remove like by given recipe id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|numeric'
        ], [
            'recipe_id.required' => 'Recipe ID is required',
            'recipe_id.numeric' => 'Recipe ID has to be numeric',
        ]);

        $recipeId = $request->recipe_id;
        $userId = Auth::id();

        $like = Like::where('user_id', $userId)->where('recipe_id', $recipeId)->first();
        if (!$like) {
            return response()->json([
                'message' => 'Recipe like not found',
            ], 404);
        }

        $like->delete();

        return response()->json([], 204);
    }
}
