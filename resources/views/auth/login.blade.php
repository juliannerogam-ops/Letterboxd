<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <header class="brand-bar">
            <div class="brand-mark" aria-hidden="true">
                <span class="brand-dot dot-orange"></span>
                <span class="brand-dot dot-green"></span>
                <span class="brand-dot dot-blue"></span>
            </div>
            <h2 class="brand-name"><span class="your">Your</span> Letterboxd</h2>
        </header>

        <main class="auth-card">
            <h1>Connexion</h1>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="field"
                        value="{{ old('email') }}"
                        placeholder="nom@exemple.com"
                        required
                    >
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="field"
                        placeholder="Mot de passe"
                        required
                    >
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">Se connecter</button>
            </form>

            <p class="switch-text">
                Vous n'avez pas de compte ?
                <a href="{{ route('register') }}">Inscrivez-vous</a>
            </p>
        </main>
    </div>
</body>
</html>

    