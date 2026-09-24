<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listes', function (Blueprint $table) {
            $table->string('type')->default('custom')->after('description');
        });

        $now = now();

        foreach (DB::table('users')->pluck('id') as $userId) {
            foreach ([
                ['type' => 'watchlist', 'titre' => 'Watchlist'],
                ['type' => 'favorites', 'titre' => 'Mes favoris'],
                ['type' => 'top_5', 'titre' => 'Mon top 5'],
            ] as $defaultList) {
                DB::table('listes')->insert([
                    'id' => (string) Str::uuid(),
                    'user_id' => $userId,
                    'titre' => $defaultList['titre'],
                    'description' => null,
                    'type' => $defaultList['type'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('listes')->whereIn('type', ['watchlist', 'favorites', 'top_5'])->delete();

        Schema::table('listes', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
