@include('components.navbar')

<h1>Quel genre de film souhaitez-vous voir aujourd'hui ?</h1>

<form method="POST" action="{{ route('recommendations.genre.store') }}">
    @csrf

    <label for="genre">Genre :</label>

    <select id="genre" name="genre" required>
        <option value="">Choisir un genre</option>

        @foreach ($genres as $genre)
            <option value="{{ $genre }}">
                {{ $genre }}
            </option>
        @endforeach
    </select>

    <button type="submit">Voir mes recommandations</button>
</form>