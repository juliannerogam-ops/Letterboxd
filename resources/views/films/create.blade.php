@include('components.navbar')

<h1>Créer un film</h1>

@if ($errors->any())
    <div>
        <p>Le film n'a pas pu être enregistré :</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('film.create') }}" method="POST">
    @csrfcd ..cd

    <label for="titre">Titre :</label>
    <input type="text" name="titre" id="titre" value="{{ old('titre') }}" required>

    <label for="genre">Genre :</label>
    <input type="text" name="genre" id="genre" value="{{ old('genre') }}">

    <label for="description">Description :</label>
    <textarea name="description" id="description">{{ old('description') }}</textarea>

    <label for="annee_sortie">Année de sortie :</label>
    <input type="number" name="annee_sortie" id="annee_sortie" value="{{ old('annee_sortie') }}">

    <label for="date_sortie">Date de sortie :</label>
    <input type="date" name="date_sortie" id="date_sortie" value="{{ old('date_sortie') }}">

    <label for="duree_minutes">Durée (minutes) :</label>
    <input type="number" name="duree_minutes" id="duree_minutes" value="{{ old('duree_minutes') }}">

    <label for="affiche_url">URL de l'affiche :</label>
    <input type="url" name="affiche_url" id="affiche_url" value="{{ old('affiche_url') }}">

    <label for="statut_sortie">Statut de sortie :</label>
    <input type="text" name="statut_sortie" id="statut_sortie" value="{{ old('statut_sortie') }}">

    <label for="derniere_synchronisation">Dernière synchronisation :</label>
    <input type="datetime-local" name="derniere_synchronisation" id="derniere_synchronisation" value="{{ old('derniere_synchronisation') }}">

    <label for="realisateur">Réalisateur :</label>
    <input type="text" name="realisateur" id="realisateur" value="{{ old('realisateur') }}">

    <button type="submit">Créer le film</button>
</form>