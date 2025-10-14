<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('habits', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('type'); // <-- ajoute cette ligne
        $table->integer('duration')->nullable();
        $table->string('icon')->nullable();
        $table->unsignedBigInteger('user_id');
        $table->time('schedule_time')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
