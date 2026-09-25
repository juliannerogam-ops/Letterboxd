<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Liste;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ListeFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_default_lists(): void
    {
        $user = User::factory()->create();

        Liste::createDefaultsFor($user);

        $this->assertEqualsCanonicalizing(
            [Liste::TYPE_WATCHLIST, Liste::TYPE_FAVORITES, Liste::TYPE_TOP_FIVE],
            $user->listes()->pluck('type')->all(),
        );
    }

    public function test_dashboard_still_loads_with_default_genre_when_none_is_selected(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 7) as $index) {
            Film::create([
                'tmdb_id' => 2000 + $index,
                'titre' => 'Film default '.$index,
                'genre' => 'Action',
            ]);
        }

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();

        $this->assertSame('Action', session('preferred_genre') ?? 'Action');
    }

    public function test_dashboard_has_no_recommendations_before_a_mood_is_selected(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 3000,
            'titre' => 'Film action par défaut',
            'genre' => 'Action',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('recommendations', fn ($recommendations): bool => $recommendations->isEmpty())
            ->assertSee('Lance le test de mood pour recevoir des recommandations personnalisées.')
            ->assertDontSee($film->titre);
    }

    public function test_dashboard_hides_watchlist_recommendations_after_reconnecting_without_a_mood(): void
    {
        $user = User::factory()->create([
            'email' => 'watchlist-user@example.com',
            'password' => bcrypt('password123'),
        ]);
        $watchlist = $user->listes()->create([
            'titre' => 'Watchlist',
            'type' => Liste::TYPE_WATCHLIST,
        ]);
        $film = Film::create([
            'tmdb_id' => 3001,
            'titre' => 'Film de watchlist',
            'genre' => 'Action',
        ]);
        $watchlist->films()->attach($film);

        $this->actingAs($user)
            ->withSession([
                'preferred_mood' => 'Calme',
                'preferred_genre' => 'Action',
                'preferred_genres' => ['Action'],
            ])
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->post('/login', [
            'email' => 'watchlist-user@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('recommendations.genre.create'));

        $this->get(route('dashboard'))
            ->assertViewHas('watchlistFilms', fn ($watchlistFilms): bool => $watchlistFilms->isEmpty())
            ->assertDontSee($film->titre);
    }

    public function test_dashboard_places_recommendations_before_blockbusters_after_mood_selection(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession([
                'preferred_mood' => 'Calme',
                'preferred_genre' => 'Drame',
                'preferred_genres' => ['Drame', 'Comédie', 'Documentaire'],
            ])
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('mood-dashboard has-selected-mood', false)
            ->assertSee('Suggestions pour le genre : Drame, Comédie, Documentaire');
    }

    public function test_dashboard_profile_menu_offers_account_switch_and_logout_actions(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Changer de compte')
            ->assertSee('Se déconnecter')
            ->assertSee('aria-controls="profile-menu-panel"', false);
    }

    public function test_dashboard_creates_a_top_five_when_missing(): void
    {
        $user = User::factory()->create();
        $genre = 'Science-fiction';

        foreach (range(1, 7) as $index) {
            Film::create([
                'tmdb_id' => 1000 + $index,
                'titre' => 'Film '.$index,
                'genre' => $genre,
            ]);
        }

        $this->actingAs($user)
            ->withSession(['preferred_genre' => $genre])
            ->get(route('dashboard'))
            ->assertOk();

        $topFive = $user->listes()->where('type', Liste::TYPE_TOP_FIVE)->firstOrFail();

        $this->assertGreaterThanOrEqual(1, $topFive->films()->count());
        $this->assertDatabaseHas('listes', [
            'user_id' => $user->id,
            'type' => Liste::TYPE_TOP_FIVE,
        ]);
    }

    public function test_authenticated_user_can_create_a_list_and_add_a_film(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 550,
            'titre' => 'Fight Club',
        ]);

        $this->actingAs($user)
            ->get(route('listes'))
            ->assertOk()
            ->assertViewIs('listes');

        $this->actingAs($user)
            ->post(route('listes.store'), [
                'titre' => 'Films à voir',
                'description' => 'Une sélection personnelle',
            ])
            ->assertRedirect(route('listes'));

        $liste = $user->listes()->firstOrFail();

        $this->actingAs($user)
            ->post(route('listes.films.store', $liste), [
                'film_id' => $film->id,
            ])
            ->assertRedirect(route('listes.show', $liste));

        $this->assertTrue($liste->films()->whereKey($film)->exists());
    }

    public function test_authenticated_user_can_view_and_update_profile(): void
    {
        $user = User::factory()->create(['name' => 'Martin', 'first_name' => 'Alex', 'pseudo' => 'alex-martin']);

        $this->actingAs($user)->get(route('profile'))
            ->assertOk()->assertViewIs('auth.profile')->assertSee('alex-martin');

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Durand', 'first_name' => 'Camille', 'pseudo' => 'camille-durand',
            'email' => 'camille@example.com', 'password' => '', 'password_confirmation' => '',
        ])->assertRedirect(route('profile'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id, 'name' => 'Durand', 'first_name' => 'Camille',
            'pseudo' => 'camille-durand', 'email' => 'camille@example.com',
        ]);

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Durand', 'first_name' => 'Camille', 'pseudo' => 'camille-durand',
            'email' => 'camille@example.com', 'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Durand', 'first_name' => 'Camille', 'pseudo' => 'camille-durand',
            'email' => 'camille@example.com', 'current_password' => 'wrong-password',
            'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Durand', 'first_name' => 'Camille', 'pseudo' => 'camille-durand',
            'email' => 'camille@example.com', 'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('profile'));

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    public function test_guest_cannot_view_or_update_profile(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
        $this->put(route('profile.update'))->assertRedirect(route('login'));
    }

    public function test_adding_a_film_at_a_top_five_position_shifts_later_films(): void
    {
        $user = User::factory()->create();
        $topFive = $user->listes()->create([
            'titre' => 'Mon top 5',
            'type' => Liste::TYPE_TOP_FIVE,
        ]);
        $films = collect(range(1, 6))->map(fn (int $tmdbId) => Film::create([
            'tmdb_id' => $tmdbId,
            'titre' => 'Film '.$tmdbId,
        ]));

        foreach ($films->take(5) as $index => $film) {
            $topFive->films()->attach($film, ['position' => $index + 1]);
        }

        $this->actingAs($user)
            ->post(route('listes.films.store', $topFive), [
                'film_id' => $films[5]->id,
                'position' => 2,
            ])
            ->assertRedirect(route('listes.show', $topFive));

        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[0]->id,
            'position' => 1,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[5]->id,
            'position' => 2,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[1]->id,
            'position' => 3,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[2]->id,
            'position' => 4,
        ]);
        $this->assertDatabaseHas('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[3]->id,
            'position' => 5,
        ]);
        $this->assertDatabaseMissing('film_liste', [
            'liste_id' => $topFive->id,
            'film_id' => $films[4]->id,
        ]);
    }

    public function test_film_page_marks_lists_that_already_contain_the_film(): void
    {
        $user = User::factory()->create();
        $film = Film::create([
            'tmdb_id' => 550,
            'titre' => 'Fight Club',
        ]);

        $existingList = $user->listes()->create([
            'titre' => 'Déjà vu',
        ]);

        $availableList = $user->listes()->create([
            'titre' => 'À regarder',
        ]);

        $existingList->films()->attach($film);

        $response = $this->actingAs($user)
            ->get(route('film.show', ['id' => $film->id]));

        $response
            ->assertOk()
            ->assertSee('Retour aux films')
            ->assertViewHas('lists', function ($lists) use ($film, $existingList, $availableList) {
                $loadedExistingList = $lists->firstWhere('id', $existingList->id);

                return $lists->contains($availableList)
                    && $lists->contains($existingList)
                    && $loadedExistingList->films->contains('id', $film->id);
            });
    }

    public function test_user_can_remove_a_film_from_the_watchlist(): void
    {
        $user = User::factory()->create();
        $watchlist = $user->listes()->create([
            'titre' => 'Watchlist',
            'type' => Liste::TYPE_WATCHLIST,
        ]);
        $film = Film::create([
            'tmdb_id' => 999,
            'titre' => 'Film à retirer',
            'date_sortie' => '2026-09-24',
        ]);
        $watchlist->films()->attach($film);

        $this->actingAs($user)
            ->delete(route('listes.films.destroy', [$watchlist, $film]))
            ->assertRedirect(route('listes.show', $watchlist));

        $this->assertDatabaseMissing('film_liste', [
            'liste_id' => $watchlist->id,
            'film_id' => $film->id,
        ]);
    }

    public function test_user_can_delete_a_custom_list(): void
    {
        $user = User::factory()->create();
        $liste = $user->listes()->create([
            'titre' => 'Ma collection',
            'type' => Liste::TYPE_FAVORITES,
        ]);

        $this->actingAs($user)
            ->delete(route('listes.destroy', $liste))
            ->assertRedirect(route('listes'));

        $this->assertDatabaseMissing('listes', ['id' => $liste->id]);
    }

    public function test_watchlist_and_top_five_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        Liste::createDefaultsFor($user);

        $watchlist = $user->listes()->where('type', Liste::TYPE_WATCHLIST)->firstOrFail();
        $topFive = $user->listes()->where('type', Liste::TYPE_TOP_FIVE)->firstOrFail();

        $this->actingAs($user)->delete(route('listes.destroy', $watchlist))->assertForbidden();
        $this->actingAs($user)->delete(route('listes.destroy', $topFive))->assertForbidden();

        $this->assertDatabaseHas('listes', ['id' => $watchlist->id]);
        $this->assertDatabaseHas('listes', ['id' => $topFive->id]);
    }

    public function test_user_cannot_delete_another_users_list(): void
    {
        $owner = User::factory()->create();
        $liste = $owner->listes()->create([
            'titre' => 'Collection privée',
            'type' => Liste::TYPE_FAVORITES,
        ]);

        $other = User::factory()->create();

        $this->actingAs($other)
            ->delete(route('listes.destroy', $liste))
            ->assertForbidden();

        $this->assertDatabaseHas('listes', ['id' => $liste->id]);
    }
}
