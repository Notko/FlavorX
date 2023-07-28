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


    /**
     * Delete comment by comment id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'comment_id' => 'required|numeric'
        ], [
            'comment_id.required' => 'Comment ID is required',
        ]);

        $commentId = $request->comment_id;

        $comment = Comment::where('user_id', $user->id)->where('id', $commentId)->first();
        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found',
            ], 404);
        }

        if ($comment->delete()) {
            return response()->json([], 204);
        } else {
            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Get all comments for a recipe
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllByID(Request $request)
    {
        $comments = Comment::where('recipe_id', $request->route('id'))->get();

        if ($comments->isEmpty()) {
            return response()->json([
                "message" => 'Comments not found'
            ], 404);
        } else {
            return response()->json([
                "data" => $comments
            ]);
        }
    }
}
