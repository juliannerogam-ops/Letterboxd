<?php

namespace Database\Seeders;

use App\Models\Film;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    /**
     * Seed de films inspiré du calendrier réel de 2026
     * avec des titres repris de la programmation connue et des dates
     * réparties de janvier à septembre pour remplir le calendrier.
     */
    public function run(): void
    {
        Film::query()->where('tmdb_id', '>=', 8000)->delete();

        $monthReleases = [
            '01' => [
                ['date_sortie' => '2026-01-02', 'titre' => 'We Bury the Dead', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-01-09', 'titre' => 'Greenland 2: Migration', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-01-09', 'titre' => 'Dead Man\'s Wire', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-01-16', 'titre' => '28 Years Later: The Bone Temple', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-01-16', 'titre' => 'The Rip', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-01-23', 'titre' => 'Mercy', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-01-28', 'titre' => 'The Wrecking Crew', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-01-30', 'titre' => 'Send Help', 'realisateur' => 'Unknown'],
            ],
            '02' => [
                ['date_sortie' => '2026-02-06', 'titre' => 'The Strangers – Chapter 3', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-02-06', 'titre' => 'Solo Mio', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-02-13', 'titre' => 'Wuthering Heights', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-02-13', 'titre' => 'Goat', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-02-13', 'titre' => 'Crime 101', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-02-20', 'titre' => 'Psycho Killer', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-02-25', 'titre' => 'The Bluff', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-02-27', 'titre' => 'Scream 7', 'realisateur' => 'Unknown'],
            ],
            '03' => [
                ['date_sortie' => '2026-03-06', 'titre' => 'Hoppers', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-03-06', 'titre' => 'The Bride!', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-03-13', 'titre' => 'Reminders of Him', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-03-13', 'titre' => 'The Gates', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-03-20', 'titre' => 'Project Hail Mary', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-03-20', 'titre' => 'Ready or Not 2: Here I Come', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-03-27', 'titre' => 'They Will Kill You', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-03-27', 'titre' => 'Mike & Nick & Nick & Alice', 'realisateur' => 'Unknown'],
            ],
            '04' => [
                ['date_sortie' => '2026-04-01', 'titre' => 'The Super Mario Galaxy Movie', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-04-03', 'titre' => 'The Drama', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-04-10', 'titre' => 'Outcome', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-04-17', 'titre' => 'Lee Cronin\'s The Mummy', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-04-17', 'titre' => 'Mother Mary', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-04-24', 'titre' => 'Michael', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-04-24', 'titre' => 'Apex', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-04-24', 'titre' => 'Desert Warrior', 'realisateur' => 'Unknown'],
            ],
            '05' => [
                ['date_sortie' => '2026-05-01', 'titre' => 'The Devil Wears Prada 2', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-05-08', 'titre' => 'Mortal Kombat II', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-05-15', 'titre' => 'Obsession', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-05-15', 'titre' => 'In the Grey', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-05-20', 'titre' => 'Jack Ryan: Ghost War', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-05-22', 'titre' => 'The Mandalorian and Grogu', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-05-29', 'titre' => 'Backrooms', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-05-29', 'titre' => 'Power Ballad', 'realisateur' => 'Unknown'],
            ],
            '06' => [
                ['date_sortie' => '2026-06-05', 'titre' => 'Scary Movie', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-06-05', 'titre' => 'Masters of the Universe', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-06-12', 'titre' => 'Disclosure Day', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-06-19', 'titre' => 'Toy Story 5', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-06-19', 'titre' => 'The Death of Robin Hood', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-06-26', 'titre' => 'Supergirl', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-06-26', 'titre' => 'Jackass: Best and Last', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-06-26', 'titre' => 'The Invite', 'realisateur' => 'Unknown'],
            ],
            '07' => [
                ['date_sortie' => '2026-07-01', 'titre' => 'Minions & Monsters', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-07-10', 'titre' => 'Moana (remake)', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-07-10', 'titre' => 'Evil Dead Burn', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-07-17', 'titre' => 'The Odyssey', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-07-24', 'titre' => 'Motor City', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-07-25', 'titre' => 'Avatar Aang: The Last Airbender', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-07-31', 'titre' => 'Spider-Man: Brand New Day', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-07-31', 'titre' => 'I Want Your Sex', 'realisateur' => 'Unknown'],
            ],
            '08' => [
                ['date_sortie' => '2026-08-07', 'titre' => 'Super Troopers 3', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-08-14', 'titre' => 'The Rivals of Amziah King', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-08-14', 'titre' => 'The End of Oak Street', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-08-21', 'titre' => 'Insidious: Out of the Further', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-08-21', 'titre' => 'The Magic Faraway Tree', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-08-28', 'titre' => 'The Dog Stars', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-08-28', 'titre' => 'Coyote vs. Acme', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-08-28', 'titre' => 'Idiots', 'realisateur' => 'Unknown'],
            ],
            '09' => [
                ['date_sortie' => '2026-09-04', 'titre' => 'By Any Means', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-09-04', 'titre' => 'Onslaught', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-09-04', 'titre' => 'Mayday', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-09-10', 'titre' => 'Practical Magic 2', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-09-18', 'titre' => 'Resident Evil', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-09-18', 'titre' => 'Rolling Loud: The Movie', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-09-18', 'titre' => 'The Weight', 'realisateur' => 'Unknown'],
                ['date_sortie' => '2026-09-18', 'titre' => 'Best of the Best', 'realisateur' => 'Unknown'],
            ],
        ];

        $tmdbId = 8000;

        foreach ($monthReleases as $releases) {
            foreach ($releases as $filmData) {
                $tmdbId++;

                Film::query()->updateOrCreate(
                    ['tmdb_id' => $tmdbId],
                    [
                        'titre' => $filmData['titre'],
                        'genre' => 'Action, Aventure',
                        'description' => 'Sortie réelle annoncée pour le calendrier 2026.',
                        'annee_sortie' => 2026,
                        'date_sortie' => $filmData['date_sortie'],
                        'duree_minutes' => 120,
                        'affiche_url' => null,
                        'statut_sortie' => 'Film sorti',
                        'realisateur' => $filmData['realisateur'],
                        'est_sorti' => true,
                        'derniere_synchronisation' => now(),
                    ]
                );
            }
        }
    }
}
