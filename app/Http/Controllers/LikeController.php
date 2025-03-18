<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($commentId)
    {
        $user = Auth::user();
        $comment = Comment::findOrFail($commentId);

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            // 既に「イイネ」している場合は削除
            $comment->likes()->detach($user->id);
            return response()->json(['liked' => false, 'like_count' => $comment->likes()->count()]);
        } else {
            // 「イイネ」する
            $comment->likes()->attach($user->id);
            return response()->json(['liked' => true, 'like_count' => $comment->likes()->count()]);
        }
    }
}
