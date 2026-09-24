@include('components.navbar')
@vite('resources/css/film.css')

<main class="film-detail-page">
	<a class="film-back-link" href="{{ route('film.list') }}" onclick="if (document.referrer) { event.preventDefault(); window.history.back(); }">
		<span aria-hidden="true">←</span> Retour aux films
	</a>

	<section class="film-detail-hero">
		<div class="film-detail-poster-wrap">
			@if ($film->hasVerifiedPoster())
				<img class="film-poster" src="{{ $film->affiche_url }}" alt="Affiche du film {{ $film->titre }}" loading="lazy" onerror="this.classList.add('is-broken'); this.nextElementSibling.classList.add('is-visible')">
				<div class="film-poster film-poster--placeholder poster-fallback" aria-label="Affiche indisponible"></div>
			@else
				<div class="film-poster film-poster--placeholder" aria-label="Affiche indisponible"></div>
			@endif
		</div>

		<div class="film-detail-copy">
			<span class="film-detail-kicker">Fiche du film</span>
			<h1>{{ $film->titre }}</h1>

			<div class="film-detail-tags">
				<span>{{ $film->genre ?: 'Film' }}</span>
				@if ($film->annee_sortie)
					<span>{{ $film->annee_sortie }}</span>
				@endif
			</div>

			<div class="film-detail-rating" aria-label="{{ $film->note ? $film->note.'/5' : 'Film non noté' }}">
				<span class="film-stars" aria-hidden="true">★★★★★</span>
				@if ($film->note)
					<strong>{{ number_format((float) $film->note, 1) }}</strong>
					<span>/ 5 · {{ $film->avis_count }} avis</span>
				@else
					<span>Pas encore noté</span>
				@endif
			</div>

			<dl class="film-facts">
				<div>
					<dt>Sortie</dt>
					<dd>{{ optional($film->date_sortie)->format('d/m/Y') ?: 'Date inconnue' }}</dd>
				</div>
				<div>
					<dt>Durée</dt>
					<dd>{{ $film->duree_minutes ? $film->duree_minutes.' min' : 'Non renseignée' }}</dd>
				</div>
				<div>
					<dt>Statut</dt>
					<dd>{{ $film->statut_sortie ?: 'Non renseigné' }}</dd>
				</div>
			</dl>

			@auth
				@if ($lists->isNotEmpty())
					<form class="film-list-form" method="POST" action="{{ route('listes.films.store', $lists->first()) }}">
						@csrf
						<label for="liste_id">Ajouter à une liste</label>
						<div>
							<select id="liste_id" required onchange="this.form.action = '{{ url('/listes') }}/' + this.value + '/films'">
								<option value="">Choisir une liste</option>
								@foreach ($lists as $list)
									<option value="{{ $list->id }}">{{ $list->titre }}</option>
								@endforeach
							</select>
							<input type="hidden" name="film_id" value="{{ $film->id }}">
							<button type="submit">Ajouter</button>
						</div>
					</form>
				@endif
			@endauth
		</div>
	</section>

	<section class="film-detail-section" aria-labelledby="film-description-title">
		<span class="film-detail-kicker">À propos</span>
		<h2 id="film-description-title">Synopsis</h2>
		<p>{{ $film->description ?: 'Aucun synopsis disponible pour le moment.' }}</p>
	</section>
</main>