@include('components.navbar')
@vite(['resources/css/dashboard.css', 'resources/css/calendar.css', 'resources/js/calendar.js'])

<div class="mood-dashboard">
    <header class="dashboard-topbar">
        <form method="GET" action="{{ route('film.list') }}" class="topbar-search">
            <span class="search-icon" aria-hidden="true">⌕</span>
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Rechercher un film, un réalisateur..."
            >
        </form>

        <button type="button" class="profile-trigger" aria-label="Profil">
            <span class="profile-avatar">{{ strtoupper(substr(Auth::user()->pseudo ?: Auth::user()->name, 0, 1)) }}</span>
        </button>
    </header>

    <section class="mood-hero">
        <div class="hero-sheen" aria-hidden="true"></div>
        <div class="hero-content">
            <span class="hero-label">NOUVEAU</span>
            <h1>Quel est ton mood <br>d'aujourd'hui ?</h1>
            <p>Fais notre test et découvre des films qui te correspondent vraiment.</p>
            <a href="{{ route('recommendations.genre.create') }}" class="hero-button">Lancer le test</a>
        </div>
    </section>

    <section class="movie-picker">
        <div class="section-title-row">
            <h2>Envie de regarder un film ?</h2>
            <a href="{{ route('recommendations.genre.create') }}">Voir tout</a>
        </div>

        <div class="film-row">
            @php
                $featuredFilms = $recommendations->take(4);
            @endphp

            @forelse ($featuredFilms as $film)
                <article class="film-card">
                    <div class="film-poster poster-{{ $loop->index % 4 + 1 }}" aria-hidden="true"></div>
                    <div class="film-info">
                        <h3>{{ $film->titre }}</h3>
                        <span>{{ $film->genre ?? 'Film' }}</span>
                    </div>
                </article>
            @empty
                @for ($i = 0; $i < 4; $i++)
                    <article class="film-card placeholder-card">
                        <div class="film-poster poster-placeholder" aria-hidden="true"></div>
                        <div class="film-info">
                            <h3>À découvrir</h3>
                            <span>Notre sélection</span>
                        </div>
                    </article>
                @endfor
            @endforelse
        </div>
    </section>

    <section class="base-dashboard-panel">
        <div class="legacy-block">
            <h3>Recommandations</h3>
            <p class="muted-copy">Suggestions pour le genre : {{ $genre }}</p>

            <div class="nested-section">
                <h4>Films de votre watchlist</h4>
                @if ($watchlistFilms->isEmpty())
                    <p>Aucun film de votre watchlist ne correspond à ce genre.</p>
                @else
                    <ul>
                        @foreach ($watchlistFilms as $film)
                            <li>
                                <a href="{{ route('film.show', ['id' => $film->id]) }}">{{ $film->titre }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="nested-section">
                <h4>On pense que ça pourrait vous plaire aussi</h4>
                @if ($recommendations->isEmpty())
                    <p>Aucune autre recommandation disponible.</p>
                @else
                    <ul>
                        @foreach ($recommendations as $film)
                            <li>
                                <a href="{{ route('film.show', ['id' => $film->id]) }}">{{ $film->titre }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="legacy-block top-five-block">
            <h3>Mon Top 5</h3>
            @if ($topFive && $topFive->films->isNotEmpty())
                <ol>
                    @foreach ($topFive->films as $film)
                        <li><a href="{{ route('film.show', ['id' => $film->id]) }}">{{ $film->titre }}</a></li>
                    @endforeach
                </ol>
            @else
                <p>Ton Top 5 est encore vide.</p>
            @endif

            @if ($topFive)
                <a href="{{ route('listes.show', $topFive) }}" class="inline-link">Modifier mon Top 5</a>
            @endif
        </div>

        <div class="legacy-block release-box">
            <h3>Calendrier des sorties</h3>
            <p class="muted-copy">Prévoyez votre prochaine session cinéma !</p>

            @if (session('status'))
                <p class="status-message">{{ session('status') }}</p>
            @endif

            <section class="release-calendar" aria-labelledby="release-calendar-title" data-csrf-token="{{ csrf_token() }}">
                <div class="release-calendar-header">
                    <button type="button" id="previous-month" aria-label="Mois précédent">&larr;</button>
                    <h2 id="release-calendar-title"></h2>
                    <button type="button" id="next-month" aria-label="Mois suivant">&rarr;</button>
                </div>

                <div class="calendar-weekdays" aria-hidden="true">
                    <span>Lun</span>
                    <span>Mar</span>
                    <span>Mer</span>
                    <span>Jeu</span>
                    <span>Ven</span>
                    <span>Sam</span>
                    <span>Dim</span>
                </div>

                <div id="calendar-days" class="calendar-days"></div>

                <div id="selected-release-films" class="selected-release-films" aria-live="polite">
                    <p>Sélectionnez un jour pour voir les sorties.</p>
                </div>
            </section>
        </div>
    </section>

    <section class="bottom-grid">
        <div class="bottom-card activity-card">
            <div class="mini-header">
                <h3>Votre activité</h3>
            </div>

            <section class="release-calendar" aria-labelledby="release-calendar-title" data-csrf-token="{{ csrf_token() }}">
                <div class="release-calendar-header">
                    <button type="button" id="previous-month" aria-label="Mois précédent">&larr;</button>
                    <h2 id="release-calendar-title"></h2>
                    <button type="button" id="next-month" aria-label="Mois suivant">&rarr;</button>
                </div>

                <div class="calendar-weekdays" aria-hidden="true">
                    <span>Lun</span>
                    <span>Mar</span>
                    <span>Mer</span>
                    <span>Jeu</span>
                    <span>Ven</span>
                    <span>Sam</span>
                    <span>Dim</span>
                </div>

                <div id="calendar-days" class="calendar-days"></div>

                <div id="selected-release-films" class="selected-release-films" aria-live="polite">
                    <p>Sélectionnez un jour pour voir les sorties.</p>
                </div>
            </section>
        </div>

        <div class="bottom-card favorites-card">
            <div class="mini-header">
                <h3>Films préférés</h3>
            </div>

            <div class="favorites-grid">
                @php
                    $favoriteFilms = $watchlistFilms->take(4);
                @endphp

                @forelse ($favoriteFilms as $film)
                    <div class="favorite-tile">
                        <div class="favorite-thumb thumb-{{ $loop->index % 4 + 1 }}" aria-hidden="true"></div>
                        <span>{{ $film->titre }}</span>
                    </div>
                @empty
                    @for ($i = 0; $i < 4; $i++)
                        <div class="favorite-tile empty-tile">
                            <div class="favorite-thumb thumb-placeholder" aria-hidden="true"></div>
                            <span>À venir</span>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    <script type="application/json" id="release-films-data">@json($releaseFilms)</script>
</div>
