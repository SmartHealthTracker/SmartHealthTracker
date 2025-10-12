<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
{
    Schema::create('comments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('resource_id');
        $table->unsignedBigInteger('user_id'); 
        $table->text('content');
        $table->timestamp('date')->useCurrent();
        $table->timestamps();

        $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}



    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
