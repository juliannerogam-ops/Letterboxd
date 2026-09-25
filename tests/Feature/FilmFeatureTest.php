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

    public function test_authenticated_user_can_search_films_from_the_dashboard(): void
    {
        $user = User::factory()->create();
        $matchingFilm = Film::create([
            'tmdb_id' => 104,
            'titre' => 'Aventure dans les étoiles',
        ]);

        Film::create([
            'tmdb_id' => 105,
            'titre' => 'Romance au bord de mer',
        ]);

        $this->actingAs($user)
            ->get(route('films.search', ['q' => 'Aventure']))
            ->assertOk()
            ->assertViewIs('films.search')
            ->assertViewHas('films', function ($films) use ($matchingFilm): bool {
                return $films->contains($matchingFilm)
                    && $films->count() === 1;
            });
    }

    public function test_film_page_displays_the_imported_poster(): void
    {
        $film = Film::create([
            'tmdb_id' => 103,
            'titre' => 'Film avec affiche',
            'affiche_url' => 'https://image.tmdb.org/t/p/w500/poster.jpg',
        ]);

        $this->get(route('film.show', ['id' => $film->id]))
            ->assertOk()
            ->assertSee('https://image.tmdb.org/t/p/w500/poster.jpg')
            ->assertSee('Affiche du film Film avec affiche');
    }
}
