@include('components.navbar')

<h1>Se connecter</h1>

<form action="{{ route('login') }}" method="POST">
    @csrf
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
    @error('email')
        <div class="error">{{ $message }}</div>
    @enderror
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>
    @error('password')
        <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit">Se connecter</button>
</form>

<div class="mt-4 text-center">
    <p>Vous n'avez pas de compte ?</p>
    <a href="{{ route('register') }}" class="btn btn-secondary">
        Créer un compte
    </a>
</div>

    