<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $posters = [
            '1917' => '1917.png',
            'Forrest Gump' => 'forrest-gump.png',
            'Intouchables' => 'intouchables.png',
            'La La Land' => 'lalaland.png',
            'Mamma Mia!' => 'mamma-mia.png',
            'Manchester by the Sea' => 'manchester-by-the-sea.png',
            'Paddington 2' => 'paddington-2.png',
            'A Quiet Place' => 'quiet.place.png',
            'Ratatouille' => 'ratatouille.png',
            'The Social Network' => 'social-network.png',
            'The Green Mile' => 'the-green-mile.png',
            'Titanic' => 'titanic.png',
            'Le Tombeau des Lucioles' => 'tombeau-des-lucioles.png',
            'Uncut Gems' => 'uncut-gems.png',
            'Whiplash' => 'whiplash.png',
        ];

        foreach ($posters as $title => $filename) {
            DB::table('film')->where('titre', $title)->update([
                'affiche_url' => '/images/'.$filename,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('film')->whereIn('titre', [
            '1917',
            'Forrest Gump',
            'Intouchables',
            'La La Land',
            'Mamma Mia!',
            'Manchester by the Sea',
            'Paddington 2',
            'A Quiet Place',
            'Ratatouille',
            'The Social Network',
            'The Green Mile',
            'Titanic',
            'Le Tombeau des Lucioles',
            'Uncut Gems',
            'Whiplash',
        ])->update(['affiche_url' => null]);
    }
};
