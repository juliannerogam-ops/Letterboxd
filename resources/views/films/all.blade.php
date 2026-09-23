<h1>Liste des films</h1>
<a href="{{ route('film.view_create') }}">Créer un film</a>

@foreach ($films as $film)
    <h3>{{ $film->titre }}</h3>
    <p>{{ $film->description }}</p>
    <a href="{{ route('film.show', ['id' => $film->id]) }}">Voir le film</a>
    <a href="{{ route('film.edit_view', ['id' => $film->id]) }}">Modifier le film</a>
    <a href="{{ route('film.delete', ['id' => $film->id]) }}">Supprimer le film</a>
    <br>
@endforeach