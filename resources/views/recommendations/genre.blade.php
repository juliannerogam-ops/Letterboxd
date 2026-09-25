@include('components.navbar')
@vite('resources/css/recommendations.css')

<div class="mood-test-shell">
    <section class="mood-panel mood-step mood-step--current" data-step="1">
        <div class="mood-panel-header">
            <span class="mood-current-step">1/1</span>
        </div>

        <h1>Comment te sens-tu aujourd'hui&nbsp;?</h1>
        <span class="mood-subtitle">Choisis l’humeur qui te ressemble le plus.</span>
        <span class="mood-hover-status {{ $selectedMood ? 'is-active' : '' }}" id="mood-hover-status" aria-live="polite">
            @if ($selectedMood)
                <span class="mood-status-label">Ton mood</span>
                <strong>{{ $selectedMood }}</strong>
            @else
                Passe sur une carte pour découvrir ton mood.
            @endif
        </span>

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
                    @class(['mood-option', 'is-selected' => $selectedMood === $label])
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

        <div class="mood-palette-strip" aria-hidden="true">
            <span class="palette-rose"></span>
            <span class="palette-sky"></span>
            <span class="palette-amber"></span>
            <span class="palette-mint"></span>
            <span class="palette-lavender"></span>
            <span class="palette-gray"></span>
        </div>

        <div class="genre-options" id="genre-options" aria-live="polite"></div>
        <p class="genre-selection-status" id="genre-selection-status" aria-live="polite"></p>

        <div class="mood-intensity" aria-labelledby="mood-intensity-title">
            <div class="mood-intensity-heading">
                <h3 id="mood-intensity-title">À quel point tu ressens ce mood ?</h3>
                <strong id="mood-intensity-value">5 / 10</strong>
            </div>
            <div class="mood-levels" role="group" aria-label="Intensité du mood de 1 à 10">
                @for ($level = 1; $level <= 10; $level++)
                    <button type="button" class="mood-level {{ $level === 5 ? 'is-selected' : '' }}" data-level="{{ $level }}" aria-label="Intensité {{ $level }} sur 10" aria-pressed="{{ $level === 5 ? 'true' : 'false' }}">
                        <span>{{ $level }}</span>
                    </button>
                @endfor
            </div>
        </div>

        <div class="mood-panel-footer">
            <button type="button" class="mood-back">← Retour</button>
            <button type="submit" class="mood-next" form="mood-form" id="confirmMoodStep">Confirmer</button>
        </div>
    </section>
</div>

<form id="mood-form" method="POST" action="{{ route('recommendations.genre.store') }}" style="display:none;">
    @csrf
    <input type="hidden" name="genre" id="selected-genre" value="">
    <input type="hidden" name="mood" id="selected-mood" value="{{ $selectedMood }}">
    <input type="hidden" name="intensity" id="mood-intensity" value="5">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const moodMap = @json($moods);
        const moodButtons = document.querySelectorAll('.mood-option');
        const steps = document.querySelectorAll('.mood-step');
        const genreOptions = document.getElementById('genre-options');
        const selectedGenreInput = document.getElementById('selected-genre');
        const selectedMoodInput = document.getElementById('selected-mood');
        const intensityInput = document.getElementById('mood-intensity');
        const intensityValue = document.getElementById('mood-intensity-value');
        const moodHoverStatus = document.getElementById('mood-hover-status');
        const moodForm = document.getElementById('mood-form');
        const nextButton = document.getElementById('nextMoodStep');
        const confirmButton = document.getElementById('confirmMoodStep');
        const backButtons = document.querySelectorAll('.mood-back');
        const dashboardUrl = @json(route('dashboard'));

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
            selectedGenreInput.value = [...selectedGenres][0] || '';
            updateGenreStatus(selectedGenres);

            allGenres.forEach((genre) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'genre-chip' + (selectedGenres.has(genre) ? ' is-selected' : '');
                button.dataset.genre = genre;
                button.setAttribute('aria-pressed', String(selectedGenres.has(genre)));
                button.innerHTML = '<span class="check" aria-hidden="true"></span><span>' + (genreLabels[genre] || genre) + '</span>';

                button.addEventListener('click', function () {
                    const activeBefore = [...document.querySelectorAll('.genre-chip.is-selected')].map((chip) => chip.dataset.genre);
                    if (!button.classList.contains('is-selected') && activeBefore.length >= 3) {
                        return;
                    }

                    button.classList.toggle('is-selected');
                    button.setAttribute('aria-pressed', String(button.classList.contains('is-selected')));

                    const active = [...document.querySelectorAll('.genre-chip.is-selected')].map((chip) => chip.dataset.genre);
                    selectedGenreInput.value = active[0] || '';
                    updateGenreStatus(new Set(active));
                });

                genreOptions.appendChild(button);
            });
        }

        function updateGenreStatus(selectedGenres) {
            const count = selectedGenres.size;
            document.getElementById('genre-selection-status').textContent = count === 0
                ? 'Choisis jusqu’à 3 ambiances.'
                : `${count} ambiance${count > 1 ? 's' : ''} sélectionnée${count > 1 ? 's' : ''}`;
        }

        const initialMood = document.querySelector('.mood-option.is-selected') || moodButtons[0];
        initialMood.classList.add('is-selected');
        selectedMoodInput.value = initialMood.dataset.mood;
        renderGenres(initialMood.dataset.mood);

        function selectMood(button) {
            moodButtons.forEach((candidate) => candidate.classList.toggle('is-selected', candidate === button));
            selectedMoodInput.value = button.dataset.mood;
            moodHoverStatus.classList.add('is-active');
            moodHoverStatus.innerHTML = '<span class="mood-status-label">Ton mood</span><strong>' + button.dataset.mood + '</strong>';
            renderGenres(button.dataset.mood);
        }

        function previewMood(button) {
            moodButtons.forEach((candidate) => candidate.classList.toggle('is-preview', candidate === button));
        }

        function clearMoodPreview() {
            moodButtons.forEach((candidate) => candidate.classList.remove('is-preview'));
            const selectedMood = document.querySelector('.mood-option.is-selected');
            if (selectedMood) {
                moodHoverStatus.classList.add('is-active');
                moodHoverStatus.innerHTML = '<span class="mood-status-label">Ton mood</span><strong>' + selectedMood.dataset.mood + '</strong>';
            } else {
                moodHoverStatus.classList.remove('is-active');
                moodHoverStatus.textContent = 'Passe sur une carte pour découvrir ton mood.';
            }
        }

        function showStep(stepNumber) {
            steps.forEach((step) => {
                const isVisible = Number(step.dataset.step) === stepNumber;
                step.style.display = isVisible ? '' : 'none';
                step.setAttribute('aria-hidden', String(!isVisible));
            });
        }

        moodButtons.forEach((button) => {
            button.addEventListener('mouseenter', function () {
                previewMood(button);
            });

            button.addEventListener('mouseleave', clearMoodPreview);
            button.addEventListener('focus', function () {
                previewMood(button);
            });
            button.addEventListener('blur', clearMoodPreview);
            button.addEventListener('click', function () {
                selectMood(button);
            });
        });

        function setIntensity(level) {
            document.querySelectorAll('.mood-level').forEach((candidate) => {
                const candidateLevel = Number(candidate.dataset.level);
                const isSelected = candidateLevel === Number(level);
                candidate.classList.toggle('is-selected', isSelected);
                candidate.classList.toggle('is-filled', candidateLevel <= Number(level));
                candidate.setAttribute('aria-pressed', String(isSelected));
            });
            intensityInput.value = level;
            intensityValue.textContent = level + ' / 10';
        }

        document.querySelectorAll('.mood-level').forEach((levelButton) => {
            levelButton.addEventListener('click', function () {
                const level = levelButton.dataset.level;
                setIntensity(level);
            });
        });

        setIntensity(5);

        nextButton.addEventListener('click', function () {
            const selectedMoodButton = document.querySelector('.mood-option.is-selected');
            if (!selectedMoodButton) {
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
                const visibleStep = [...steps].find((step) => step.getAttribute('aria-hidden') !== 'true');

                if (visibleStep?.dataset.step === '1') {
                    window.location.href = dashboardUrl;
                    return;
                }

                showStep(1);
            });
        });

        showStep(1);
    });
</script>
