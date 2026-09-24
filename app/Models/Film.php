<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Film extends Model
{
    use HasUuids;

    protected $table = 'film';

    public $timestamps = false;

    protected $fillable = [
        'tmdb_id',
        'titre',
        'genre',
        'description',
        'annee_sortie',
        'date_sortie',
        'duree_minutes',
        'affiche_url',
        'note',
        'avis_count',
        'statut_sortie',
        'derniere_synchronisation',
        'realisateur_id',
        'realisateur',
        'est_sorti',
    ];

    protected function casts(): array
    {
        return [
            'date_sortie' => 'date',
            'note' => 'decimal:1',
            'avis_count' => 'integer',
            'derniere_synchronisation' => 'datetime',
            'est_sorti' => 'boolean',
        ];
    }

    public function hasVerifiedPoster(): bool
    {
        $poster = $this->affiche_url;

        if (! is_string($poster) || $poster === '') {
            return false;
        }

        $url = parse_url($poster);

        if (($url['scheme'] ?? null) === 'https') {
            return in_array($url['host'] ?? null, ['image.tmdb.org', 'media.themoviedb.org'], true)
                && str_starts_with($url['path'] ?? '', '/t/p/');
        }

        return ($url['path'] ?? '') !== ''
            && str_starts_with($url['path'], '/images/')
            && preg_match('/\.(?:png|jpe?g|webp)$/i', $url['path']) === 1;
    }

    public function listes(): BelongsToMany
    {
        return $this->belongsToMany(Liste::class, 'film_liste');
    }
}
