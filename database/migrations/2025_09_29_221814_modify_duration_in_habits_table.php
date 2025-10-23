<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            // Exemple : changer la colonne 'duration' en integer nullable
            $table->integer('duration')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            // Revenir à l'état précédent, ex : non-nullable
            $table->integer('duration')->nullable(false)->change();
        });
    }
};
