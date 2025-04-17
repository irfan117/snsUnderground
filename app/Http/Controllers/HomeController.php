<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua postingan dengan relasi likes dan user
        $posts = Post::with('likes', 'user')->latest()->get();

        // Kirim data ke view dashboard
        return view('dashboard', compact('posts'));
    }
}
