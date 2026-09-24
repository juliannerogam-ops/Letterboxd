@include('components.navbar')

<h1>Créer une liste</h1>

@if ($errors->any())
    <ul role="alert">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('listes.store') }}">
    @csrf

    <label for="titre">Nom de la liste :</label>
    <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required>

    <label for="description">Description :</label>
    <textarea id="description" name="description">{{ old('description') }}</textarea>

    <button type="submit">Créer la liste</button>
</form>
