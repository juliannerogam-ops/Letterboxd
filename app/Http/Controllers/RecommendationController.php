<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecommendationController extends Controller
{
    public function create(): View
    {
        $genres = $this->availableGenres();
        $moods = $this->moodGenres();

        return view('recommendations.genre', compact('genres', 'moods'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'genre' => ['required', 'string', 'regex:/^[^,]+$/', Rule::in($this->availableGenres())],
        ]);

        $request->session()->put('preferred_genre', $validated['genre']);

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
