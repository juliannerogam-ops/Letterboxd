<h1>S'inscire</h1>

<form action="{{ route('user.create') }}" method="POST">
    @csrf
    <label for="name">Nom :</label>
    <input type="text" name="name" id="name" value="{{ old('name') }}" required>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>

    <label for="confirmation_password">Confirmation du mot de passe :</label>
    <textarea name="confirmation_password" id="confirmation_password">{{ old('confirmation_password') }}</textarea>

    @error('confirmation_password')
        <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit">Valider</button>
</form>