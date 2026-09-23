<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

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
            'derniere_synchronisation' => 'datetime',
            'est_sorti' => 'boolean',
        ];
    }
}