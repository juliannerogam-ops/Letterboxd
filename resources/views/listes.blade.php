@include('components.navbar')

<h1>Mes listes</h1>

<form method="GET" action="{{ route('listes') }}">
    <input
        type="search"
        name="q"
        value="{{ $search }}"
        placeholder="Rechercher une liste..."
    >

    <button type="submit">Rechercher</button>
</form>

<a href="{{ route('listes.create') }}">Créer une liste</a>

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
