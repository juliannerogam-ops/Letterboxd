@include('components.navbar')
@vite('resources/css/admin.css')

<main class="admin-users-page">
    <a class="admin-users-back" href="{{ route('admin.index') }}"><span aria-hidden="true">←</span> Administration</a>
    <header class="admin-users-header">
        <div>
            <span class="admin-card-kicker">Accès & permissions</span>
            <h1>Liste des utilisateurs</h1>
            <p>Gère les rôles et garde une vue claire sur les membres de la communauté.</p>
        </div>
        <strong class="admin-users-count">{{ $users->count() }} <span>{{ $users->count() > 1 ? 'membres' : 'membre' }}</span></strong>
    </header>

    @if (session('status'))
        <p class="admin-users-notice" role="status">{{ session('status') }}</p>
    @endif

    <section class="admin-users-table-wrap" aria-label="Utilisateurs enregistrés">
        <div class="admin-users-table-head">
            <span>Profil</span>
            <span>Contact</span>
            <span>Accès</span>
            <span>Action</span>
        </div>
        <div class="admin-users-list">
            @foreach ($users as $user)
                <article class="admin-user-row">
                    <div class="admin-user-profile">
                        <span class="admin-user-avatar">{{ strtoupper(substr($user->pseudo ?: $user->name, 0, 1)) }}</span>
                        <div>
                            <strong>{{ $user->name }} {{ $user->first_name }}</strong>
                            <span>@{{ $user->pseudo ?: 'membre' }}</span>
                        </div>
                    </div>
                    <span class="admin-user-email">{{ $user->email }}</span>
                    <span class="admin-user-role {{ $user->is_admin ? 'is-admin' : '' }}">{{ $user->is_admin ? 'Admin' : 'Utilisateur' }}</span>
                    <div class="admin-user-action">
                        @if (! $user->is_admin)
                            <form method="POST" action="{{ route('admin.users.promote', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit">Nommer admin <span aria-hidden="true">→</span></button>
                            </form>
                        @else
                            <span class="admin-user-confirmed">Accès complet</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</main>