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

    <section class="legacy-block recommendation-block">
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
    </section>

    <section class="activity-grid-shell">
        <div class="activity-panel">
            <div class="panel-header-row">
                <h3>Votre activité</h3>
                <label class="month-select-wrap" aria-label="Choisir le mois">
                    <span class="month-label">Mois</span>
                    <select class="month-select" name="activity_month">
                        <option value="janvier">Janvier</option>
                        <option value="fevrier">Février</option>
                        <option value="mars">Mars</option>
                        <option value="avril">Avril</option>
                        <option value="mai">Mai</option>
                        <option value="juin">Juin</option>
                        <option value="juillet">Juillet</option>
                        <option value="aout">Août</option>
                        <option value="septembre" selected>Septembre</option>
                        <option value="octobre">Octobre</option>
                        <option value="novembre">Novembre</option>
                        <option value="decembre">Décembre</option>
                    </select>
                </label>
            </div>

            <div class="activity-calendar" aria-label="Journal de bord des moods">
                <div class="journal-day-name">Lun</div>
                <div class="journal-day-name">Mar</div>
                <div class="journal-day-name">Mer</div>
                <div class="journal-day-name">Jeu</div>
                <div class="journal-day-name">Ven</div>
                <div class="journal-day-name">Sam</div>
                <div class="journal-day-name">Dim</div>

                <button type="button" class="journal-day journal-day--muted"> </button>
                <button type="button" class="journal-day mood-rose">1</button>
                <button type="button" class="journal-day mood-sky">2</button>
                <button type="button" class="journal-day mood-amber">3</button>
                <button type="button" class="journal-day mood-mint">4</button>
                <button type="button" class="journal-day mood-lavender">5</button>
                <button type="button" class="journal-day mood-gray">6</button>

                <button type="button" class="journal-day mood-sky">7</button>
                <button type="button" class="journal-day mood-rose">8</button>
                <button type="button" class="journal-day mood-gray">9</button>
                <button type="button" class="journal-day mood-amber">10</button>
                <button type="button" class="journal-day mood-mint">11</button>
                <button type="button" class="journal-day mood-gray">12</button>
                <button type="button" class="journal-day mood-lavender">13</button>

                <button type="button" class="journal-day mood-sky">14</button>
                <button type="button" class="journal-day mood-rose">15</button>
                <button type="button" class="journal-day mood-mint">16</button>
                <button type="button" class="journal-day mood-amber">17</button>
                <button type="button" class="journal-day mood-gray">18</button>
                <button type="button" class="journal-day mood-rose">19</button>
                <button type="button" class="journal-day mood-sky">20</button>

                <button type="button" class="journal-day mood-amber">21</button>
                <button type="button" class="journal-day mood-gray">22</button>
                <button type="button" class="journal-day mood-sky">23</button>
                <button type="button" class="journal-day mood-mint">24</button>
                <button type="button" class="journal-day mood-rose">25</button>
                <button type="button" class="journal-day mood-amber">26</button>
                <button type="button" class="journal-day mood-gray">27</button>

                <button type="button" class="journal-day mood-sky">28</button>
                <button type="button" class="journal-day mood-mint">29</button>
                <button type="button" class="journal-day mood-rose">30</button>
                <button type="button" class="journal-day mood-pink"> </button>
                <button type="button" class="journal-day journal-day--muted"> </button>
                <button type="button" class="journal-day journal-day--muted"> </button>
                <button type="button" class="journal-day journal-day--muted"> </button>
            </div>
        </div>

        <div class="legacy-block release-box">
            <h3>Calendrier des sorties</h3>
            <p class="muted-copy">Prévoyez votre prochaine session cinéma !</p>

            @if (session('status'))
                <p class="status-message">{{ session('status') }}</p>
            @endif

            <section class="release-calendar" aria-labelledby="release-calendar-title" data-csrf-token="{{ csrf_token() }}">
                <div class="release-calendar-header">
                    <h2 id="release-calendar-title" class="release-calendar-title"></h2>

                    <div class="release-month-picker" aria-label="Choisir le mois">
                        <button type="button" class="month-nav month-nav-prev" data-direction="prev" aria-label="Mois précédent">‹</button>
                        <label class="release-month-select-wrap">
                            <select id="release-month-select" class="release-month-select" aria-label="Sélectionner le mois">
                                <option value="0">Janvier</option>
                                <option value="1">Février</option>
                                <option value="2">Mars</option>
                                <option value="3">Avril</option>
                                <option value="4">Mai</option>
                                <option value="5">Juin</option>
                                <option value="6">Juillet</option>
                                <option value="7">Août</option>
                                <option value="8" selected>Septembre</option>
                                <option value="9">Octobre</option>
                                <option value="10">Novembre</option>
                                <option value="11">Décembre</option>
                            </select>
                        </label>
                        <button type="button" class="month-nav month-nav-next" data-direction="next" aria-label="Mois suivant">›</button>
                    </div>
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

    <script type="application/json" id="release-films-data">@json($releaseFilms)</script>
</div>
