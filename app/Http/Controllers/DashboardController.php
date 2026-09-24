<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse; // 1. Ajoutez cet import en haut
use App\Models\Liste;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    // 2. Modifiez le type de retour ici pour autoriser View OU RedirectResponse
    public function index(Request $request): View|RedirectResponse
    {
        $genre = $request->session()->get('preferred_genre');

        if (! $genre) {
            // Cette ligne fonctionne maintenant sans provoquer de TypeError !
            return redirect()->route('recommendations.genre.create');
        }

        $watchlist = $request->user()
            ->listes()
            ->where('type', Liste::TYPE_WATCHLIST)
            ->with([
                'films' => fn ($query) => $query->where('genre', 'like', '%' . $genre . '%'),
            ])
            ->first();

        $watchlistFilms = $watchlist?->films ?? collect();

        $topFive = $request->user()
            ->listes()
            ->where('type', Liste::TYPE_TOP_FIVE)
            ->with('films')
            ->first();

        $recommendations = Film::query()
            ->where('genre', 'like', '%' . $genre . '%')
            ->whereNotIn('id', $watchlistFilms->pluck('id'))
            ->orderBy('titre')
            ->get();

        $releaseFilms = Film::query()
            ->whereNotNull('date_sortie')
            ->orderBy('date_sortie')
            ->orderBy('titre')
            ->get(['id', 'titre', 'date_sortie'])
            ->map(fn (Film $film): array => [
                'date' => Carbon::parse($film->date_sortie)->toDateString(),
                'title' => $film->titre,
                'url' => route('film.show', ['id' => $film->id]),
            ])
            ->values();

        return view('dashboard', compact(
            'genre',
            'watchlistFilms',
            'recommendations',
            'topFive',
            'releaseFilms'
        ));
    }
}
