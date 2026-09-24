@include('components.navbar')

<h1>Tableau de bord</h1>

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

