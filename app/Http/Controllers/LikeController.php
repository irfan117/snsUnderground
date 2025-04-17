<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = $request->user();

        // Cek apakah user sudah menyukai postingan
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Jika sudah menyukai, hapus like
            $like->delete();
            $liked = false;
        } else {
            // Jika belum menyukai, tambahkan like
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        // Kembalikan jumlah like dan status
        return response()->json([
            'liked' => $liked,
            'likeCount' => $post->likes()->count(),
        ]);
    }
}
