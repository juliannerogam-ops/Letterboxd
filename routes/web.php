<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilmRequest;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListeController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('film')->name("film.")->group(function () {
    Route::get('/', [FilmController::class, 'list'])
        ->middleware(['auth', 'admin'])
        ->name("list");
    Route::get('/import', [FilmController::class, 'importView'])->name("import_view");
    Route::post('/import', [FilmController::class, 'importFromUrl'])->name("import");
    Route::get("/{id}", [FilmController::class, 'show'])->whereUuid('id')->name("show");

    Route::get('/create', function () {
        return view('films.create');
    })->name("view_create");
    Route::post('/create', [FilmController::class, 'create'])->name("create");

    Route::get("/{id}/edit", [FilmController::class, 'edit_view'])->name("edit_view");
    Route::post("/{id}/edit", [FilmController::class, 'edit'])->name("edit");

    Route::get("/{id}/delete", [FilmController::class, 'delete'])->name("delete");
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::patch('/users/{user}/promote', [AdminController::class, 'promote'])->name('users.promote');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::post('/dashboard/watchlist/{film}', [DashboardController::class, 'addToWatchlist'])
    ->middleware('auth')
    ->name('dashboard.watchlist.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/listes', [ListeController::class, 'index'])->name('listes');
    Route::get('/listes/create', [ListeController::class, 'create'])->name('listes.create');
    Route::post('/listes', [ListeController::class, 'store'])->name('listes.store');
    Route::get('/listes/{liste}', [ListeController::class, 'show'])->name('listes.show');
    Route::post('/listes/{liste}/films', [ListeController::class, 'addFilm'])->name('listes.films.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/preferences/genre', [RecommendationController::class, 'create'])
        ->name('recommendations.genre.create');

    Route::post('/preferences/genre', [RecommendationController::class, 'store'])
        ->name('recommendations.genre.store');
});