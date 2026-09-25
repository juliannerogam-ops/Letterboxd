<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Liste; // 1. Ajoutez cet import en haut
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    // 2. Modifiez le type de retour ici pour autoriser View OU RedirectResponse
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $genre = $request->session()->get('preferred_genre', 'Action');
        $preferredGenres = $request->session()->get('preferred_genres', [$genre]);
        $preferredMoodGenres = $request->session()->get('preferred_mood_genres', $preferredGenres);
        $preferredMoodTitles = $request->session()->get('preferred_mood_titles', []);
        $mood = $request->session()->get('preferred_mood');
        $watchlistFilmsByMood = $request->session()->get('watchlist_films_by_mood', []);

        if (! $request->session()->has('preferred_genre')) {
            $request->session()->put('preferred_genre', $genre);
        }

        $watchlist = $user
            ->listes()
            ->where('type', Liste::TYPE_WATCHLIST)
            ->first();

        $watchlistFilmIds = $watchlist?->films()->pluck('film.id')->all() ?? [];
        $watchlistFilms = $mood && $watchlist
            ? $watchlist->films()
                ->when(isset($watchlistFilmsByMood[$mood]), function ($query) use ($watchlistFilmsByMood, $mood): void {
                    $query->whereIn('film.id', $watchlistFilmsByMood[$mood]);
                })
                ->when(! isset($watchlistFilmsByMood[$mood]), function ($query) use ($preferredMoodGenres): void {
                    $query->where(function ($genreQuery) use ($preferredMoodGenres): void {
                        foreach ($preferredMoodGenres as $preferredMoodGenre) {
                            $genreQuery->orWhere('genre', 'like', '%'.$preferredMoodGenre.'%');
                        }
                    });
                })
                ->limit(4)
                ->get()
            : collect();

        $topFive = $user
            ->listes()
            ->where('type', Liste::TYPE_TOP_FIVE)
            ->with('films')
            ->first();

        if (! $topFive) {
            $topFive = $user->listes()->create([
                'titre' => 'Mon top 5',
                'type' => Liste::TYPE_TOP_FIVE,
            ]);
        }

        if ($topFive->films()->count() < 5) {
            $defaultTopFive = Film::query()
                ->where('genre', 'like', '%'.$genre.'%')
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

        $recommendations = collect();

        if ($mood) {
            $recommendations = Film::query()
                ->where(function ($query) use ($preferredMoodGenres, $preferredMoodTitles): void {
                    foreach ($preferredMoodGenres as $preferredMoodGenre) {
                        $query->orWhere('genre', 'like', '%'.$preferredMoodGenre.'%');
                    }

                    if ($preferredMoodTitles !== []) {
                        $query->orWhereIn('titre', $preferredMoodTitles);
                    }
                })
                ->whereNotIn('id', $watchlistFilmIds)
                ->orderByRaw('CASE WHEN genre LIKE ? THEN 0 ELSE 1 END', ['%'.$genre.'%'])
                ->orderByRaw("CASE WHEN affiche_url IS NULL OR affiche_url = '' THEN 1 ELSE 0 END")
                ->orderBy('titre')
                ->get()
                ->sortBy(function (Film $film) use ($preferredMoodTitles): int {
                    $position = array_search($film->titre, $preferredMoodTitles, true);

                    return $position === false ? PHP_INT_MAX : $position;
                })
                ->values();
        }

        $summerBlockbusters = Film::query()
            ->whereBetween('date_sortie', ['2026-07-01', '2026-08-31'])
            ->orderByDesc('avis_count')
            ->orderByDesc('date_sortie')
            ->orderBy('titre')
            ->get()
            ->unique(fn (Film $film): string => $this->normalizeTitle($film->titre))
            ->take(4)
            ->values();

        $releaseFilms = Film::query()
            ->whereNotNull('date_sortie')
            ->whereYear('date_sortie', 2026)
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
            'preferredGenres',
            'mood',
            'watchlistFilms',
            'recommendations',
            'summerBlockbusters',
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

    private function normalizeTitle(string $title): string
    {
        return (string) Str::of($title)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish();
    }
}
