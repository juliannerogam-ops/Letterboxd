@include('components.navbar')
@vite('resources/css/film.css')

<main class="film-import-page">
    <a class="film-back-link" href="{{ route('film.list') }}">
        <span aria-hidden="true">←</span> Retour au catalogue
    </a>

    <section class="film-import-shell">
        <div class="film-import-content">
            <span class="film-detail-kicker">Ajouter au catalogue</span>
            <h1>Importer un film<br>depuis TMDb</h1>
            <p>Colle le lien d’un film pour récupérer automatiquement son affiche, son synopsis et ses informations.</p>

            @if ($errors->any())
                <div class="film-import-errors" role="alert">
                    @foreach ($errors->all() as $error)
                        <span>{{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <form class="film-import-form" action="{{ route('film.import') }}" method="POST">
                @csrf
                <label for="url">Lien TMDb</label>
                <div class="film-import-input-wrap">
                    <input
                        type="url"
                        name="url"
                        id="url"
                        value="{{ old('url') }}"
                        placeholder="https://www.themoviedb.org/movie/550"
                        required
                    >
                    <button type="submit">Importer <span aria-hidden="true">↗</span></button>
                </div>
            </form>

            <span class="film-import-note">Le lien doit provenir de themoviedb.org</span>
        </div>
    </section>
</main>
