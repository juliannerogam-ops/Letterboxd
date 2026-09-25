@include('components.navbar')
@vite('resources/css/lists.css')

<main class="lists-index-page">
	<header class="lists-index-header">
		<div>
			<span class="list-eyebrow">TON ESPACE CINÉMA</span>
			<h1>Mes listes<span>.</span></h1>
			<p>Garde une trace des films qui méritent une prochaine séance.</p>
		</div>
		<a class="lists-create-link" href="{{ route('listes.create') }}"><span aria-hidden="true">＋</span> Nouvelle liste</a>
	</header>

	<form class="lists-search" method="GET" action="{{ route('listes') }}">
		<label for="lists-search-input"><span aria-hidden="true">⌕</span> Rechercher dans mes listes</label>
		<div>
			<input id="lists-search-input" type="search" name="q" value="{{ $search }}" placeholder="Ex. films à voir">
			<button type="submit">Chercher</button>
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
					<a class="lists-index-item-link" href="{{ route('listes.show', $list) }}">
						<div class="lists-index-item-topline">
							<span class="list-section-kicker">{{ $list->type === \App\Models\Liste::TYPE_WATCHLIST ? 'À VOIR' : 'COLLECTION' }}</span>
							<span class="lists-index-arrow" aria-hidden="true">↗</span>
						</div>
						<h2>{{ $list->titre }}</h2>
						<p>{{ $list->description ?: 'Une sélection de films à retrouver plus tard.' }}</p>
						<div class="lists-index-preview" aria-hidden="true">
							@foreach ($list->films->take(4) as $film)
								@if ($film->hasVerifiedPoster())
									<img src="{{ $film->affiche_url }}" alt="">
								@else
									<span></span>
								@endif
							@endforeach
							@if ($list->films->isEmpty())
								<span class="lists-index-preview-empty">Ajoute ton premier film</span>
							@endif
						</div>
					</a>
					<div class="lists-index-footer">
						<span class="lists-index-count">{{ $list->films->count() }} films</span>
						<span class="lists-index-open">Ouvrir la liste</span>
					</div>
				</article>
			@endforeach
		@endif
	</section>
</main>
