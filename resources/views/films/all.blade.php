@include('components.navbar')

<h1>Liste des films</h1>

@auth
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">
            Déconnexion
        </button>
    </form>
@endauth

<a href="{{ route('film.view_create') }}">Créer un film</a>
<a href="{{ route('film.import_view') }}">Importer depuis TMDb</a>

<form method="GET" action="{{ route('film.list') }}">
    <input
        type="search"
        name="q"
        value="{{ $search }}"
        placeholder="Rechercher un film..."
    >

    <button type="submit">Rechercher</button>
</form>

@foreach ($films as $film)
    <h3>{{ $film->titre }}</h3>
    <p>{{ $film->description }}</p>
    <a href="{{ route('film.show', ['id' => $film->id]) }}">Voir le film</a>
    <a href="{{ route('film.edit_view', ['id' => $film->id]) }}">Modifier le film</a>
    <a href="{{ route('film.delete', ['id' => $film->id]) }}">Supprimer le film</a>
    <br>
@endforeach

