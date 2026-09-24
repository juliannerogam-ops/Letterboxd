<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('film')
            ->where('affiche_url', 'like', '/images/%')
            ->orWhere('affiche_url', 'like', 'images/%')
            ->update(['affiche_url' => null]);
    }

    public function down(): void
    {
        // Removed fake posters are intentionally not restored.
    }
};
