<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
   public function up()
{
    Schema::create('resources', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('category')->nullable();
        $table->unsignedBigInteger('created_by');
        $table->timestamps();

        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
    });
}



   
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
