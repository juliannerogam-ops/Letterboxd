@include('components.navbar')

<h1>Tableau de bord</h1>


<form method="GET" action="{{ route('film.list') }}">
    <input
        type="search"
        name="q"
        value="{{ request('q') }}"
        placeholder="Rechercher un film..."
    >
    <button type="submit">Rechercher</button>
</form>

<section class="release-calendar" aria-labelledby="release-calendar-title">
    <div class="release-calendar-header">
        <button type="button" id="previous-month" aria-label="Mois précédent">&larr;</button>
        <h2 id="release-calendar-title"></h2>
        <button type="button" id="next-month" aria-label="Mois suivant">&rarr;</button>
    </div>

    <div class="calendar-weekdays" aria-hidden="true">
        <span>Lun</span>
        <span>Mar</span>
        <span>Mer</span>
        <span>Jeu</span>
        <span>Ven</span>
        <span>Sam</span>
        <span>Dim</span>
    </div>

    <div id="calendar-days" class="calendar-days"></div>

    <div id="selected-release-films" class="selected-release-films" aria-live="polite">
        <p>Sélectionnez un jour pour voir les sorties.</p>
    </div>
</section>

<style>
    .release-calendar {
        max-width: 720px;
        margin: 32px 0;
    }

    .release-calendar-header,
    .calendar-weekdays,
    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 6px;
    }

    .release-calendar-header {
        grid-template-columns: 44px 1fr 44px;
        align-items: center;
        margin-bottom: 12px;
    }

    .release-calendar-header h2 {
        margin: 0;
        text-align: center;
        text-transform: capitalize;
    }

    .release-calendar-header button,
    .calendar-day {
        min-height: 44px;
        border: 1px solid #d5d5d5;
        border-radius: 6px;
        background: #fff;
        cursor: pointer;
    }

    .calendar-weekdays {
        margin-bottom: 6px;
        color: #666;
        font-size: 0.85rem;
        text-align: center;
    }

    .calendar-day {
        position: relative;
    }

    .calendar-day.has-releases {
        border-color: #4d7cfe;
        font-weight: 700;
    }

    .calendar-day.is-selected {
        background: #dbe5ff;
    }

    .release-count {
        display: block;
        color: #4d7cfe;
        font-size: 0.7rem;
    }

    .selected-release-films {
        margin-top: 18px;
    }
</style>

<script>
    const releaseFilms = @json($releaseFilms);
    const calendarDays = document.getElementById('calendar-days');
    const calendarTitle = document.getElementById('release-calendar-title');
    const selectedFilms = document.getElementById('selected-release-films');
    let displayedMonth = new Date();
    let selectedDate = null;

    const filmsByDate = releaseFilms.reduce((films, film) => {
        films[film.date] ??= [];
        films[film.date].push(film);

        return films;
    }, {});

    const dateKey = (year, month, day) => [
        year,
        String(month + 1).padStart(2, '0'),
        String(day).padStart(2, '0'),
    ].join('-');

    function showFilmsForDate(date) {
        selectedDate = date;
        const films = filmsByDate[date] ?? [];
        selectedFilms.replaceChildren();

        if (films.length === 0) {
            const message = document.createElement('p');
            message.textContent = 'Aucune sortie de film ce jour-là.';
            selectedFilms.append(message);
        } else {
            const heading = document.createElement('h3');
            heading.textContent = `Sorties du ${date.split('-').reverse().join('/')}`;
            const list = document.createElement('ul');

            films.forEach((film) => {
                const item = document.createElement('li');
                const link = document.createElement('a');
                link.href = film.url;
                link.textContent = film.title;
                item.append(link);
                list.append(item);
            });

            selectedFilms.append(heading, list);
        }

        renderCalendar();
    }

    function renderCalendar() {
        const year = displayedMonth.getFullYear();
        const month = displayedMonth.getMonth();
        const firstDay = (new Date(year, month, 1).getDay() + 6) % 7;
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        calendarTitle.textContent = new Intl.DateTimeFormat('fr-FR', {
            month: 'long',
            year: 'numeric',
        }).format(displayedMonth);
        calendarDays.innerHTML = '';

        for (let index = 0; index < firstDay; index += 1) {
            calendarDays.append(document.createElement('span'));
        }

        for (let day = 1; day <= daysInMonth; day += 1) {
            const date = dateKey(year, month, day);
            const films = filmsByDate[date] ?? [];
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `calendar-day${films.length ? ' has-releases' : ''}${selectedDate === date ? ' is-selected' : ''}`;
            button.textContent = day;

            if (films.length) {
                const count = document.createElement('span');
                count.className = 'release-count';
                count.textContent = `${films.length} film${films.length > 1 ? 's' : ''}`;
                button.append(count);
            }

            button.addEventListener('click', () => showFilmsForDate(date));
            calendarDays.append(button);
        }
    }

    document.getElementById('previous-month').addEventListener('click', () => {
        displayedMonth.setMonth(displayedMonth.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        displayedMonth.setMonth(displayedMonth.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();
</script>

<section>
    <h2>Recommandations</h2>

    <p>Suggestions pour le genre : {{ $genre }}</p>

    <h3>Films de votre watchlist</h3>

    @if ($watchlistFilms->isEmpty())
        <p>Aucun film de votre watchlist ne correspond à ce genre.</p>
    @else
        <ul>
            @foreach ($watchlistFilms as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</section>

<section>
    <h3>On pense que ça pourrait vous plaire aussi</h3>

    @if ($recommendations->isEmpty())
        <p>Aucune autre recommandation disponible.</p>
    @else
        <ul>
            @foreach ($recommendations as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</section>

<section>
    <h2>Mon Top 5</h2>

    @if ($topFive && $topFive->films->isNotEmpty())
        <ol>
            @foreach ($topFive->films as $film)
                <li>
                    <a href="{{ route('film.show', ['id' => $film->id]) }}">
                        {{ $film->titre }}
                    </a>
                </li>
            @endforeach
        </ol>
    @else
        <p>Ton Top 5 est encore vide.</p>
    @endif

    @if ($topFive)
        <a href="{{ route('listes.show', $topFive) }}">
            Modifier mon Top 5
        </a>
    @endif
</section>
