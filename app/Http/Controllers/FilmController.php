<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function list()
    {
        $films = Film::all();

        return view('films.all', compact('films'));
    }

    public function show($id)
    {
        $film = Film::findOrFail($id);

        return view('films.show', compact('film'));
    }

    public function delete($id)
    {
        Film::findOrFail($id)->delete();

        return redirect()->route('film.list');
    }

    public function create(Request $request)
    {
        Film::create($request->validate([
            'tmdb_id' => ['required', 'integer', 'unique:film,tmdb_id'],
            'titre' => ['required', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'annee_sortie' => ['nullable', 'integer'],
            'date_sortie' => ['nullable', 'date'],
            'duree_minutes' => ['nullable', 'integer'],
            'affiche_url' => ['nullable', 'string', 'max:255'],
            'statut_sortie' => ['nullable', 'string', 'max:255'],
            'derniere_synchronisation' => ['nullable', 'date'],
            'realisateur_id' => ['nullable', 'uuid'],
        ]));

        return redirect()->route('film.list');
    }

    public function edit_view($id)
    {
        $film = Film::findOrFail($id);

        return view('films.edit', compact('film'));
    }

    public function edit(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        $film->update($request->validate([
            'tmdb_id' => ['required', 'integer', 'unique:film,tmdb_id,' . $film->id],
            'titre' => ['required', 'string', 'max:255'],
            'genre' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'annee_sortie' => ['nullable', 'integer'],
            'date_sortie' => ['nullable', 'date'],
            'duree_minutes' => ['nullable', 'integer'],
            'affiche_url' => ['nullable', 'string', 'max:255'],
            'statut_sortie' => ['nullable', 'string', 'max:255'],
            'derniere_synchronisation' => ['nullable', 'date'],
            'realisateur_id' => ['nullable', 'uuid'],
        ]));

        return redirect()->route('film.list');
    }
}