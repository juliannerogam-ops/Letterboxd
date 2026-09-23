<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Requests\FilmRequest;


Route::prefix('film')->name("film.")->group(function () {
    Route::get('/', [FilmController::class, 'list'])->name("list");
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