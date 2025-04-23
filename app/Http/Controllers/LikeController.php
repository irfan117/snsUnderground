<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\LikeNotification;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likeCount' => $post->likes()->count(),
        ]);
    }
}
