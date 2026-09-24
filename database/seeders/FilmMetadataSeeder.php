<?php

namespace Database\Seeders;

use App\Models\Film;
use App\Services\TmdbService;
use Illuminate\Database\Seeder;
use Throwable;

class FilmMetadataSeeder extends Seeder
{
    public function run(TmdbService $tmdbService): void
    {
        Film::query()
            ->where(function ($query): void {
                $query->whereNull('date_sortie')->orWhereNull('note');
            })
            ->orderBy('titre')
            ->each(function (Film $film) use ($tmdbService): void {
                try {
                    $metadata = $tmdbService->getMovieDetailsByTitle($film->titre);
                } catch (Throwable) {
                    return;
                }

                if ($metadata === null) {
                    return;
                }

                unset($metadata['affiche_url'], $metadata['tmdb_id']);
                $film->update(array_filter($metadata, static fn ($value): bool => $value !== null));
            });
    }
}
