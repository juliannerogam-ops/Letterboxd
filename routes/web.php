<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return "Bonjour";
});

Route::get('/toto', function () {
    return [
        "Title" => "Dune",
        "Description" => "Un film avec du sable"
    ];
});

Route::get('/mmi', function (Request $request) {
    return $request->ville;
});

Route::get('/blog/{id}', function ($id) {
    return "Voici mon Article " . $id;
})->name("blog.show");

Route::prefix('enseignant')->name("enseignant.")->group(function () {
    Route::get('/liste', function () {
        return "Voici la liste des enseignants";
    })->name("liste");

    Route::get('/{id}', function ($id) {
        return "Voici l'enseignant " . $id;
    })->name("show");
});

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
