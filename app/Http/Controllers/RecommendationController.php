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
        $genreAliases = $this->genreAliases();
        $activeAliases = $genreAliases[$activeGenre] ?? [];
        $showAllFilms = $activeGenre === '' || $activeGenre === 'Tous';
        $selectedMood = $showAllFilms ? null : $request->session()->get('preferred_mood');
        $moodIntensity = $request->session()->get('mood_intensity');
        $moodTitles = $request->session()->get('preferred_mood_titles', []);

        $films = Film::query()
            ->when(! $showAllFilms, function ($query) use ($activeAliases): void {
                $query->where(function ($genreQuery) use ($activeAliases): void {
                    foreach ($activeAliases as $alias) {
                        $genreQuery->orWhere('genre', 'like', '%'.$alias.'%');
                    }
                });
            })
            ->when(! $showAllFilms && $moodTitles !== [], function ($query) use ($moodTitles): void {
                $query->orWhereIn('titre', $moodTitles);
            })
            ->orderBy($showAllFilms ? 'titre' : 'avis_count', $showAllFilms ? 'asc' : 'desc')
            ->orderBy('titre')
            ->when(! $showAllFilms, fn ($query) => $query->limit(13))
            ->get();

        return view('recommendations.index', [
            'films' => $films,
            'activeGenre' => $activeGenre !== '' ? $activeGenre : 'Tous',
            'selectedMood' => $selectedMood,
            'moodIntensity' => $moodIntensity,
            'genres' => array_keys($genreAliases),
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
            'intensity' => ['nullable', 'integer', 'between:1,10'],
        ]);

        $request->session()->put('preferred_genre', $validated['genre']);
        if (filled($validated['mood'] ?? null)) {
            $request->session()->put('preferred_mood', $validated['mood']);
            $request->session()->put('mood_intensity', $validated['intensity'] ?? 5);
            $request->session()->put('preferred_genres', array_values(array_unique([
                $validated['genre'],
                ...$this->moodGenres()[$validated['mood']],
            ])));
            $request->session()->put('preferred_mood_titles', $this->moodFilms()[$validated['mood']]);
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

    private function genreAliases(): array
    {
        return [
            'Tous' => [],
            'Romantique' => ['Romantique', 'Romance', 'Romantic'],
            'Suspense' => ['Suspense'],
            'Drame' => ['Drame', 'Drama'],
            'Comédie' => ['Comédie', 'Comedy'],
            'Action' => ['Action'],
            'Fantastique' => ['Fantastique', 'Fantasy'],
            'Aventure' => ['Aventure', 'Adventure'],
            'Émotion' => ['Émotion', 'Emotion'],
            'Horreur' => ['Horreur', 'Horror'],
            'Thriller' => ['Thriller'],
            'Documentaire' => ['Documentaire', 'Documentary'],
            'Animation' => ['Animation'],
            'Biopic' => ['Biopic'],
            'Science-fiction' => ['Science-fiction', 'Science Fiction', 'Sci-Fi'],
        ];
    }

    private function moodFilms(): array
    {
        return [
            'Heureuse' => ['Mamma Mia!', 'La La Land', 'Intouchables', 'Paddington 2', 'Ratatouille', 'The Greatest Showman', 'Crazy Rich Asians', 'Shrek', 'Les Gardiens de la Galaxie', 'Retour vers le futur'],
            'Stressée' => ['Whiplash', 'Uncut Gems', 'A Quiet Place', '1917', 'The Social Network', 'Black Swan', 'Nightcrawler', 'Prisoners', 'Sicario', 'Good Time'],
            'Triste' => ['Titanic', 'The Green Mile', 'Forrest Gump', 'Manchester by the Sea', 'Le Tombeau des Lucioles', 'La Vie est Belle', 'Coco', 'Wonder', 'À la recherche du bonheur', 'Hachi'],
            'Calme' => ['Perfect Days', 'Paterson', 'Lost in Translation', 'Before Sunrise', "Le Fabuleux Destin d'Amélie Poulain", 'My Neighbor Totoro', 'Call Me by Your Name', 'Pride & Prejudice', 'The Secret Life of Walter Mitty', 'Little Women'],
            'Anxieuse' => ['Get Out', 'Shutter Island', 'Gone Girl', 'The Machinist', 'Midsommar', 'Hereditary', 'The Truman Show', 'Donnie Darko', 'The Lighthouse', 'Parasite'],
            'Énervée' => ['Kill Bill', 'John Wick', 'Mad Max: Fury Road', 'Fight Club', 'The Dark Knight', 'Gladiator', 'V pour Vendetta', 'Baby Driver', 'Django Unchained', 'The Hunger Games'],
            'Fatiguée' => ['Kiki la petite sorcière', 'Ratatouille', 'Paddington', 'Toy Story', 'My Neighbor Totoro', 'The Holiday', 'Chef', 'About Time', 'Fantastic Mr. Fox', 'Amélie'],
            'Neutre' => ['The Grand Budapest Hotel', "Ocean's Eleven", 'The Martian', 'Catch Me If You Can', 'The Truman Show', 'Knives Out', 'Moneyball', 'The Secret Life of Walter Mitty', 'Now You See Me', 'The Intern'],
            'Ne sais pas trop' => ['Everything Everywhere All at Once', 'Eternal Sunshine of the Spotless Mind', 'Inception', 'The Truman Show', 'Spider-Man: Into the Spider-Verse', 'Her', 'The Grand Budapest Hotel', 'The Secret Life of Walter Mitty', 'Arrival', 'Lost in Translation'],
        ];
    }
}
