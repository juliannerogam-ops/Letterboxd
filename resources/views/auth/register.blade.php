<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        :root {
            --brand-pink: #FDCBE8;
            --page-bg: #f4f4f4;
            --field-bg: #ececec;
            --field-border: #d7d7d7;
            --text: #171717;
            --muted: #666666;
            --primary: #bfe7f2;
            --primary-dark: #a9dce9;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background: var(--page-bg);
            color: var(--text);
            font-family: 'Trueno Regular', 'Trueno', 'Segoe UI', sans-serif;
            font-weight: 400;
        }

        body {
            display: flex;
            justify-content: center;
            padding: 0;
            background: #FDCBE8;
        }

        .auth-shell {
            width: 100%;
            min-height: 100vh;
            background: #f4f4f4;
        }

        .brand-bar {
            background: var(--brand-pink);
            padding: 56px 0 38px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 26px;
        }

        .brand-dot {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-block;
            box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.08);
        }

        .dot-orange { background: #f08b39; }
        .dot-green { background: #2db765; }
        .dot-blue { background: #2c8fe8; }

        .brand-name {
            margin: 0;
            font-size: clamp(2.1rem, 2.3vw, 2.9rem);
            font-weight: 400;
            letter-spacing: -0.08em;
            line-height: 1;
            color: #1f1f1f;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.08em;
        }

        .brand-name .your {
            font-weight: 400;
        }

        .auth-card {
            max-width: 820px;
            margin: 0 auto;
            padding: 68px 44px 42px;
        }

        .auth-card h1 {
            margin: 0 0 48px;
            font-size: clamp(3rem, 4.1vw, 4.7rem);
            font-weight: 400;
            letter-spacing: -0.08em;
            line-height: 0.95;
            color: #171717;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 32px;
            margin: 0;
        }

        .form-group {
            margin: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 12px;
            font-size: 1.05rem;
            color: var(--text);
            letter-spacing: -0.03em;
            font-weight: 400;
        }

        .field {
            width: 100%;
            height: 52px;
            border: 1px solid var(--field-border);
            background: var(--field-bg);
            border-radius: 10px;
            padding: 0 16px;
            font-size: 1rem;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field::placeholder {
            color: #7b7b7b;
        }

        .field:focus {
            border-color: rgba(24, 24, 24, 0.45);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.03);
        }

        .primary-btn {
            width: 100%;
            height: 60px;
            border: 0;
            border-radius: 10px;
            background: var(--primary);
            color: #1f1f1f;
            font-size: clamp(1.6rem, 1.8vw, 2.1rem);
            font-weight: 400;
            letter-spacing: -0.06em;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .primary-btn:hover {
            background: var(--primary-dark);
        }

        .primary-btn:active {
            transform: translateY(1px);
        }

        .error-text {
            margin-top: 8px;
            font-size: 0.8rem;
            color: #bf2f2f;
            font-weight: 600;
        }

        .switch-text {
            margin-top: 38px;
            text-align: center;
            font-size: 0.98rem;
            color: #2f2f2f;
        }

        .switch-text a {
            color: var(--text);
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        @media (max-width: 640px) {
            .auth-card {
                padding: 24px 20px 18px;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .field {
                height: 50px;
            }

            .primary-btn {
                height: 58px;
            }
        }
    </style>
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
            <h1>Bienvenue</h1>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" name="email" id="email" class="field" value="{{ old('email') }}" placeholder="nom@example.com" required>
                </div>

                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" class="field" value="{{ old('name') }}" placeholder="Ton nom" required>
                </div>

                <div class="form-group">
                    <label for="first_name">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="field" value="{{ old('first_name') }}" placeholder="Ton prénom" required>
                </div>

                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" class="field" value="{{ old('pseudo') }}" placeholder="Ton pseudo" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" class="field" placeholder="Mot de passe" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmation du mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="field" placeholder="Confirmation du mot de passe" required>
                </div>

                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror

                <button type="submit" class="primary-btn">Créer mon compte</button>
            </form>

            <p class="switch-text">
                Vous avez déjà un compte ?
                <a href="{{ route('login') }}">Connectez-vous</a>
            </p>
        </main>
    </div>
</body>
</html>