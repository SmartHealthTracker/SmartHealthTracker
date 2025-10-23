<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthLogsTable extends Migration
{
    public function up()
    {
        Schema::create('health_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Hydratation
            $table->integer('water')->nullable(); // en ml

            // Poids / IMC
            $table->float('weight')->nullable(); // kg
            $table->float('height')->nullable(); // cm

            // Alimentation / Nutriments
            $table->string('food_name')->nullable();
            $table->integer('calories')->nullable();
            $table->integer('protein')->nullable();
            $table->integer('carbs')->nullable();
            $table->integer('fat')->nullable();

            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('health_logs');
    }
}
