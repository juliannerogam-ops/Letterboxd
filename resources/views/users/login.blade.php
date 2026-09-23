<h1>Se connecter</h1>

<form action="{{ route('user.login') }}" method="POST">
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


    