@vite('resources/css/navbar.css')

<nav class="sidebar-nav" aria-label="Navigation principale">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <span class="brand-mark" aria-hidden="true"></span>
        <span>Your Letterboxd</span>
    </a>

    @auth
        <div class="sidebar-links">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">⌂</span>
                Accueil
            </a>
            <a href="{{ route('recommendations.genre.create') }}" class="sidebar-link {{ request()->routeIs('recommendations.*') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">◉</span>
                Découvrir
            </a>
            <a href="{{ route('listes') }}" class="sidebar-link {{ request()->routeIs('listes*') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">☷</span>
                Mes listes
            </a>
            @if (Auth::user()->is_admin)
                <a href="{{ route('admin.index') }}" class="sidebar-link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon" aria-hidden="true">◎</span>
                    Admin
                </a>
            @endif
            <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile*') ? 'is-active' : '' }}">
                <span class="sidebar-icon" aria-hidden="true">♙</span>
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
                <button type="submit" class="sidebar-logout" aria-label="Déconnexion">↪</button>
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
