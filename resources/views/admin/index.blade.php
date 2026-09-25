@include('components.navbar')
@vite('resources/css/admin.css')

<main class="admin-page">
    <section class="admin-actions-panel">
            <div class="admin-section-heading">
                <div>
                    <span class="admin-card-kicker">Centre de contrôle</span>
                    <h2>Que veux-tu gérer&nbsp;?</h2>
                </div>
                <div class="admin-filter" role="group" aria-label="Filtrer les raccourcis">
                    <button type="button" class="is-selected" data-admin-filter="all">Tout</button>
                    <button type="button" data-admin-filter="people">Accès</button>
                    <button type="button" data-admin-filter="catalog">Films</button>
                </div>
            </div>

            <div class="admin-action-list">
                <a class="admin-action" data-admin-category="people" href="{{ route('admin.users.index') }}">
                    <span class="admin-action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c1.1-3.1 3-4.5 5.5-4.5s4.4 1.4 5.5 4.5"/><path d="M16 5.5a2.6 2.6 0 0 1 0 5M17 14.7c1.7.5 2.8 1.8 3.5 4.3"/></svg>
                    </span>
                    <span>
                        <h3>Utilisateurs</h3>
                        <p>Consulter les comptes et gérer les rôles.</p>
                    </span>
                    <span class="admin-action-arrow" aria-hidden="true">↗</span>
                </a>

                <a class="admin-action" data-admin-category="catalog" href="{{ route('film.list') }}">
                    <span class="admin-action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="m8 3 1.5 3h5L16 3M8 11h8M8 15h5"/></svg>
                    </span>
                    <span>
                        <h3>Films</h3>
                        <p>Explorer, vérifier et maintenir le catalogue.</p>
                    </span>
                    <span class="admin-action-arrow" aria-hidden="true">↗</span>
                </a>
            </div>
    </section>
</main>

<script>
    document.querySelectorAll('[data-admin-filter]').forEach((filterButton) => {
        filterButton.addEventListener('click', () => {
            const filter = filterButton.dataset.adminFilter;

            document.querySelectorAll('[data-admin-filter]').forEach((button) => {
                button.classList.toggle('is-selected', button === filterButton);
            });

            document.querySelectorAll('[data-admin-category]').forEach((action) => {
                action.hidden = filter !== 'all' && action.dataset.adminCategory !== filter;
            });
        });
    });
</script>