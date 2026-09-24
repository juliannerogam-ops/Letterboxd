const releaseFilmsData = document.getElementById('release-films-data');

if (releaseFilmsData) {
    const releaseFilms = JSON.parse(releaseFilmsData.textContent);
    const calendarDays = document.getElementById('calendar-days');
    const calendarTitle = document.getElementById('release-calendar-title');
    const selectedFilms = document.getElementById('selected-release-films');
    const calendar = document.querySelector('.release-calendar');
    const csrfToken = calendar.dataset.csrfToken;
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

                if (film.inWatchlist) {
                    const status = document.createElement('span');
                    status.textContent = ' (Déjà dans votre watchlist)';
                    item.append(status);
                } else {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = film.watchlistUrl;

                    const token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = csrfToken;

                    const button = document.createElement('button');
                    button.type = 'submit';
                    button.textContent = 'Ajouter à ma watchlist';

                    form.append(token, button);
                    item.append(form);
                }

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
        calendarDays.replaceChildren();

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
}