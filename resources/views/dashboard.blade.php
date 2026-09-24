@include('components.navbar')
@vite(['resources/css/calendar.css', 'resources/js/calendar.js'])

<h1>Tableau de bord</h1>


<form method="GET" action="{{ route('film.list') }}">
    <input
        type="search"
        name="q"
        value="{{ request('q') }}"
        placeholder="Rechercher un film..."
    >
    <button type="submit">Rechercher</button>
</form>

<section>
    <h2>Recommandations</h2>

    <p>Suggestions pour le genre : {{ $genre }}</p>

    <h3>Films de votre watchlist</h3>

    @if ($watchlistFilms->isEmpty())
        <p>Aucun film de votre watchlist ne correspond à ce genre.</p>
    @else
        <ul>
            @foreach ($watchlistFilms as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</section>

<section>
    <h3>On pense que ça pourrait vous plaire aussi</h3>

    @if ($recommendations->isEmpty())
        <p>Aucune autre recommandation disponible.</p>
    @else
        <ul>
            @foreach ($recommendations as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</section>

<section>
    <h2>Mon Top 5</h2>

    @if ($topFive && $topFive->films->isNotEmpty())
        <ol>
            @foreach ($topFive->films as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ol>
    @else
        <p>Ton Top 5 est encore vide.</p>
    @endif

    @if ($topFive)
        <a href="{{ route('listes.show', $topFive) }}">
            Modifier mon Top 5
        </a>
    @endif
</section>


<h2>Calendrier des sorties</h2>
<p>Prévoyez votre prochaine session cinéma !</p>

@if (session('status'))
    <p>{{ session('status') }}</p>
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

<script type="application/json" id="release-films-data">@json($releaseFilms)</script>
