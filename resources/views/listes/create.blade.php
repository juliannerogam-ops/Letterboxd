@include('components.navbar')
@vite('resources/css/lists.css')

<main class="list-page">
    <header class="list-page-header">
        <div>
            <span class="list-eyebrow">Nouvelle collection</span>
            <h1>Créer une liste</h1>
            <p>Donne un nom et une description à ta prochaine sélection de films.</p>
        </div>
    </header>

    <section class="list-create-panel" aria-labelledby="create-list-title">
        @if ($errors->any())
            <div class="list-import-errors" role="alert">
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('listes.store') }}" class="list-create-form">
            @csrf

            <label class="list-field" for="titre">
                <span>Nom de la liste</span>
                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" placeholder="Ex. Films à voir un soir de pluie" required>
            </label>

            <label class="list-field" for="description">
                <span>Description</span>
                <textarea id="description" name="description" placeholder="Quelques mots sur cette liste...">{{ old('description') }}</textarea>
            </label>

            <button class="list-submit" type="submit">Créer la liste <span aria-hidden="true">+</span></button>
        </form>
    </section>
</main>
