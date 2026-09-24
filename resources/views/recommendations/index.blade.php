@include('components.navbar')
@vite('resources/css/recommendation-results.css')

<main class="recommendation-results">
    <header class="recommendation-results-header">
        <h1>Voici tes recommandations !</h1>
        <p>Selon ton mood et tes envies, on a sélectionné ces films pour toi.</p>
    </header>

    <nav class="recommendation-filters" aria-label="Filtrer les recommandations">
        @foreach ($genres as $genre)
            <a
                class="recommendation-filter {{ $activeGenre === $genre ? 'is-active' : '' }}"
                href="{{ route('recommendations.index', $genre === 'Tous' ? [] : ['genre' => $genre]) }}"
                aria-current="{{ $activeGenre === $genre ? 'page' : 'false' }}"
            >
                {{ $genre }}
            </a>
        @endforeach
    </nav>

    <section class="recommendation-results-grid" aria-label="Films recommandés">
        @forelse ($films as $film)
            <article class="result-film-card">
                @if ($film->affiche_url)
                    <img src="{{ $film->affiche_url }}" alt="Affiche de {{ $film->titre }}">
                @else
                    <div class="result-film-poster-placeholder" aria-label="Affiche indisponible"></div>
                @endif

                <h2>{{ $film->titre }}</h2>
                <p class="result-film-genre">{{ $film->genre ?: 'Film' }}</p>
                <div class="result-film-meta">
                    <span class="result-film-rating" aria-label="{{ $film->note ? $film->note.'/5' : 'Film non noté' }}">
                        <span class="star" aria-hidden="true">★</span>
                        @if ($film->note)
                            <span>{{ number_format((float) $film->note, 1) }}</span>
                            <span class="rating-scale">/5</span>
                        @else
                            <span>Non noté</span>
                        @endif
                    </span>
                    @if ($film->avis_count > 0)
                        <span class="result-film-reviews">({{ $film->avis_count }} avis)</span>
                    @else
                        <span class="result-film-reviews">Avis indisponibles</span>
                    @endif
                </div>
                @if ($film->date_sortie)
                    <time class="result-film-date" datetime="{{ $film->date_sortie->toDateString() }}">
                        <span>Sortie</span>
                        {{ $film->date_sortie->format('d/m/Y') }}
                    </time>
                @endif
            </article>
        @empty
            @for ($i = 0; $i < 8; $i++)
                <article class="result-film-card result-film-card--placeholder">
                    <div class="result-film-poster-placeholder" aria-hidden="true"></div>
                    <h2>À découvrir</h2>
                    <p class="result-film-genre">Sélection à venir</p>
                    <div class="result-film-meta"><span class="result-film-reviews">(0 avis)</span></div>
                    <span class="result-film-date">Date à venir</span>
                </article>
            @endfor
        @endforelse
    </section>
</main>