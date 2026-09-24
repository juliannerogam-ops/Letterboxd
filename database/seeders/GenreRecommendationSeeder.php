<?php

namespace Database\Seeders;

use App\Models\Film;
use Illuminate\Database\Seeder;

class GenreRecommendationSeeder extends Seeder
{
    public function run(): void
    {
        $filmsByGenre = [
            'Romantique' => ['La La Land', 'The Notebook', 'Orgueil et Préjugés', 'Before Sunrise', 'About Time', 'Crazy Rich Asians', '10 Things I Hate About You', 'Love, Rosie', 'Call Me by Your Name', 'Eternal Sunshine of the Spotless Mind', 'The Holiday', 'Titanic', "Le Fabuleux Destin d'Amélie Poulain"],
            'Suspense' => ['Shutter Island', 'Gone Girl', 'Prisoners', 'A Quiet Place', 'The Sixth Sense', 'The Prestige', 'Searching', 'Get Out', 'The Game', 'Knives Out', 'Zodiac', 'Memento', 'The Invisible Man'],
            'Drame' => ['Forrest Gump', 'Intouchables', 'Titanic', 'Whiplash', 'Parasite', 'The Shawshank Redemption', 'Manchester by the Sea', 'Little Women', 'The Green Mile', 'La Vie est Belle', 'Wonder', 'À la recherche du bonheur', 'Oppenheimer'],
            'Comédie' => ['Intouchables', 'Mamma Mia!', 'Superbad', 'The Hangover', 'Bridesmaids', 'Le Dîner de Cons', 'OSS 117', 'Mean Girls', 'Crazy Rich Asians', 'The Grand Budapest Hotel', 'Shrek', 'Ratatouille', 'Palm Springs'],
            'Action' => ['John Wick', 'Mad Max: Fury Road', 'Gladiator', 'Kill Bill', 'The Dark Knight', 'Avengers: Endgame', 'Mission: Impossible – Fallout', 'Top Gun: Maverick', 'Baby Driver', 'Django Unchained', 'V pour Vendetta', 'The Hunger Games', 'Die Hard'],
            'Fantastique' => ['Harry Potter à l\'école des sorciers', 'Le Seigneur des Anneaux : La Communauté de l\'Anneau', 'Le Labyrinthe de Pan', 'Edward aux mains d\'argent', 'Stardust', 'Les Chroniques de Narnia', 'Beetlejuice', 'Le Voyage de Chihiro', 'Coraline', 'Pirates des Caraïbes : La Malédiction du Black Pearl', 'Mon voisin Totoro', 'Kiki la petite sorcière', 'About Time'],
            'Aventure' => ["Indiana Jones et les Aventuriers de l'Arche perdue", 'Jurassic Park', 'Le Seigneur des Anneaux : La Communauté de l’Anneau', 'Pirates des Caraïbes : La Malédiction du Black Pearl', 'Jumanji', 'Retour vers le futur', 'Uncharted', 'Le Hobbit : Un voyage inattendu', 'Les Goonies', 'Interstellar', 'Mad Max: Fury Road', 'The Martian', 'Le Voyage de Chihiro'],
            'Émotion' => ['Forrest Gump', 'Intouchables', 'The Green Mile', 'Le Tombeau des Lucioles', 'À la recherche du bonheur', 'La Vie est Belle', 'Coco', 'Wonder', 'Hachi', 'Interstellar', 'Titanic', 'Up', 'The Pursuit of Happyness'],
            'Horreur' => ['The Conjuring', 'Hereditary', 'The Exorcist', 'It', 'Scream', 'The Shining', 'Halloween', 'Insidious', 'The Ring', 'Get Out', 'A Quiet Place', 'Midsommar', 'The Invisible Man'],
            'Thriller' => ['Se7en', 'Gone Girl', 'The Silence of the Lambs', 'Zodiac', 'Nightcrawler', 'Black Swan', 'No Country for Old Men', 'Memento', 'Parasite', 'Joker', 'Shutter Island', 'Prisoners', 'The Dark Knight'],
            'Documentaire' => ['Free Solo', '13th', 'I Am Not Your Negro', "Won't You Be My Neighbor?", 'The Social Dilemma', 'Our Planet', "La Marche de l'empereur", 'Amy', 'Senna', 'Searching for Sugar Man', 'Planet Earth', 'My Octopus Teacher', 'Fire of Love'],
            'Animation' => ['Le Voyage de Chihiro', 'Toy Story', 'Ratatouille', 'Coco', 'Spider-Man: New Generation', 'Le Roi Lion', 'Vice-Versa', 'WALL-E', 'Shrek', 'Mon voisin Totoro', 'Kiki la petite sorcière', 'Là-haut', 'Les Indestructibles'],
            'Biopic' => ['Bohemian Rhapsody', 'Oppenheimer', 'Elvis', 'The Social Network', 'A Beautiful Mind', 'The Imitation Game', 'Walk the Line', 'Rocketman', 'Steve Jobs', 'La Môme', 'The Theory of Everything', 'Lincoln', 'Catch Me If You Can'],
            'Science-fiction' => ['Interstellar', 'Inception', 'The Matrix', 'Blade Runner 2049', 'Dune', 'Arrival', 'Everything Everywhere All at Once', 'Retour vers le futur', 'Avatar', 'Alien', 'The Martian', 'The Truman Show', 'Her'],
        ];

        $tmdbId = (int) Film::query()->max('tmdb_id');

        $fakeMetadata = static function (string $title): array {
            return [
                'note' => round(3.1 + (abs(crc32($title)) % 19) / 10, 1),
                'avis_count' => 20 + abs(crc32('avis-'.$title)) % 480,
            ];
        };

        $filmMetadata = [
            '10 Things I Hate About You' => [
                'annee_sortie' => 1999,
                'date_sortie' => '1999-03-31',
                'note' => 3.7,
            ],
        ];

        $filmPosters = [
            'La La Land' => 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg',
        ];

        foreach ($filmsByGenre as $genre => $titles) {
            foreach ($titles as $title) {
                $film = Film::query()->where('titre', $title)->first();

                if ($film) {
                    $genres = collect(explode(',', (string) $film->genre))
                        ->map(fn (string $existingGenre): string => trim($existingGenre))
                        ->filter()
                        ->push($genre)
                        ->unique()
                        ->values()
                        ->implode(', ');

                    $updates = ['genre' => $genres];

                    if ($film->note === null) {
                        $updates['note'] = $fakeMetadata($title)['note'];
                    }

                    if ($film->avis_count === 0) {
                        $updates['avis_count'] = $fakeMetadata($title)['avis_count'];
                    }

                    if (isset($filmPosters[$title])) {
                        $updates['affiche_url'] = $filmPosters[$title];
                    }

                    $film->update($updates);

                    if (isset($filmMetadata[$title])) {
                        $film->update($filmMetadata[$title]);
                    }

                    continue;
                }

                $tmdbId++;

                Film::query()->updateOrCreate(
                    ['tmdb_id' => $tmdbId],
                    [
                        'titre' => $title,
                        'genre' => $genre,
                        'description' => 'Film recommandé dans la catégorie '.$genre.'.',
                        'affiche_url' => $filmPosters[$title] ?? null,
                        ...$fakeMetadata($title),
                        ...($filmMetadata[$title] ?? []),
                        'statut_sortie' => 'Film sorti',
                        'est_sorti' => true,
                    ],
                );
            }
        }

        $moodFilms = [
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

        $moodGenres = [
            'Heureuse' => 'Romantique, Comédie, Aventure',
            'Stressée' => 'Drame, Thriller, Suspense',
            'Triste' => 'Drame, Émotion',
            'Calme' => 'Drame, Romantique, Animation',
            'Anxieuse' => 'Thriller, Horreur, Suspense',
            'Énervée' => 'Action, Thriller, Drame',
            'Fatiguée' => 'Animation, Comédie, Romantique',
            'Neutre' => 'Comédie, Drame, Thriller',
            'Ne sais pas trop' => 'Science-fiction, Drame, Fantastique',
        ];

        foreach ($moodFilms as $mood => $titles) {
            foreach ($titles as $title) {
                if (Film::query()->where('titre', $title)->exists()) {
                    continue;
                }

                $tmdbId++;

                Film::query()->create([
                    'tmdb_id' => $tmdbId,
                    'titre' => $title,
                    'genre' => $moodGenres[$mood],
                    'description' => 'Film sélectionné pour le mood '.$mood.'.',
                    ...$fakeMetadata($title),
                    'statut_sortie' => 'Film sorti',
                    'est_sorti' => true,
                ]);
            }
        }

        Film::query()
            ->whereNull('date_sortie')
            ->get()
            ->each(function (Film $film): void {
                $seed = abs(crc32($film->titre));
                $year = $film->annee_sortie ?: 1980 + ($seed % 46);
                $month = 1 + ($seed % 12);
                $day = 1 + (($seed >> 4) % 28);

                $film->update([
                    'annee_sortie' => $film->annee_sortie ?: $year,
                    'date_sortie' => sprintf('%04d-%02d-%02d', $year, $month, $day),
                ]);
            });
    }
}
