<h1>{{ $film->titre }}</h1>
<h3>{{ $film->genre }}</h3>
<p>{{ $film->description }}</p>
<p>Sortie : {{ optional($film->date_sortie)->format('d/m/Y') }}</p>
<p>Durée : {{ $film->duree_minutes }} minutes</p>
<p>Statut : {{ $film->statut_sortie }}</p>