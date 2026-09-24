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

    @if ($liste->isTopFive())
        <label for="position">Rang :</label>
        <select id="position" name="position" required>
            <option value="">Choisir un rang</option>
            @for ($position = 1; $position <= 5; $position++)
                <option value="{{ $position }}">{{ $position }}</option>
            @endfor
        </select>
    @endif

    <button type="submit">Ajouter à la liste</button>
</form>

<h2>Films de la liste</h2>

@if ($liste->films->isEmpty())
    <p>Cette liste ne contient encore aucun film.</p>
@else
    <ul>
        @foreach ($liste->films as $film)
            <li>
                @if ($liste->isTopFive())
                    #{{ $film->pivot->position }}
                @endif
                <a href="{{ route('film.show', ['id' => $film->id]) }}">{{ $film->titre }}</a>
            </li>
        @endforeach
    </ul>
@endif

