<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('film', function (Blueprint $table): void {
            $table->decimal('note', 2, 1)->nullable()->after('affiche_url');
            $table->unsignedInteger('avis_count')->default(0)->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('film', function (Blueprint $table): void {
            $table->dropColumn(['note', 'avis_count']);
        });
    }
};
