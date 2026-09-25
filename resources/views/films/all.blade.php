@include('components.navbar')
@vite('resources/css/film.css')

<main class="film-library-page">
    <header class="film-library-header">
        <div>
            <span class="film-detail-kicker">Catalogue</span>
            <h1>Liste des films</h1>
            <p>Retrouve les films de la collection et ouvre leur fiche pour découvrir tous leurs détails.</p>
        </div>
        <div class="film-library-actions">
            <a class="film-library-link film-library-link--quiet" href="{{ route('film.import_view') }}">Importer depuis TMDb <span aria-hidden="true">↗</span></a>
        </div>
    </header>

    <form class="film-library-search" method="GET" action="{{ route('film.list') }}">
        <label for="film-search">Rechercher dans le catalogue</label>
        <div>
            <span aria-hidden="true">⌕</span>
            <input id="film-search" type="search" name="q" value="{{ $search }}" placeholder="Titre du film...">
            <button type="submit">Rechercher</button>
        </div>
    </form>

    <div class="film-library-meta">
        <span>{{ $films->count() }} {{ $films->count() > 1 ? 'films' : 'film' }}</span>
        @if ($search !== '')
            <span>Résultats pour « {{ $search }} »</span>
        @endif
    </div>

    <section class="film-library-grid" aria-label="Catalogue des films">
        @forelse ($films as $film)
            <article class="film-library-card">
                <a class="film-library-poster-link" href="{{ route('film.show', ['id' => $film->id]) }}">
                    @if ($film->hasVerifiedPoster())
                        <img class="film-library-poster" src="{{ $film->affiche_url }}" alt="Affiche de {{ $film->titre }}" loading="lazy" onerror="this.classList.add('is-broken'); this.nextElementSibling.classList.add('is-visible')">
                        <span class="film-library-poster-placeholder poster-fallback" aria-hidden="true"></span>
                    @else
                        <span class="film-library-poster-placeholder" aria-hidden="true"></span>
                    @endif
                    <span class="film-library-poster-cta">Voir la fiche <span aria-hidden="true">↗</span></span>
                </a>
                <div class="film-library-card-copy">
                    <div>
                        <span class="film-library-genre">{{ $film->genre ?: 'Film' }}</span>
                        <h2><a href="{{ route('film.show', ['id' => $film->id]) }}">{{ $film->titre }}</a></h2>
                    </div>
                    <span class="film-library-year">{{ $film->annee_sortie ?: '—' }}</span>
                </div>
                <div class="film-library-admin-links">
                    <a href="{{ route('film.edit_view', ['id' => $film->id]) }}">Modifier</a>
                    <a href="{{ route('film.delete', ['id' => $film->id]) }}">Supprimer</a>
                </div>
            </article>
        @empty
            <div class="film-library-empty">
                <span class="film-detail-kicker">Aucun résultat</span>
                <h2>Le catalogue est encore silencieux.</h2>
                <p>Essaie une autre recherche ou ajoute un nouveau film.</p>
                <a class="film-library-link" href="{{ route('film.view_create') }}">Créer un film <span aria-hidden="true">+</span></a>
            </div>
        @endforelse
    </section>
</main>

