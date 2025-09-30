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
        Schema::create('habit_trackings', function (Blueprint $table) {
        $table->id();

        // Lien avec l’habitude
        $table->unsignedBigInteger('habit_id');
        $table->foreign('habit_id')->references('id')->on('habits')->onDelete('cascade');

        // Lien avec l’utilisateur (si nécessaire)
        $table->unsignedBigInteger('user_id');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        // Date de suivi
        $table->date('tracking_date');

        // Statut ou progression
        $table->boolean('completed')->default(false);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_trackings');
    }
};
