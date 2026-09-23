<!-- Generate form to create a new Pokemon -->
<form action="{{ route('pokemon.edit', ['id' => $pokemon->id]) }}" method="POST">
    @csrf
    <label for="name">Nom :</label>
    <input type="text" name="name" id="name" value="{{ $pokemon->name }}" required>
    <label for="level">Niveau :</label>
    <input type="number" name="level" id="level" value="{{ $pokemon->level }}" required>
    <label for="description">Description :</label>
    <textarea name="description" id="description" required>{{ $pokemon->description }}</textarea>
    <button type="submit">Mettre à jour le Pokémon</button>
</form>