<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FilmController,
    AuthController,
    KritikController,
};

// Route Halaman Utama & Film
Route::get('/', [FilmController::class, 'movieHome'])->name('home');
Route::get('/movies', [FilmController::class, 'movies'])->name('movies');
Route::get('/movies/{film}', [FilmController::class, 'show'])->name('movies.show');
Route::get('/movies/genre/{genre}', [FilmController::class, 'moviesByGenre'])->name('genre');

// Route Autentikasi (Login & Register)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Kritik / Komentar (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::post('/movies/{film}/kritik', [KritikController::class, 'store'])->name('kritik.store');
});

// Route Dashboard Admin (CRUD Film)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [FilmController::class, 'adminIndex'])->name('dashboard');
    Route::get('/movies', [FilmController::class, 'adminIndex'])->name('movies.index');
    Route::get('/movies/create', [FilmController::class, 'create'])->name('movies.create');
    Route::post('/movies', [FilmController::class, 'store'])->name('movies.store');
    Route::get('/movies/{film}/edit', [FilmController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{film}', [FilmController::class, 'update'])->name('movies.update');
    Route::delete('/movies/{film}', [FilmController::class, 'destroy'])->name('movies.destroy');
});