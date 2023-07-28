<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Add recipe
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'title' => 'required|between:2,128',
            'description' => 'required|min:2|max:100000',
            'ingredients' => 'required|min:2|max:100000',
            'instructions' => 'required|min:2|max:100000',
        ], [
            'title.required' => 'Title is required',
            'title.between' => 'Title must be between 2-128 characters',
            'description.required' => 'Description is required',
            'description.min' => 'Description must be at least 2 characters long',
            'description.max' => 'Description must be at maximum 100000 characters long',
            'ingredients.required' => 'Ingredients are required',
            'ingredients.min' => 'Ingredients must be at least 2 characters long',
            'ingredients.max' => 'Ingredients must be at maximum 100000 characters long',
            'instructions.required' => 'Instructions are required',
            'instructions.min' => 'Instructions must be at least 2 characters long',
            'instructions.max' => 'Instructions must be at maximum 100000 characters long',
        ]);

        $recipe = Recipe::create(
            [
                'title' => $request->title,
                'description' => $request->description,
                'ingredients' => $request->ingredients,
                'instructions' => $request->instructions,
                'user_id' => $userId,
            ]
        );

        if (!$recipe) {
            return response()->json(
                [
                    'message' => 'Server Error'
                ],
                500
            );
        } else {
            return response()->json(
                [
                    'message' => 'Recipe successfully added'
                ],
                201
            );
        }
    }

    /**
     * Get recipe by id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request)
    {
        $recipeId = $request->route('id');
        $recipe = Recipe::find($recipeId);

        if ($recipe) {
            return response()->json([
                'data' => $recipe,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Recipe not found',
            ], 404);
        }
    }

    /**
     * Get all recipes
     * Available queries: limit, offset, desc
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request)
    {
        $limit = $request->input('limit', 20);
        $offset = $request->input('offset', 0);
        $desc = filter_var($request->input('desc', false), FILTER_VALIDATE_BOOLEAN);

        $recipes = Recipe::all()->sortBy('created_at', SORT_REGULAR, $desc)->skip($offset)->take($limit)->all();

        if ($recipes) {
            return response()->json(
                [
                    'data' => $recipes
                ], 200
            );
        } else {
            return response()->json(
                [
                    'message' => 'Recipes not found'
                ], 404
            );
        }
    }
}
