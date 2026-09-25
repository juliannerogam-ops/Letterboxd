@include('components.navbar')
@vite(['resources/css/film.css', 'resources/css/dashboard.css'])

<main class="film-search-page">
    <header class="film-search-header">
        <span>Explorer</span>
        <h1>Rechercher un film</h1>
        <form method="GET" action="{{ route('films.search') }}" class="film-search-form">
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Rechercher un film..."
                autofocus
            >
            <button type="submit">Rechercher</button>
        </form>
    </header>

    @if ($search === '')
        <p class="film-search-message">Saisis un titre pour commencer.</p>
    @elseif ($films->isEmpty())
        <p class="film-search-message">Aucun film trouvé pour « {{ $search }} ».</p>
    @else
        <section class="film-search-results" aria-label="Résultats de recherche">
            @foreach ($films as $film)
                <a class="film-search-result" href="{{ route('film.show', ['id' => $film->id]) }}">
                    @if ($film->hasVerifiedPoster())
                        <img src="{{ $film->affiche_url }}" alt="Affiche de {{ $film->titre }}">
                    @else
                        <div class="film-search-placeholder" aria-hidden="true"></div>
                    @endif
                    <span>
                        <strong>{{ $film->titre }}</strong>
                        <small>{{ $film->genre ?? 'Film' }}</small>
                    </span>
                </a>
            @endforeach
        </section>
    @endif
</main>
