<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Menangani penyimpanan postingan baru
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000', // Izinkan hingga 10.000 karakter
            'image' => 'nullable|image|max:2048',
        ]);

        // Simpan post
        $post = new Post();
        $post->user_id = auth()->id();
        $post->content = $request->content;

        // Jika ada gambar, simpan
        if ($request->hasFile('image')) {
            $post->image_path = $request->file('image')->store('posts', 'public');
        }

        $post->save();

        // Redirect kembali ke halaman dashboard
        return redirect()->route('dashboard');
    }
}
