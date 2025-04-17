<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Halaman utama (welcome page)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Halaman dashboard (feed) dengan middleware auth dan verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Rute untuk membuat postingan
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Rute untuk profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk menyukai postingan
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    // Rute untuk menambahkan komentar pada postingan
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
});

// Rute autentikasi bawaan Laravel
require __DIR__.'/auth.php';
