<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecommendationController extends Controller
{
    public function index(Request $request): View
    {
        $activeGenre = $request->string('genre')->toString();

        $films = Film::query()
            ->when($activeGenre !== '' && $activeGenre !== 'Tous', function ($query) use ($activeGenre): void {
                $query->where('genre', 'like', '%'.$activeGenre.'%');
            })
            ->orderByDesc('avis_count')
            ->orderBy('titre')
            ->limit(8)
            ->get();

        return view('recommendations.index', [
            'films' => $films,
            'activeGenre' => $activeGenre !== '' ? $activeGenre : 'Tous',
            'genres' => ['Tous', 'Comédie', 'Drame', 'Romance', 'Thriller', 'Fantastique'],
        ]);
    }

    public function create(): View
    {
        $genres = $this->availableGenres();
        $moods = $this->moodGenres();
        $selectedMood = session('preferred_mood');

        return view('recommendations.genre', compact('genres', 'moods', 'selectedMood'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'genre' => ['required', 'string', 'regex:/^[^,]+$/', Rule::in($this->availableGenres())],
            'mood' => ['nullable', 'string', Rule::in(array_keys($this->moodGenres()))],
        ]);

        $request->session()->put('preferred_genre', $validated['genre']);
        if (filled($validated['mood'] ?? null)) {
            $request->session()->put('preferred_mood', $validated['mood']);
        }

        return redirect()->route('dashboard');
    }

    private function availableGenres(): array
    {
        $presetGenres = [
            'Thriller',
            'Fantasy',
            'Comedy',
            'Romance',
            'Horror',
            'Historical',
            'Drama',
            'Family',
            'Crime',
            'Action',
            'Adventure',
            'Animation',
            'Western',
            'Sci-Fi',
            'Documentary',
            'Mystery',
            'Suspense',
            'Romantique',
            'Comédie',
            'Horreur',
            'Fantastique',
            'Drame',
            'Aventure',
            'Émotion',
            'Biopic',
            'Science-fiction',
        ];

        $filmGenres = Film::query()
            ->whereNotNull('genre')
            ->where('genre', '!=', '')
            ->pluck('genre')
            ->flatMap(fn (string $genres): array => array_map('trim', explode(',', $genres)))
            ->filter()
            ->map(fn (string $genre): string => trim($genre))
            ->all();

        return array_values(array_unique([...$presetGenres, ...$filmGenres]));
    }

    private function moodGenres(): array
    {
        return [
            'Heureuse' => ['Romantique', 'Comédie', 'Aventure'],
            'Stressée' => ['Suspense', 'Horreur', 'Drame'],
            'Triste' => ['Drame', 'Documentaire', 'Romantique'],
            'Calme' => ['Documentaire', 'Animation', 'Drame'],
            'Anxieuse' => ['Thriller', 'Science-fiction', 'Horreur'],
            'Énervée' => ['Action', 'Suspense', 'Comédie'],
            'Fatiguée' => ['Animation', 'Comédie', 'Documentaire'],
            'Neutre' => ['Drame', 'Comédie', 'Documentaire'],
            'Ne sais pas trop' => ['Fantastique', 'Aventure', 'Animation'],
        ];
    }
}
