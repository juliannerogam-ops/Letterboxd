<h1>Importer un film depuis TMDb</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('film.import') }}" method="POST">
    @csrf
    <label for="url">Lien TMDb :</label>
    <input
        type="url"
        name="url"
        id="url"
        value="{{ old('url') }}"
        placeholder="https://www.themoviedb.org/movie/550"
        required
    >
    <button type="submit">Importer le film</button>
</form>

<a href="{{ route('film.list') }}">Retour a la liste des films</a>
