@include('components.navbar')

<main>
    <a href="{{ route('admin.index') }}">Retour à l'administration</a>
    <h1>Liste des utilisateurs</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Pseudo</th>
                <th>E-mail</th>
                <th>Rôle</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }} {{ $user->first_name }}</td>
                    <td>{{ $user->pseudo }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_admin ? 'Admin' : 'Utilisateur' }}</td>
                    <td>
                        @if (! $user->is_admin)
                            <form method="POST" action="{{ route('admin.users.promote', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit">Nommer admin</button>
                            </form>
                        @else
                            Déjà admin
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>