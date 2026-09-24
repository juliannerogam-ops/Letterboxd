@include('components.navbar')
@vite('resources/css/lists.css')

<main class="lists-index-page">
	<header class="lists-index-header">
		<div>
			<span class="list-eyebrow">Ta collection</span>
			<h1>Mes listes</h1>
			<p>Retrouve les films que tu veux garder ou classer.</p>
		</div>
		<a class="lists-create-link" href="{{ route('listes.create') }}">Créer une liste <span aria-hidden="true">+</span></a>
	</header>

	<form class="lists-search" method="GET" action="{{ route('listes') }}">
		<label for="lists-search-input">Rechercher dans mes listes</label>
		<div>
			<input id="lists-search-input" type="search" name="q" value="{{ $search }}" placeholder="Ex. films à voir">
			<button type="submit">Rechercher</button>
		</div>
	</form>

	<section class="lists-index-grid" aria-label="Mes listes">
		@if ($lists->isEmpty())
			<div class="lists-empty-state">
				<h2>Aucune liste pour le moment</h2>
				<p>Crée ta première liste pour organiser tes prochains films.</p>
				<a class="lists-create-link" href="{{ route('listes.create') }}">Créer une liste <span aria-hidden="true">+</span></a>
			</div>
		@else
			@foreach ($lists as $list)
				<article class="lists-index-item">
					<div>
						<span class="list-section-kicker">Liste personnelle</span>
						<h2><a href="{{ route('listes.show', $list) }}">{{ $list->titre }}</a></h2>
						<p>{{ $list->description ?: 'Une sélection de films à retrouver plus tard.' }}</p>
					</div>
					<span class="lists-index-count">{{ $list->films->count() }} films</span>
				</article>
			@endforeach
		@endif
	</section>
</main>
