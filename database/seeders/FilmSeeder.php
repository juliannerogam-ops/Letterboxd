<?php

namespace Database\Seeders;

use App\Models\Film;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    /**
     * Seed de films inspiré de la logique de Letterboxd
     * avec des dates de sortie réalistes sur les mois récents.
     */
    public function run(): void
    {
        $films = [
            ['tmdb_id' => 101, 'titre' => 'F1', 'genre' => 'Action, Sport', 'description' => 'Un pilote de Formule 1 cherche à retrouver sa place au sommet.', 'annee_sortie' => 2026, 'date_sortie' => '2026-06-12', 'duree_minutes' => 155, 'affiche_url' => 'https://placehold.co/500x750/111827/ffffff?text=F1', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Joseph Kosinski', 'est_sorti' => true],
            ['tmdb_id' => 102, 'titre' => 'Mission: Impossible – The Final Reckoning', 'genre' => 'Action, Espionnage', 'description' => 'Ethan Hunt doit sauver le monde dans une ultime mission impossible.', 'annee_sortie' => 2026, 'date_sortie' => '2026-07-10', 'duree_minutes' => 164, 'affiche_url' => 'https://placehold.co/500x750/1f2937/ffffff?text=Mission+Impossible', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Christopher McQuarrie', 'est_sorti' => true],
            ['tmdb_id' => 103, 'titre' => 'Superman', 'genre' => 'Action, Super-héros', 'description' => 'Clark Kent est confronté à une menace mondiale et doit révéler sa vraie nature.', 'annee_sortie' => 2026, 'date_sortie' => '2026-07-17', 'duree_minutes' => 152, 'affiche_url' => 'https://placehold.co/500x750/1d4ed8/ffffff?text=Superman', 'statut_sortie' => 'Film sorti', 'realisateur' => 'James Gunn', 'est_sorti' => true],
            ['tmdb_id' => 104, 'titre' => 'The Smurfs Movie', 'genre' => 'Animation, Famille', 'description' => 'Les Schtroumpfs repartent en aventure dans une nouvelle odyssée colorée.', 'annee_sortie' => 2026, 'date_sortie' => '2026-08-07', 'duree_minutes' => 96, 'affiche_url' => 'https://placehold.co/500x750/38bdf8/ffffff?text=Smurfs', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Peyo', 'est_sorti' => true],
            ['tmdb_id' => 105, 'titre' => 'Toy Story 5', 'genre' => 'Animation, Aventure', 'description' => 'Woody, Buzz et la bande retrouvent de nouveaux défis dans un univers encore plus vaste.', 'annee_sortie' => 2026, 'date_sortie' => '2026-08-21', 'duree_minutes' => 110, 'affiche_url' => 'https://placehold.co/500x750/f59e0b/111827?text=Toy+Story+5', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Pixar', 'est_sorti' => true],
            ['tmdb_id' => 106, 'titre' => 'The Conjuring: Last Rites', 'genre' => 'Horreur, Thriller', 'description' => 'Les enquêteurs du paranormal sont confrontés à des événements encore plus dangereux.', 'annee_sortie' => 2026, 'date_sortie' => '2026-09-03', 'duree_minutes' => 123, 'affiche_url' => 'https://placehold.co/500x750/111827/ffffff?text=Conjuring', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Michael Chaves', 'est_sorti' => true],
            ['tmdb_id' => 107, 'titre' => 'M3GAN 2.0', 'genre' => 'Horreur, Science-fiction', 'description' => 'La vie artificielle devient un danger encore plus grand pour l’humanité.', 'annee_sortie' => 2026, 'date_sortie' => '2026-09-11', 'duree_minutes' => 112, 'affiche_url' => 'https://placehold.co/500x750/7c3aed/ffffff?text=M3GAN+2.0', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Gerard Johnstone', 'est_sorti' => true],
            ['tmdb_id' => 108, 'titre' => 'Zootopia 2', 'genre' => 'Animation, Comédie', 'description' => 'Judy Hopps et Nick Wilde s’aventurent dans une nouvelle enquête qui leur fait découvrir la ville sous un autre angle.', 'annee_sortie' => 2026, 'date_sortie' => '2026-09-25', 'duree_minutes' => 104, 'affiche_url' => 'https://placehold.co/500x750/0ea5e9/111827?text=Zootopia+2', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Byron Howard', 'est_sorti' => true],
            ['tmdb_id' => 109, 'titre' => 'A Quiet Place: Day One', 'genre' => 'Horreur, Science-fiction', 'description' => 'Une nuit de chaos devient le point de départ d’une histoire épique de survie.', 'annee_sortie' => 2026, 'date_sortie' => '2026-09-30', 'duree_minutes' => 122, 'affiche_url' => 'https://placehold.co/500x750/0f172a/ffffff?text=A+Quiet+Place', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Michael Sarnoski', 'est_sorti' => true],
            ['tmdb_id' => 110, 'titre' => 'The SpongeBob Movie: Search for SquarePants', 'genre' => 'Animation, Famille', 'description' => 'Bob l’éponge part à la recherche d’un trésor sous-marin aux contours inattendus.', 'annee_sortie' => 2025, 'date_sortie' => '2025-11-13', 'duree_minutes' => 101, 'affiche_url' => 'https://placehold.co/500x750/0ea5e9/111827?text=SpongeBob', 'statut_sortie' => 'Film sorti', 'realisateur' => 'John Kricfalusi', 'est_sorti' => true],
            ['tmdb_id' => 111, 'titre' => 'How to Train Your Dragon', 'genre' => 'Animation, Aventure', 'description' => 'Un jeune Viking apprivoise un dragon et découvre autant de courage que de compassion.', 'annee_sortie' => 2025, 'date_sortie' => '2025-06-13', 'duree_minutes' => 125, 'affiche_url' => 'https://placehold.co/500x750/22c55e/111827?text=How+to+Train+Your+Dragon', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Dean DeBlois', 'est_sorti' => true],
            ['tmdb_id' => 112, 'titre' => 'The Batman Part II', 'genre' => 'Action, Crime', 'description' => 'Batman poursuit son enquête dans Gotham au milieu d’une violence croissante.', 'annee_sortie' => 2026, 'date_sortie' => '2026-10-02', 'duree_minutes' => 176, 'affiche_url' => 'https://placehold.co/500x750/111827/ffffff?text=Batman+2', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Matt Reeves', 'est_sorti' => true],
            ['tmdb_id' => 113, 'titre' => 'Dune: Messiah', 'genre' => 'Science-fiction, Aventure', 'description' => 'La continuation de Paul Atréides plonge l’univers dans une nouvelle bataille pour l’empire.', 'annee_sortie' => 2026, 'date_sortie' => '2026-11-18', 'duree_minutes' => 167, 'affiche_url' => 'https://placehold.co/500x750/7c2d12/ffffff?text=Dune+Messiah', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Denis Villeneuve', 'est_sorti' => true],
            ['tmdb_id' => 114, 'titre' => 'Avatar: Fire and Ash', 'genre' => 'Science-fiction, Aventure', 'description' => 'De nouveaux mondes, nouvelles menaces et un destin plus dangereux pour Pandora.', 'annee_sortie' => 2025, 'date_sortie' => '2025-12-19', 'duree_minutes' => 192, 'affiche_url' => 'https://placehold.co/500x750/0891b2/ffffff?text=Avatar+3', 'statut_sortie' => 'Film sorti', 'realisateur' => 'James Cameron', 'est_sorti' => true],
            ['tmdb_id' => 115, 'titre' => 'The Hunger Games: Sunrise on the Reaping', 'genre' => 'Science-fiction, Action', 'description' => 'Le monde d’une ère nouvelle se prépare à une nouvelle lutte de survie.', 'annee_sortie' => 2026, 'date_sortie' => '2026-11-20', 'duree_minutes' => 147, 'affiche_url' => 'https://placehold.co/500x750/6d28d9/ffffff?text=Hunger+Games', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Francis Lawrence', 'est_sorti' => true],
            ['tmdb_id' => 116, 'titre' => 'Inside Out 3', 'genre' => 'Animation, Drame', 'description' => 'Les émotions de Riley traversent une nouvelle étape de sa vie avec plus de profondeur.', 'annee_sortie' => 2026, 'date_sortie' => '2026-06-25', 'duree_minutes' => 105, 'affiche_url' => 'https://placehold.co/500x750/60a5fa/111827?text=Inside+Out+3', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Pete Docter', 'est_sorti' => true],
            ['tmdb_id' => 117, 'titre' => 'The Bad Guys 2', 'genre' => 'Animation, Comédie', 'description' => 'Le gang de voleurs les plus improbables revient pour une nouvelle aventure.', 'annee_sortie' => 2026, 'date_sortie' => '2026-08-18', 'duree_minutes' => 98, 'affiche_url' => 'https://placehold.co/500x750/eab308/111827?text=Bad+Guys+2', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Pierre Perifel', 'est_sorti' => true],
            ['tmdb_id' => 118, 'titre' => 'Frozen 3', 'genre' => 'Animation, Fantastique', 'description' => 'Elsa et Anna affrontent un nouveau défi dans un royaume toujours plus magique.', 'annee_sortie' => 2026, 'date_sortie' => '2026-11-25', 'duree_minutes' => 109, 'affiche_url' => 'https://placehold.co/500x750/14b8a6/111827?text=Frozen+3', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Jennifer Lee', 'est_sorti' => true],
            ['tmdb_id' => 119, 'titre' => 'The Marvels 2', 'genre' => 'Action, Super-héros', 'description' => 'Les héroïnes du cosmos doivent réécrire leur destin face à une menace galactique.', 'annee_sortie' => 2026, 'date_sortie' => '2026-10-09', 'duree_minutes' => 138, 'affiche_url' => 'https://placehold.co/500x750/7c2d12/ffffff?text=Marvels+2', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Nia DaCosta', 'est_sorti' => true],
            ['tmdb_id' => 120, 'titre' => 'The Last of Us', 'genre' => 'Action, Drame', 'description' => 'Un survivant solitaire trouve un nouvel équilibre dans un monde sans frontières.', 'annee_sortie' => 2026, 'date_sortie' => '2026-09-15', 'duree_minutes' => 136, 'affiche_url' => 'https://placehold.co/500x750/14532d/ffffff?text=Last+of+Us', 'statut_sortie' => 'Film sorti', 'realisateur' => 'Craig Mazin', 'est_sorti' => true],
        ];

        foreach ($films as $film) {
            Film::query()->updateOrCreate(
                ['tmdb_id' => $film['tmdb_id']],
                [
                    'titre' => $film['titre'],
                    'genre' => $film['genre'],
                    'description' => $film['description'],
                    'annee_sortie' => $film['annee_sortie'],
                    'date_sortie' => $film['date_sortie'],
                    'duree_minutes' => $film['duree_minutes'],
                    'affiche_url' => $film['affiche_url'],
                    'statut_sortie' => $film['statut_sortie'],
                    'realisateur' => $film['realisateur'],
                    'est_sorti' => $film['est_sorti'],
                    'derniere_synchronisation' => now(),
                ]
            );
        }
    }
}
