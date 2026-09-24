<h1>{{ $film->titre }}</h1>
<h3>{{ $film->genre }}</h3>
<p>{{ $film->description }}</p>
<p>Sortie : {{ optional($film->date_sortie)->format('d/m/Y') }}</p>
<p>Durée : {{ $film->duree_minutes }} minutes</p>
<p>Statut : {{ $film->statut_sortie }}</p>

@auth
	@if ($lists->isNotEmpty())
		<h2>Ajouter à une liste</h2>

		<form method="POST" action="{{ route('listes.films.store', $lists->first()) }}">
			@csrf

			<label for="liste_id">Liste :</label>
			<select id="liste_id" required onchange="this.form.action = '{{ url('/listes') }}/' + this.value + '/films'">
				<option value="">Choisir une liste</option>
				@foreach ($lists as $list)
					<option value="{{ $list->id }}">{{ $list->titre }}</option>
				@endforeach
			</select>
			<input type="hidden" name="film_id" value="{{ $film->id }}">

			<button type="submit">Ajouter</button>
		</form>
	@else
		<p>Ce film est déjà présent dans toutes vos listes.</p>
	@endif
@endauth