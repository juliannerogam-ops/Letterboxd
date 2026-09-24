@include('components.navbar')

<main>
    <h1>Administration</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <section>
        <h2>Utilisateurs</h2>

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
    </section>

    <section>
        <h2>Films</h2>

        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Genre</th>
                    <th>Année</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($films as $film)
                    <tr>
                        <td><a href="{{ route('film.show', ['id' => $film->id]) }}">{{ $film->titre }}</a></td>
                        <td>{{ $film->genre }}</td>
                        <td>{{ $film->annee_sortie }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</main>