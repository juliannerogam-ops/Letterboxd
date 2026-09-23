<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('film', function (Blueprint $table) {
            $table->string('realisateur')->nullable()->after('realisateur_id');
            $table->boolean('est_sorti')->default(false)->after('realisateur');
        });
    }

    public function down(): void
    {
        Schema::table('film', function (Blueprint $table) {
            $table->dropColumn(['realisateur', 'est_sorti']);
        });
    }
};
