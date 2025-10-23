<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_saif_id')->constrained('habitsaif')->onDelete('cascade'); // Explicit table name
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 8, 2);
            $table->dateTime('logged_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};