<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Pokemon;
use App\Http\Controllers\PokemonController;

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

Route::prefix('pokemon')->name("pokemon.")->group(function () {
    // Afficher pokemons
    Route::get('/', [PokemonController::class, 'list'])->name("list");
    Route::get("/{id}", [PokemonController::class, 'show'])->where('id', '[0-9]+')->name("show");
    Route::get("/only_bizzares", [PokemonController::class, 'onlyBizzares'])->name("only_bizzares");

    // Créer un pokemon
    Route::get('/create', function () {
        return view('pokemons.create');
    })->name("view_create");
    Route::post('/create', [PokemonController::class, 'create'])->name("create");

    // Modifier un pokemon
    Route::get("/{id}/edit", [PokemonController::class, 'edit_view'])->name("edit_view");
    Route::post("/{id}/edit", [PokemonController::class, 'edit'])->name("edit");

    // Supprimer un pokemon
    Route::get("/{id}/delete", [PokemonController::class, 'delete'])->name("delete");
});
