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
        $genre = $request->session()->get('preferred_genre', 'Action');

        if (! $request->session()->has('preferred_genre')) {
            $request->session()->put('preferred_genre', $genre);
        }

        $watchlist = $request->user()
            ->listes()
            ->where('type', Liste::TYPE_WATCHLIST)
            ->with([
                'films' => fn ($query) => $query->where('genre', 'like', '%' . $genre . '%'),
            ])
            ->first();

        $watchlistFilms = $watchlist?->films ?? collect();
        $watchlistFilmIds = $watchlist?->films()->pluck('film.id')->all() ?? [];

        $topFive = $request->user()
            ->listes()
            ->where('type', Liste::TYPE_TOP_FIVE)
            ->with('films')
            ->first();

        if (! $topFive) {
            $topFive = $request->user()->listes()->create([
                'titre' => 'Mon top 5',
                'type' => Liste::TYPE_TOP_FIVE,
            ]);
        }

        if ($topFive->films()->count() < 5) {
            $defaultTopFive = Film::query()
                ->where('genre', 'like', '%' . $genre . '%')
                ->whereNotIn('id', $topFive->films()->pluck('film.id'))
                ->orderBy('titre')
                ->limit(5)
                ->get();

            if ($defaultTopFive->count() < 5) {
                $fallbackTopFive = Film::query()
                    ->whereNotIn('id', $topFive->films()->pluck('film.id'))
                    ->orderBy('titre')
                    ->limit(5 - $defaultTopFive->count())
                    ->get();

                $defaultTopFive = $defaultTopFive->merge($fallbackTopFive)->unique('id')->take(5);
            }

            if ($defaultTopFive->isNotEmpty()) {
                $topFive->films()->syncWithoutDetaching(
                    $defaultTopFive->mapWithKeys(fn (Film $film, int $index) => [
                        $film->id => ['position' => $index + 1],
                    ])->all(),
                );
            }
        }

        $topFive->load('films');

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
                'watchlistUrl' => route('dashboard.watchlist.store', ['film' => $film->id]),
                'inWatchlist' => in_array($film->id, $watchlistFilmIds, true),
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

    public function addToWatchlist(Request $request, Film $film): RedirectResponse
    {
        $watchlist = $request->user()->listes()->firstOrCreate(
            ['type' => Liste::TYPE_WATCHLIST],
            ['titre' => 'Watchlist'],
        );

        $watchlist->films()->syncWithoutDetaching([$film->id]);

        return back()->with('status', 'Film ajouté à votre watchlist.');
    }
}
