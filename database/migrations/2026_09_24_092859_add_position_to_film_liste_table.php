<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('film_liste', function (Blueprint $table) {
            $table->unsignedTinyInteger('position')->nullable()->after('film_id');
            $table->index(['liste_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('film_liste', function (Blueprint $table) {
            $table->dropIndex(['liste_id', 'position']);
            $table->dropColumn('position');
        });
    }
};
