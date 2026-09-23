<h1>Liste des Pokémon</h1>
<a href="{{ route('pokemon.view_create') }}">Créer un nouveau Pokémon</a>
@foreach ($pokemons as $pokemon)
    <h3>{{ $pokemon->name }}</h3>
    <p>{{ $pokemon->description }}</p>
    <a href="{{ route('pokemon.show', ['id' => $pokemon->id]) }}">Voir le Pokémon</a>
    <a href="{{ route('pokemon.edit_view', ['id' => $pokemon->id]) }}">Modifier le Pokémon</a>
    <a href="{{ route('pokemon.delete', ['id' => $pokemon->id])}}">Supprimer le Pokémon</a>
    </br>
@endforeach
