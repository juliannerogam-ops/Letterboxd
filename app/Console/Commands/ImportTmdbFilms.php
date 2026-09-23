<?php

namespace App\Console\Commands;

use App\Models\Film;
use App\Services\TmdbService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use RuntimeException;
use Throwable;

class ImportTmdbFilms extends Command
{
    protected $signature = 'tmdb:import
        {--pages=1 : Nombre de pages a importer}
        {--start-page=1 : Premiere page TMDb a importer}
        {--year= : Annee de sortie a importer}';

    protected $description = 'Importe des films TMDb dans la base locale';

    public function handle(TmdbService $tmdbService): int
    {
        $pages = (int) $this->option('pages');
        $startPage = (int) $this->option('start-page');
        $year = $this->option('year');

        if ($pages < 1 || $startPage < 1) {
            $this->error('Les options --pages et --start-page doivent etre superieures a zero.');

            return self::FAILURE;
        }

        if ($year !== null && (! is_numeric($year) || (int) $year < 1870 || (int) $year > now()->year + 5)) {
            $this->error('L\'option --year doit contenir une annee valide.');

            return self::FAILURE;
        }

        try {
            $firstPage = $tmdbService->discoverMovies($startPage, $year !== null ? (int) $year : null);
        } catch (RequestException $exception) {
            $this->error('TMDb a refuse la requete : ' . $exception->getMessage());

            return self::FAILURE;
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $totalPages = min((int) ($firstPage['total_pages'] ?? $startPage), 500);
        $lastPage = min($startPage + $pages - 1, $totalPages);
        $imported = 0;
        $failed = 0;

        for ($page = $startPage; $page <= $lastPage; $page++) {
            $pageData = $page === $startPage
                ? $firstPage
                : $tmdbService->discoverMovies($page, $year !== null ? (int) $year : null);

            foreach ($pageData['results'] ?? [] as $summary) {
                try {
                    $details = $tmdbService->getMovieDetails((int) $summary['id']);
                    Film::updateOrCreate(['tmdb_id' => $details['tmdb_id']], $details);
                    $imported++;
                    $this->line("[{$page}/{$lastPage}] {$details['titre']}");
                } catch (Throwable $exception) {
                    $failed++;
                    $this->warn("Film {$summary['id']} ignore : {$exception->getMessage()}");
                }

                usleep(250000);
            }
        }

        $this->info("Import termine : {$imported} film(s), {$failed} erreur(s).");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
