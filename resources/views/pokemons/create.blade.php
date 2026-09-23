<!-- Generate form to create a new Pokemon -->
<form action="{{ route('pokemon.create') }}" method="POST">
    @csrf
    <label for="name">Nom :</label>
    <input type="text" name="name" id="name" required>
    <label for="level">Niveau :</label>
    <input type="number" name="level" id="level" required>
    <label for="description">Description :</label>
    <textarea name="description" id="description" required></textarea>
    <button type="submit">Créer le Pokémon</button>
</form>