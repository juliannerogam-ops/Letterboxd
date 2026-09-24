@include('components.navbar')
@vite('resources/css/lists.css')

<main class="list-page">
    <header class="list-page-header">
        <div class="list-page-header-row">
            <div>
                <h1>{{ $liste->titre }}</h1>
                @if ($liste->description)
                    <p>{{ $liste->description }}</p>
                @else
                    <p>Les films que tu veux garder près de toi.</p>
                @endif
            </div>
        </div>
    </header>

    <section class="list-add-panel" aria-labelledby="add-film-title">
        <div>
            <span class="list-section-kicker">Ta sélection</span>
            <h2 id="add-film-title">Ajouter un film</h2>
        </div>

        <form method="POST" action="{{ route('listes.films.store', $liste) }}" class="list-add-form">
            @csrf

            <label class="list-field">
                <span>Film</span>
                <select id="film_id" name="film_id" required>
                    <option value="">Choisir un film</option>
                    @foreach ($films as $film)
                        <option value="{{ $film->id }}">{{ $film->titre }}</option>
                    @endforeach
                </select>
            </label>

            @if ($liste->isTopFive())
                <label class="list-field list-field--rank">
                    <span>Rang</span>
                    <select id="position" name="position" required>
                        <option value="">Choisir</option>
                        @for ($position = 1; $position <= 5; $position++)
                            <option value="{{ $position }}">#{{ $position }}</option>
                        @endfor
                    </select>
                </label>
            @endif

            <button class="list-submit" type="submit">Ajouter à la liste <span aria-hidden="true">+</span></button>
        </form>
    </section>

    <section class="list-films-section" aria-labelledby="list-films-title">
        <div class="list-section-heading">
            <div>
                <span class="list-section-kicker">Classement</span>
                <h2 id="list-films-title">Films de la liste</h2>
            </div>
        </div>

        @if ($liste->films->isEmpty())
            <div class="list-empty-state">
                <span aria-hidden="true">✦</span>
                <p>Cette liste ne contient encore aucun film.</p>
            </div>
        @else
            <div class="list-film-grid">
                @foreach ($liste->films->sortBy(fn ($film) => $film->pivot->position ?? 99) as $film)
                    <div class="list-film-entry">
                        <a class="list-film-card" href="{{ route('film.show', ['id' => $film->id]) }}">
                            <div class="list-film-rank">#{{ $film->pivot->position ?? $loop->iteration }}</div>
                            @if ($film->hasVerifiedPoster())
                                <img src="{{ $film->affiche_url }}" alt="Affiche de {{ $film->titre }}">
                            @endif
                            <strong>{{ $film->titre }}</strong>
                            <span>{{ $film->genre ?? 'Film' }}</span>
                        </a>
                        @if ($liste->type === \App\Models\Liste::TYPE_WATCHLIST)
                            <form method="POST" action="{{ route('listes.films.destroy', [$liste, $film]) }}" onsubmit="return confirm('Retirer ce film de la watchlist ?')">
                                @csrf
                                @method('DELETE')
                                <button class="list-film-remove" type="submit">Retirer</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    @php
        $listFilmIds = $liste->films->pluck('id');
        $discoverFilms = $films->whereNotIn('id', $listFilmIds)->take(3);
    @endphp

    <section class="list-discover-panel" aria-labelledby="discover-films-title">
        <div class="list-section-heading">
            <div>
                <span class="list-section-kicker">Pour la suite</span>
                <h2 id="discover-films-title">À découvrir ensuite</h2>
            </div>
            <a class="list-discover-link" href="{{ route('recommendations.index') }}">Voir les recommandations <span aria-hidden="true">→</span></a>
        </div>

        @if ($discoverFilms->isEmpty())
            <p class="list-discover-empty">Ta sélection est complète. Explore les recommandations pour trouver ton prochain film.</p>
        @else
            <div class="list-discover-grid">
                @foreach ($discoverFilms as $film)
                    <a class="list-discover-card" href="{{ route('film.show', ['id' => $film->id]) }}">
                        @if ($film->hasVerifiedPoster())
                            <img src="{{ $film->affiche_url }}" alt="Affiche de {{ $film->titre }}">
                        @endif
                        <span>
                            <strong>{{ $film->titre }}</strong>
                            <small>{{ $film->genre ?? 'Film' }}</small>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</main>
