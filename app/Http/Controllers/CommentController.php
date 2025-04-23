<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\CommentNotification;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id ?? null, // Simpan parent_id jika ada
        ]);

        // Kembalikan komentar baru sebagai JSON
        return response()->json([
            'comment' => $comment,
            'user' => $comment->user,
        ]);
    }
}
