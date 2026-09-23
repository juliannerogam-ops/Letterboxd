<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('film', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('tmdb_id')->unique();
            $table->string('titre');
            $table->string('genre')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('annee_sortie')->nullable();
            $table->date('date_sortie')->nullable();
            $table->unsignedSmallInteger('duree_minutes')->nullable();
            $table->text('affiche_url')->nullable();
            $table->string('statut_sortie')->nullable();
            $table->dateTime('derniere_synchronisation')->nullable();
            $table->uuid('realisateur_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('film');
    }
};