<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Models\Film;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use RuntimeException;

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

    public function importView()
    {
        return view('films.import');
    }

    public function importFromUrl(Request $request, TmdbService $tmdbService)
    {
        $validated = $request->validate([
            'url' => ['required', 'url'],
        ]);

        if (! preg_match('~themoviedb\.org/movie/(\d+)~', $validated['url'], $matches)) {
            return back()->withErrors(['url' => "Lien TMDb invalide. Utilise un lien de type https://www.themoviedb.org/movie/550."]);
        }

        $tmdbId = (int) $matches[1];
        $film = Film::where('tmdb_id', $tmdbId)->first();

        if (! $film) {
            try {
                $film = Film::create($tmdbService->getMovieDetails($tmdbId));
            } catch (RequestException) {
                return back()->withErrors(['url' => "Ce film n'a pas pu être récupéré depuis TMDb."]);
            } catch (RuntimeException $exception) {
                return back()->withErrors(['url' => $exception->getMessage()]);
            }
        }

        return redirect()
            ->route('film.show', ['id' => $film->id])
            ->with('success', 'Film importé avec succès.');
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
            'realisateur' => ['nullable', 'string', 'max:255'],
            'est_sorti' => ['nullable', 'boolean'],
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
            'realisateur' => ['nullable', 'string', 'max:255'],
            'est_sorti' => ['nullable', 'boolean'],
        ]));
        return redirect()->route('film.list');
    }
}