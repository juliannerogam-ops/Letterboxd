<?php

namespace App\Console\Commands;

use App\Models\Film;
use App\Services\TmdbService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Throwable;

#[Signature('tmdb:sync-posters {--all : Rechercher aussi les films qui ont deja une affiche}')]
#[Description('Associe les affiches TMDB aux films locaux par leur titre')]
class SyncFilmPosters extends Command
{
    public function handle(TmdbService $tmdbService): int
    {
        $films = Film::query()
            ->when(! $this->option('all'), fn ($query) => $query->whereNull('affiche_url'))
            ->orderBy('titre')
            ->get();
        $matched = 0;
        $notFound = 0;
        $failed = 0;

        foreach ($films as $film) {
            try {
                $metadata = retry(
                    3,
                    fn (): ?array => $tmdbService->getMovieDetailsByTitle($film->titre),
                    3000,
                    fn (Throwable $exception): bool => $exception instanceof RequestException
                        && $exception->response?->status() === 429,
                );
            } catch (Throwable $exception) {
                $failed++;
                $this->warn("Recherche impossible pour {$film->titre} : {$exception->getMessage()}");

                continue;
            }

            $poster = $metadata['affiche_url'] ?? null;

            if (! is_string($poster) || ! str_starts_with($poster, 'https://media.themoviedb.org/t/p/')) {
                $notFound++;
                $this->line("Aucune affiche TMDB : {$film->titre}");

                continue;
            }

            $film->update(['affiche_url' => $poster]);
            $matched++;
            $this->info("Affiche associee : {$film->titre}");
            usleep(500000);
        }

        $this->newLine();
        $this->info("Synchronisation terminee : {$matched} associee(s), {$notFound} introuvable(s), {$failed} erreur(s).");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
