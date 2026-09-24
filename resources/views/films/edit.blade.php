<h1>Modifier le film</h1>

<form action="{{ route('film.edit', ['id' => $film->id]) }}" method="POST">
    @csrf

    @if ($errors->any())
        <div role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <input type="hidden" name="tmdb_id" value="{{ old('tmdb_id', $film->tmdb_id) }}">

    <label for="titre">Titre :</label>
    <input type="text" name="titre" id="titre" value="{{ old('titre', $film->titre) }}" required>

    <label for="genre">Genre :</label>
    <input type="text" name="genre" id="genre" value="{{ old('genre', $film->genre) }}">

    <label for="description">Description :</label>
    <textarea name="description" id="description">{{ old('description', $film->description) }}</textarea>

    <label for="annee_sortie">Année de sortie :</label>
    <input type="number" name="annee_sortie" id="annee_sortie" value="{{ old('annee_sortie', $film->annee_sortie) }}">

    <label for="date_sortie">Date de sortie :</label>
    <input type="date" name="date_sortie" id="date_sortie" value="{{ old('date_sortie', optional($film->date_sortie)->format('Y-m-d')) }}">

    <label for="duree_minutes">Durée (minutes) :</label>
    <input type="number" name="duree_minutes" id="duree_minutes" value="{{ old('duree_minutes', $film->duree_minutes) }}">

    <label for="affiche_url">URL de l'affiche :</label>
    <input type="url" name="affiche_url" id="affiche_url" value="{{ old('affiche_url', $film->affiche_url) }}">

    <label for="statut_sortie">Statut de sortie :</label>
    <input type="text" name="statut_sortie" id="statut_sortie" value="{{ old('statut_sortie', $film->statut_sortie) }}">

    <label for="derniere_synchronisation">Dernière synchronisation :</label>
    <input type="datetime-local" name="derniere_synchronisation" id="derniere_synchronisation" value="{{ old('derniere_synchronisation', optional($film->derniere_synchronisation)->format('Y-m-d\TH:i')) }}">

    <button type="submit">Mettre à jour le film</button>
</form>