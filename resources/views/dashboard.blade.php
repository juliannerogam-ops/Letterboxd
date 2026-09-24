@include('components.navbar')

<h1>Tableau de bord</h1>

<section>
    <h2>Recommandations</h2>

    <p>Suggestions pour le genre : {{ $genre }}</p>

    <h3>Films de votre watchlist</h3>

    @if ($watchlistFilms->isEmpty())
        <p>Aucun film de votre watchlist ne correspond à ce genre.</p>
    @else
        <ul>
            @foreach ($watchlistFilms as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</section>

<section>
    <h3>On pense que ça pourrait vous plaire aussi</h3>

    @if ($recommendations->isEmpty())
        <p>Aucune autre recommandation disponible.</p>
    @else
        <ul>
            @foreach ($recommendations as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</section>

<section>
    <h2>Mon Top 5</h2>

    @if ($topFive && $topFive->films->isNotEmpty())
        <ol>
            @foreach ($topFive->films as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ol>
    @else
        <p>Ton Top 5 est encore vide.</p>
    @endif

    @if ($topFive)
        <a href="{{ route('listes.show', $topFive) }}">
            Modifier mon Top 5
        </a>
    @endif
</section>
