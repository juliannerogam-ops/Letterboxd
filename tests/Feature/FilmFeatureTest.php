<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilmFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_films_from_the_film_list(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();

        $matchingFilm = Film::create([
            'tmdb_id' => 101,
            'titre' => 'Aventure dans les étoiles',
        ]);

        Film::create([
            'tmdb_id' => 102,
            'titre' => 'Romance au bord de mer',
        ]);

        $this->actingAs($admin)
            ->get(route('film.list', ['q' => 'Aventure']))
            ->assertOk()
            ->assertViewIs('films.all')
            ->assertViewHas('films', function ($films) use ($matchingFilm): bool {
                return $films->contains($matchingFilm)
                    && $films->count() === 1;
            });
    }

    public function test_regular_user_cannot_access_the_film_list(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('film.list'))
            ->assertForbidden();
    }
}