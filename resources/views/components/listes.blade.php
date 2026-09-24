<h1>Mes listes</h1>

@if ($lists->isEmpty())
    <p>Tu n'as encore créé aucune liste.</p>
@else
    @foreach ($lists as $list)
        <article>
            <h2>
                <a href="{{ route('listes.show', $list) }}">{{ $list->titre }}</a>
            </h2>
            <p>{{ $list->description }}</p>
            <p>{{ $list->films->count() }} film(s)</p>
        </article>
    @endforeach
@endif