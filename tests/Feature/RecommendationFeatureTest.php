<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Liste;
use App\Models\User;
use Database\Seeders\FilmSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RecommendationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_clicking_the_logo_resets_the_mood_and_returns_home(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession([
                'preferred_genre' => 'Horreur',
                'preferred_genres' => ['Horreur'],
                'preferred_mood' => 'Stressée',
                'mood_intensity' => 8,
                'preferred_mood_titles' => ['Scream'],
            ])
            ->post(route('recommendations.reset'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionMissing([
                'preferred_genre',
                'preferred_genres',
                'preferred_mood_genres',
                'preferred_mood',
                'mood_intensity',
                'preferred_mood_titles',
                'watchlist_films_by_mood',
            ]);
    }

    public function test_genre_selection_uses_individual_genres_from_film_data(): void
    {
        $user = User::factory()->create();

        Film::create([
            'tmdb_id' => 1,
            'titre' => 'Film aventure romance',
            'genre' => 'Aventure, Romance',
        ]);

        Film::create([
            'tmdb_id' => 2,
            'titre' => 'Film science-fiction',
            'genre' => 'Science-fiction',
        ]);

        $this->actingAs($user)
            ->get(route('recommendations.genre.create'))
            ->assertOk()
            ->assertSee('Aventure')
            ->assertSee('Romantique')
            ->assertSee('Science-fiction')
            ->assertDontSee('Aventure, Romance');
    }

    public function test_mood_test_shows_a_confirmation_button(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('recommendations.genre.create'))
            ->assertOk()
            ->assertSee('Confirmer');
    }

    public function test_recommendation_results_show_filters_and_film_metadata(): void
    {
        $user = User::factory()->create();

        $film = Film::create([
            'tmdb_id' => 7,
            'titre' => 'Little Miss Sunshine',
            'genre' => 'Comédie',
            'affiche_url' => 'https://example.com/little-miss-sunshine.jpg',
            'note' => 3.8,
            'avis_count' => 128,
            'date_sortie' => '2006-08-18',
        ]);

        $this->actingAs($user)
            ->get(route('recommendations.index'))
            ->assertOk()
            ->assertSee('Voici tes recommandations !')
            ->assertSee('Comédie')
            ->assertSee('Little Miss Sunshine')
            ->assertSee('3.8')
            ->assertSee('(128 avis)')
            ->assertSee('18/08/2006')
            ->assertSee(route('film.show', ['id' => $film->id]), false);
    }

    public function test_see_all_keeps_the_selected_mood_and_genre_preferences(): void
    {
        $user = User::factory()->create();

        $moodFilm = Film::create([
            'tmdb_id' => 70,
            'titre' => 'Film du mood',
            'genre' => 'Suspense',
        ]);

        Film::create([
            'tmdb_id' => 71,
            'titre' => 'Film du genre choisi',
            'genre' => 'Horreur',
        ]);

        Film::create([
            'tmdb_id' => 72,
            'titre' => 'Film hors sélection',
            'genre' => 'Comédie',
        ]);

        $this->actingAs($user)
            ->withSession([
                'preferred_genre' => 'Horreur',
                'preferred_genres' => ['Horreur', 'Suspense', 'Drame'],
                'preferred_mood' => 'Stressée',
                'mood_intensity' => 8,
                'preferred_mood_titles' => [$moodFilm->titre],
            ])
            ->get(route('recommendations.index'))
            ->assertOk()
            ->assertViewHas('activeGenre', 'Horreur')
            ->assertViewHas('activeGenres', ['Horreur', 'Suspense', 'Drame'])
            ->assertViewHas('selectedMood', 'Stressée')
            ->assertSee('Film du mood')
            ->assertSee('Film du genre choisi')
            ->assertDontSee('Film hors sélection');
    }

    public function test_recommendations_include_all_films_matching_the_selected_mood_genres(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 14) as $filmNumber) {
            Film::create([
                'tmdb_id' => 200 + $filmNumber,
                'titre' => 'Film suspense '.$filmNumber,
                'genre' => 'Suspense',
                'avis_count' => 14 - $filmNumber,
            ]);
        }

        $this->actingAs($user)
            ->withSession([
                'preferred_genre' => 'Horreur',
                'preferred_genres' => ['Horreur', 'Suspense', 'Drame'],
                'preferred_mood' => 'Stressée',
            ])
            ->get(route('recommendations.index'))
            ->assertOk()
            ->assertViewHas('films', fn ($films): bool => $films->contains('titre', 'Film suspense 14'));
    }

    public function test_recommendation_results_do_not_repeat_titles_with_formatting_variations(): void
    {
        $user = User::factory()->create();

        Film::create([
            'tmdb_id' => 81,
            'titre' => 'Le Roi Lion',
            'genre' => 'Animation',
        ]);

        Film::create([
            'tmdb_id' => 82,
            'titre' => 'Le roi-lion',
            'genre' => 'Animation',
        ]);

        $this->actingAs($user)
            ->get(route('recommendations.index'))
            ->assertOk()
            ->assertViewHas('films', fn ($films): bool => $films->whereIn('titre', [
                'Le Roi Lion',
                'Le roi-lion',
            ])->count() === 1);
    }

    public function test_recommendations_find_a_genre_inside_a_film_genre_list(): void
    {
        $user = User::factory()->create();

        $matchingFilm = Film::create([
            'tmdb_id' => 3,
            'titre' => 'Film aventure',
            'genre' => 'Aventure, Romance',
        ]);

        $otherFilm = Film::create([
            'tmdb_id' => 4,
            'titre' => 'Film science-fiction',
            'genre' => 'Science-fiction',
        ]);

        $this->actingAs($user)
            ->post(route('recommendations.genre.store'), ['genre' => 'Aventure'])
            ->assertRedirect(route('dashboard'));

        $this->actingAs($user)
            ->withSession([
                'preferred_genre' => 'Aventure',
                'preferred_genres' => ['Aventure'],
                'preferred_mood' => 'Ne sais pas trop',
            ])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('recommendations', function ($recommendations) use ($matchingFilm, $otherFilm): bool {
                return $recommendations->contains($matchingFilm)
                    && ! $recommendations->contains($otherFilm);
            });
    }

    public function test_happy_mood_loads_compatible_recommendations_excluding_watchlist_films(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 10) as $filmNumber) {
            Film::create([
                'tmdb_id' => 100 + $filmNumber,
                'titre' => 'Film heureux '.$filmNumber,
                'genre' => 'Aventure',
            ]);
        }

        $this->actingAs($user)
            ->post(route('recommendations.genre.store'), [
                'genre' => 'Aventure',
                'mood' => 'Heureuse',
            ])
            ->assertRedirect(route('dashboard'));

        $watchlistFilmIds = $user->listes()
            ->where('type', Liste::TYPE_WATCHLIST)
            ->first()
            ->films()
            ->pluck('film.id');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertViewHas('recommendations', function ($recommendations) use ($watchlistFilmIds): bool {
                return $recommendations->isNotEmpty()
                    && $recommendations->every(fn (Film $film): bool => str_contains($film->genre, 'Aventure'))
                    && $recommendations->every(fn (Film $film) => ! $watchlistFilmIds->contains($film->id));
            });
    }

    public function test_confirming_a_mood_adds_four_compatible_films_to_the_watchlist(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 6) as $filmNumber) {
            Film::create([
                'tmdb_id' => 500 + $filmNumber,
                'titre' => 'Film watchlist mood '.$filmNumber,
                'genre' => 'Aventure',
                'avis_count' => 100 - $filmNumber,
            ]);
        }

        $this->actingAs($user)
            ->post(route('recommendations.genre.store'), [
                'genre' => 'Aventure',
                'mood' => 'Heureuse',
            ])
            ->assertRedirect(route('dashboard'));

        $watchlist = $user->listes()
            ->where('type', Liste::TYPE_WATCHLIST)
            ->firstOrFail();

        $this->assertSame(4, $watchlist->films()->count());
        $this->assertSame(
            [
                'Film watchlist mood 1',
                'Film watchlist mood 2',
                'Film watchlist mood 3',
                'Film watchlist mood 4',
            ],
            $watchlist->films()->pluck('titre')->all(),
        );
    }

    public function test_dashboard_displays_automatically_added_mood_films_in_the_watchlist(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 600,
            'titre' => 'Film comedie du mood',
            'genre' => 'Comédie',
        ]);

        $this->actingAs($user)
            ->post(route('recommendations.genre.store'), [
                'genre' => 'Aventure',
                'mood' => 'Heureuse',
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertSame(['Romantique', 'Comédie', 'Aventure'], session('preferred_mood_genres'));

        $this->get(route('dashboard'))
            ->assertViewHas('watchlistFilms', fn ($watchlistFilms): bool => $watchlistFilms->contains($film))
            ->assertSee($film->titre);
    }

    public function test_different_moods_add_different_films_to_the_watchlist(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 8) as $filmNumber) {
            Film::create([
                'tmdb_id' => 700 + $filmNumber,
                'titre' => 'Film humeur '.$filmNumber,
                'genre' => $filmNumber <= 4 ? 'Comédie' : 'Suspense',
                'avis_count' => 100 - $filmNumber,
            ]);
        }

        $this->actingAs($user)
            ->post(route('recommendations.genre.store'), [
                'genre' => 'Aventure',
                'mood' => 'Heureuse',
            ])
            ->assertRedirect(route('dashboard'));

        $this->post(route('recommendations.genre.store'), [
            'genre' => 'Aventure',
            'mood' => 'Stressée',
        ])->assertRedirect(route('dashboard'));

        $watchlist = $user->listes()
            ->where('type', Liste::TYPE_WATCHLIST)
            ->firstOrFail();

        $this->assertSame(8, $watchlist->films()->count());
        $this->assertSame(8, $watchlist->films()->pluck('titre')->unique()->count());

        $this->get(route('dashboard'))
            ->assertViewHas('watchlistFilms', function ($watchlistFilms): bool {
                return $watchlistFilms->every(
                    fn (Film $film): bool => str_contains($film->genre, 'Suspense'),
                );
            });
    }

    public function test_dashboard_contains_release_films_for_the_calendar(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 5,
            'titre' => 'Film sorti aujourd’hui',
            'genre' => 'Aventure',
            'date_sortie' => '2026-09-24',
        ]);

        $this->actingAs($user)
            ->withSession(['preferred_genre' => 'Aventure'])
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('release-calendar')
            ->assertViewHas('releaseFilms', function ($releaseFilms) use ($film): bool {
                return $releaseFilms->contains(fn (array $releaseFilm): bool => $releaseFilm['title'] === $film->titre
                    && $releaseFilm['date'] === '2026-09-24');
            });
    }

    public function test_seed_populates_at_least_eight_releases_per_month_from_january_to_september_2026(): void
    {
        Artisan::call('db:seed', ['--class' => FilmSeeder::class]);

        $monthlyCounts = Film::query()
            ->whereYear('date_sortie', 2026)
            ->whereMonth('date_sortie', '>=', 1)
            ->whereMonth('date_sortie', '<=', 9)
            ->selectRaw('CAST(strftime("%m", date_sortie) AS INTEGER) as month')
            ->get()
            ->groupBy('month')
            ->map(fn ($films) => $films->count());

        foreach (range(1, 9) as $month) {
            $this->assertGreaterThanOrEqual(
                8,
                $monthlyCounts->get($month, 0),
                "Le mois {$month} n'a pas assez de sorties réelles enregistrées en 2026."
            );
        }
    }

    public function test_user_can_add_a_calendar_film_to_the_watchlist(): void
    {
        $user = User::factory()->create();
        Liste::createDefaultsFor($user);
        $film = Film::create([
            'tmdb_id' => 6,
            'titre' => 'Film à ajouter',
            'date_sortie' => '2026-09-24',
        ]);

        $this->actingAs($user)
            ->post(route('dashboard.watchlist.store', ['film' => $film]))
            ->assertRedirect();

        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $user->listes()->where('type', Liste::TYPE_WATCHLIST)->value('id'),
            'film_id' => $film->id,
        ]);
    }
}
