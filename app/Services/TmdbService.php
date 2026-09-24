<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;

class TmdbService
{
    public function getMovieDetails(int $tmdbId): array
    {
        $html = $this->client()
            ->get("/movie/{$tmdbId}", ['language' => 'fr-FR'])
            ->throw()
            ->body();

        return $this->parseMoviePage($html, $tmdbId);
    }

    public function discoverMovies(int $page, ?int $year = null): array
    {
        $query = [
            'language' => 'fr-FR',
            'page' => $page,
            'sort_by' => 'popularity.desc',
        ];

        if ($year !== null) {
            $query['primary_release_year'] = $year;
        }

        $html = $this->client()
            ->get('/discover/movie', $query)
            ->throw()
            ->body();

        $xpath = $this->xpath($html);
        $ids = [];

        foreach ($xpath->query('//a[contains(@href, "/movie/")]') as $link) {
            if (! $link instanceof DOMElement || ! preg_match('~/movie/(\d+)~', $link->getAttribute('href'), $matches)) {
                continue;
            }

            $ids[(int) $matches[1]] = ['id' => (int) $matches[1]];
        }

        return [
            'total_pages' => 500,
            'results' => array_values($ids),
        ];
    }

    private function parseMoviePage(string $html, int $tmdbId): array
    {
        $xpath = $this->xpath($html);
        $title = $this->text($xpath, '//section[contains(@class, "header")]//div[contains(@class, "title")]//h2/a');
        $releaseText = $this->text($xpath, '//span[contains(concat(" ", normalize-space(@class), " "), " release ")]');
        $releaseDate = null;

        if (preg_match('~(\d{1,2}/\d{1,2}/\d{4})~', $releaseText, $matches)) {
            $date = \DateTime::createFromFormat('d/m/Y', $matches[1]);
            $releaseDate = $date?->format('Y-m-d');
        }

        $runtime = $this->text($xpath, '//section[contains(@class, "header")]//span[contains(@class, "runtime")]');
        $duration = 0;
        if (preg_match('~(?:(\d+)h)?\s*(?:(\d+)m)?~', $runtime, $matches)) {
            $duration = ((int) ($matches[1] ?? 0) * 60) + (int) ($matches[2] ?? 0);
        }

        $director = $this->text($xpath, '//ol[contains(@class, "people")]//li[.//p[contains(@class, "character") and normalize-space()="Director"]]//p[1]/a');
        $genres = [];
        foreach ($xpath->query('//section[contains(@class, "header")]//span[contains(@class, "genres")]//a') as $genre) {
            $genres[] = trim($genre->textContent);
        }

        $poster = $xpath->evaluate('string((//img[contains(@src, "/t/p/")])[1]/@src)');
        if (str_starts_with($poster, '/')) {
            $poster = 'https://image.tmdb.org' . $poster;
        }

        $description = $this->text($xpath, '//div[contains(@class, "overview")]//p');

        return [
            'tmdb_id' => $tmdbId,
            'titre' => $title ?: 'Titre inconnu',
            'genre' => implode(', ', $genres),
            'description' => $description ?: null,
            'annee_sortie' => filled($releaseDate)
                ? (int) substr($releaseDate, 0, 4)
                : null,
            'date_sortie' => $releaseDate,
            'duree_minutes' => $duration ?: null,
            'affiche_url' => $poster ?: null,
            'statut_sortie' => filled($releaseDate) && $releaseDate <= now()->toDateString()
                ? 'Film sorti'
                : 'Film a venir',
            'realisateur' => $director ?: null,
            'est_sorti' => filled($releaseDate) && $releaseDate <= now()->toDateString(),
            'derniere_synchronisation' => now(),
        ];
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(config('services.tmdb.web_base_url', 'https://www.themoviedb.org'))
            ->acceptJson()
            ->withHeaders(['User-Agent' => 'Letterboxd local importer'])
            ->timeout(10);
    }

    private function xpath(string $html): DOMXPath
    {
        $document = new DOMDocument;
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        return new DOMXPath($document);
    }

    private function text(DOMXPath $xpath, string $query): string
    {
        return trim((string) $xpath->evaluate("string(({$query})[1])"));
    }
}
