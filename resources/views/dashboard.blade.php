@include('components.navbar')

<a href="{{ route('listes.create') }}">Créer une liste</a>

@include('components.listes', ['lists' => $lists])
