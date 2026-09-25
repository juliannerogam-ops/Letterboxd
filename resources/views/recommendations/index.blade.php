@include('components.navbar')
@vite('resources/css/recommendation-results.css')

<main class="recommendation-results">
    <header class="recommendation-results-header">
        <h1>
            @if ($selectedMood)
                Films pour ton mood : {{ $selectedMood }}
            @else
                Voici tes recommandations !
                <span class="recommendation-catalogue-label">Catalogue des films</span>
            @endif
        </h1>
        <p>
            {{ $selectedMood ? 'On a sélectionné ces films pour correspondre à ton humeur.' : 'Explore tous les films disponibles dans le catalogue.' }}
            @if ($selectedMood && $moodIntensity)
                <span class="results-intensity">Intensité {{ $moodIntensity }}/10</span>
            @endif
        </p>
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
        <a class="result-film-card result-film-link" href="{{ route('film.show', ['id' => $film->id]) }}">
                @if ($film->hasVerifiedPoster())
                    <img src="{{ $film->affiche_url }}" alt="Affiche de {{ $film->titre }}" onerror="this.classList.add('is-broken'); this.nextElementSibling.classList.add('is-visible')">
                    <div class="result-film-poster-placeholder poster-fallback" aria-label="Affiche indisponible"></div>
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
            </a>
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