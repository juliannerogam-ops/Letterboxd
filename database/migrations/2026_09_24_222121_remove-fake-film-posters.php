<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('film')
            ->where('affiche_url', 'like', '%unsplash.com%')
            ->update(['affiche_url' => null]);
    }

    public function down(): void
    {
        // Fake poster URLs are intentionally not restored.
    }
};
