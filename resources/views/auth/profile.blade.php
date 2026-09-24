<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    @vite(['resources/css/profile.css', 'resources/js/app.js'])
</head>
<body>
    <div class="profile-shell">
        <header class="profile-header">
            <h1>Mon profil</h1>
            <a href="{{ route('dashboard') }}">Retour au Tableau de bord</a>
        </header>

        <main class="profile-card">
            <h2>Mes informations</h2>
            <p class="profile-intro">Modifiez les informations liées à votre compte.</p>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" class="field" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="field" value="{{ old('first_name', $user->first_name) }}" required>
                    @error('first_name') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" class="field" value="{{ old('pseudo', $user->pseudo) }}" required>
                    @error('pseudo') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" name="email" id="email" class="field" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="current_password">Votre mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" class="field" autocomplete="current-password">
                    <p class="password-help">Obligatoire uniquement si vous modifiez votre mot de passe.</p>
                    @error('current_password') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" class="field" autocomplete="new-password">
                    <p class="password-help">Laissez ce champ vide pour conserver votre mot de passe actuel.</p>
                    @error('password') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="field" autocomplete="new-password">
                </div>

                <button type="submit" class="primary-btn">Enregistrer les modifications</button>
            </form>
        </main>
    </div>
</body>
</html>

    