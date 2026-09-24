@include('components.navbar')

<h1>{{ $liste->titre }}</h1>

@if ($liste->description)
    <p>{{ $liste->description }}</p>
@endif

<h2>Ajouter un film</h2>

<form method="POST" action="{{ route('listes.films.store', $liste) }}">
    @csrf

    <label for="film_id">Film :</label>
    <select id="film_id" name="film_id" required>
        <option value="">Choisir un film</option>
        @foreach ($films as $film)
            <option value="{{ $film->id }}">{{ $film->titre }}</option>
        @endforeach
    </select>

    <button type="submit">Ajouter à la liste</button>
</form>

<h2>Films de la liste</h2>

@if ($liste->films->isEmpty())
    <p>Cette liste ne contient encore aucun film.</p>
@else
    <ul>
        @foreach ($liste->films as $film)
            <li>
                <a href="{{ route('film.show', ['id' => $film->id]) }}">{{ $film->titre }}</a>
            </li>
        @endforeach
    </ul>
@endif
