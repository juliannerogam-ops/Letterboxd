@include('components.navbar')

<main>
    <h1>Administration</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <section>
        <h2>Utilisateurs</h2>
        <a href="{{ route('admin.users.index') }}">Liste des utilisateurs</a>
    </section>

    <section>
        <h2>Films</h2>
        <a href="{{ route('film.list') }}">Liste des films</a>
    </section>
</main>