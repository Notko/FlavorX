<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Add comment by recipe id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'comment' => 'required|min:2|max:100000',
            'recipe_id' => 'required|numeric',
        ], [
            'comment.required' => 'Comment is required',
            'comment.min' => 'Comment must be at least 2 characters long',
            'comment.max' => 'Comment must be at maximum 100000 characters long',
            'recipe_id.required' => 'Recipe ID is required',
            'recipe_id.numeric' => 'Recipe ID has to be numeric',
        ]);

        $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => $user->id,
            'recipe_id' => $request->recipe_id,
        ]);

        if (!$comment) {
            return response()->json(
                [
                    'message' => 'Server Error'
                ],
                500
            );
        } else {
            return response()->json(
                [
                    'message' => 'Comment successfully added'
                ],
                201
            );
        }
    }
}
