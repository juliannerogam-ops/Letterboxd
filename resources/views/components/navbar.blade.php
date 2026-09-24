@vite('resources/css/navbar.css')

<nav class="sidebar-nav" aria-label="Navigation principale">
    <form method="POST" action="{{ route('recommendations.reset') }}" class="sidebar-brand-form">
        @csrf
        <button type="submit" class="sidebar-brand" aria-label="Revenir à l'accueil et réinitialiser le mood">
            <span class="brand-mark" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </span>
            <span>Your Letterboxd</span>
        </button>
    </form>

    @auth
        <div class="sidebar-links">
            <span class="sidebar-section-label">Explorer</span>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10.5 12 4l8 6.5"/><path d="M6 9.8V19h12V9.8"/><path d="M10 19v-5h4v5"/></svg>
                </span>
                Accueil
            </a>
            <a href="{{ route('recommendations.index') }}" class="sidebar-link {{ request()->routeIs('recommendations.index') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="6.5"/><circle cx="12" cy="12" r="2.2"/><path d="M12 1.8v2.7M12 19.5v2.7M1.8 12h2.7M19.5 12h2.7"/></svg>
                </span>
                Découvrir
            </a>
            <span class="sidebar-section-label sidebar-section-label--collection">Ta collection</span>
            <a href="{{ route('listes') }}" class="sidebar-link {{ request()->routeIs('listes*') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 7h11M8 12h11M8 17h11M4.2 7h.01M4.2 12h.01M4.2 17h.01"/></svg>
                </span>
                Mes listes
            </a>
            <a href="{{ route('recommendations.genre.create') }}" class="sidebar-link {{ request()->routeIs('recommendations.genre.*') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.5 14.8 8l5.9.9-4.3 4.2 1 5.9-5.4-2.8-5.4 2.8 1-5.9L3.3 9l5.9-.9L12 2.5Z"/></svg>
                </span>
                Test mood
            </a>
            @if (Auth::user()->is_admin)
                <a href="{{ route('admin.index') }}" class="sidebar-link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="6.5"/><path d="M12 8.5v3.2M12 15.5h.01"/></svg>
                    </span>
                    Admin
                </a>
            @endif
            <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile*') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.3"/><path d="M5 18.5c1.5-3 4.1-4.5 7-4.5s5.5 1.5 7 4.5"/></svg>
                </span>
                Profil
            </a>
        </div>

        <div class="sidebar-account">
            <div class="account-avatar" aria-hidden="true">{{ strtoupper(substr(Auth::user()->pseudo ?: Auth::user()->name, 0, 1)) }}</div>
            <div class="account-copy">
                <strong>Bonjour {{ Auth::user()->pseudo ?: Auth::user()->name }} !</strong>
                <span>Prêt pour un nouveau film ?</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-logout">Se déconnecter</button>
            </form>
        </div>
    @else
        <div class="sidebar-links">
            <a href="{{ route('login') }}" class="sidebar-link">
                <span class="sidebar-icon" aria-hidden="true">→</span>
                Connexion
            </a>
            <a href="{{ route('register') }}" class="sidebar-link">
                <span class="sidebar-icon" aria-hidden="true">+</span>
                S'inscrire
            </a>
        </div>
    @endauth
</nav>
