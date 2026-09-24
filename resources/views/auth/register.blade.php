@include('components.navbar')

<h1>S'inscrire</h1>

<form action="{{ route('register') }}" method="POST">
    @csrf
    <label for="name">Nom :</label>
    <input type="text" name="name" id="name" value="{{ old('name') }}" required>

    <label for="first_name">Prénom :</label>
    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required>

    <label for="pseudo">Pseudo :</label>
    <input type="text" name="pseudo" id="pseudo" value="{{ old('pseudo') }}" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>

    <label for="password_confirmation">Confirmation du mot de passe :</label>
    <input type="password" name="password_confirmation" id="password_confirmation" required>

    @error('password')
        <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit">Valider</button>
</form>