<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // référence à users
            $table->foreignId('resource_id')->constrained()->onDelete('cascade'); // référence à resources
            $table->string('action')->default('view'); // type d'action (view, like, etc.)
            $table->timestamps();

            $table->unique(['user_id', 'resource_id', 'action']); // éviter les doublons
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_resources');
    }
};
