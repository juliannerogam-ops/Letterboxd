<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Liste extends Model
{
    use HasUuids;

    public const TYPE_WATCHLIST = 'watchlist';

    public const TYPE_FAVORITES = 'favorites';

    public const TYPE_TOP_FIVE = 'top_5';

    protected $fillable = [
        'titre',
        'description',
        'type',
    ];

    public static function createDefaultsFor(User $user): void
    {
        foreach ([
            self::TYPE_WATCHLIST => 'Watchlist',
            self::TYPE_FAVORITES => 'Mes favoris',
            self::TYPE_TOP_FIVE => 'Mon top 5',
        ] as $type => $title) {
            $user->listes()->firstOrCreate(
                ['type' => $type],
                ['titre' => $title],
            );
        }
    }

    public function isTopFive(): bool
    {
        return $this->type === self::TYPE_TOP_FIVE;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class, 'film_liste')
            ->withPivot('position')
            ->orderBy('film_liste.position');
    }
}
