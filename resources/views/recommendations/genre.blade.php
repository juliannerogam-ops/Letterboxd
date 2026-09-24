@include('components.navbar')
@vite('resources/css/recommendations.css')

<div class="mood-test-shell">
    <section class="mood-panel mood-step mood-step--current" data-step="1">
        <div class="mood-panel-header">
            <span class="mood-current-step">1/1</span>
        </div>

        <h1>Comment est ton mood aujourd'hui ?</h1>
        <span class="mood-subtitle">Choisis ton humeur qui te correspond le plus !</span>

        <div class="mood-grid" aria-label="Sélection du mood">
            @foreach ($moods as $label => $genres)
                @php
                    $tone = match ($label) {
                        'Heureuse' => 'pink',
                        'Stressée' => 'yellow',
                        'Triste' => 'blue',
                        'Calme' => 'green',
                        'Anxieuse' => 'purple',
                        'Énervée' => 'peach',
                        'Fatiguée' => 'mint',
                        'Neutre' => 'gray',
                        'Ne sais pas trop' => 'pink',
                        default => 'pink',
                    };

                    $imageFile = match ($label) {
                        'Heureuse' => 'happy.png',
                        'Stressée' => 'stress.png',
                        'Triste' => 'sad.png',
                        'Calme' => 'peaceful.png',
                        'Anxieuse' => 'anxiety.png',
                        'Énervée' => 'anger.png',
                        'Fatiguée' => 'tired.png',
                        'Neutre' => 'normal.png',
                        'Ne sais pas trop' => 'numbness.png',
                        default => 'happy.png',
                    };
                @endphp

                <button
                    type="button"
                    class="mood-option"
                    data-mood="{{ $label }}"
                    data-genres='@json($genres)'
                    data-tone="{{ $tone }}"
                    aria-label="Choisir le mood {{ $label }}"
                >
                    <span class="mood-icon" aria-hidden="true">
                        <img src="{{ asset('images/' . $imageFile) }}" alt="{{ $label }}" class="mood-card-image">
                    </span>
                    <span class="mood-label">{{ $label }}</span>
                </button>
            @endforeach
        </div>

        <div class="mood-footer">
            <button type="button" class="mood-back">← Retour</button>
            <button type="button" class="mood-next" id="nextMoodStep">Suivant →</button>
        </div>
    </section>

    <section class="mood-panel mood-step mood-step--next" id="mood-genre-panel" data-step="2" aria-hidden="true">
        <div class="mood-panel-header">
            <span class="mood-current-step">2/2</span>
        </div>

        <h2>Tu préfères quel type d'ambiance ?</h2>
        <span class="mood-subtitle">Choisis jusqu'à 3 ambiances qui te font envie.</span>

        <div class="genre-options" id="genre-options" aria-live="polite"></div>

        <div class="mood-panel-footer">
            <button type="button" class="mood-back">← Retour</button>
            <button type="submit" class="mood-next" form="mood-form" id="confirmMoodStep">Confirmer</button>
        </div>
    </section>
</div>

<form id="mood-form" method="POST" action="{{ route('recommendations.genre.store') }}" style="display:none;">
    @csrf
    <input type="hidden" name="genre" id="selected-genre" value="">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const moodMap = @json($moods);
        const moodButtons = document.querySelectorAll('.mood-option');
        const steps = document.querySelectorAll('.mood-step');
        const genreOptions = document.getElementById('genre-options');
        const selectedGenreInput = document.getElementById('selected-genre');
        const moodForm = document.getElementById('mood-form');
        const nextButton = document.getElementById('nextMoodStep');
        const confirmButton = document.getElementById('confirmMoodStep');
        const backButtons = document.querySelectorAll('.mood-back');

        const genreLabels = {
            Romantique: 'Romantique',
            Suspense: 'Suspense',
            Drame: 'Drame',
            'Comédie': 'Comédie',
            Action: 'Action',
            Fantastique: 'Fantastique',
            Aventure: 'Aventure',
            Animation: 'Animation',
            Documentaire: 'Documentaire',
            Émotion: 'Émotion',
            Horreur: 'Horreur',
            Thriller: 'Thriller',
            'Science-fiction': 'Science-fiction',
            Biopic: 'Biopic',
        };

        const allGenres = [
            'Romantique',
            'Suspense',
            'Drame',
            'Comédie',
            'Action',
            'Fantastique',
            'Aventure',
            'Émotion',
            'Horreur',
            'Thriller',
            'Documentaire',
            'Animation',
            'Biopic',
            'Science-fiction',
        ];

        function renderGenres(moodName) {
            const selectedGenres = new Set(moodMap[moodName] ?? []);
            genreOptions.innerHTML = '';

            allGenres.forEach((genre) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'genre-chip' + (selectedGenres.has(genre) ? ' is-selected' : '');
                button.dataset.genre = genre;
                button.innerHTML = '<span class="check" aria-hidden="true"></span><span>' + (genreLabels[genre] || genre) + '</span>';

                button.addEventListener('click', function () {
                    const activeBefore = [...document.querySelectorAll('.genre-chip.is-selected')].map((chip) => chip.dataset.genre);
                    if (!button.classList.contains('is-selected') && activeBefore.length >= 3) {
                        return;
                    }

                    button.classList.toggle('is-selected');

                    const active = [...document.querySelectorAll('.genre-chip.is-selected')].map((chip) => chip.dataset.genre);
                    selectedGenreInput.value = active[0] || '';
                });

                genreOptions.appendChild(button);
            });
        }

        const firstMood = moodButtons[0];
        renderGenres(firstMood.dataset.mood);

        function showStep(stepNumber) {
            steps.forEach((step) => {
                const isVisible = Number(step.dataset.step) === stepNumber;
                step.style.display = isVisible ? '' : 'none';
                step.setAttribute('aria-hidden', String(!isVisible));
            });
        }

        moodButtons.forEach((button) => {
            button.addEventListener('click', function () {
                moodButtons.forEach((candidate) => candidate.classList.toggle('is-selected', candidate === button));
                renderGenres(button.dataset.mood);
            });
        });

        nextButton.addEventListener('click', function () {
            const selectedMood = document.querySelector('.mood-option.is-selected');
            if (!selectedMood) {
                return;
            }

            showStep(2);
        });

        confirmButton.addEventListener('click', function () {
            const active = [...document.querySelectorAll('.genre-chip.is-selected')].map((chip) => chip.dataset.genre);
            if (active.length === 0) {
                return;
            }

            selectedGenreInput.value = active[0];
            moodForm.submit();
        });

        backButtons.forEach((button) => {
            button.addEventListener('click', function () {
                showStep(1);
            });
        });

        showStep(1);
    });
</script>
