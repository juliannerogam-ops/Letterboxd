<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $films = DB::table('film')
            ->select(['id', 'titre', 'tmdb_id'])
            ->orderBy('tmdb_id')
            ->get()
            ->groupBy(fn (object $film): string => $this->normalizeTitle($film->titre))
            ->filter(fn ($group): bool => $group->count() > 1);

        foreach ($films as $duplicates) {
            $canonical = $duplicates->first();

            foreach ($duplicates->skip(1) as $duplicate) {
                $listEntries = DB::table('film_liste')
                    ->where('film_id', $duplicate->id)
                    ->get(['liste_id', 'position']);

                foreach ($listEntries as $entry) {
                    DB::table('film_liste')->insertOrIgnore([
                        'liste_id' => $entry->liste_id,
                        'film_id' => $canonical->id,
                        'position' => $entry->position,
                    ]);
                }

                DB::table('film')->where('id', $duplicate->id)->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}

    private function normalizeTitle(string $title): string
    {
        return (string) Str::of($title)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', ' ')
            ->squish();
    }
};
