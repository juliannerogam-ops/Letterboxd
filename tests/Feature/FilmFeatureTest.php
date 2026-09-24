<?php

namespace Tests\Feature;

use App\Models\Film;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilmFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_search_films_from_the_film_list(): void
    {
        $matchingFilm = Film::create([
            'tmdb_id' => 101,
            'titre' => 'Aventure dans les étoiles',
        ]);

        Film::create([
            'tmdb_id' => 102,
            'titre' => 'Romance au bord de mer',
        ]);

        $this->get(route('film.list', ['q' => 'Aventure']))
            ->assertOk()
            ->assertViewIs('films.all')
            ->assertViewHas('films', function ($films) use ($matchingFilm): bool {
                return $films->contains($matchingFilm)
                    && $films->count() === 1;
            });
    }
}