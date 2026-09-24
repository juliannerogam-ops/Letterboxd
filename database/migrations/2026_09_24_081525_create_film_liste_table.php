<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('film_liste', function (Blueprint $table) {
            $table->foreignUuid('liste_id')->constrained('listes')->cascadeOnDelete();
            $table->foreignUuid('film_id')->constrained('film')->cascadeOnDelete();
            $table->primary(['liste_id', 'film_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('film_liste');
    }
};
