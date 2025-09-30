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
    Schema::table('health_logs', function (Blueprint $table) {
        $table->integer('steps')->nullable();
        $table->decimal('sleep_hours', 4, 2)->nullable();
        $table->integer('heart_rate')->nullable();
        $table->string('blood_pressure')->nullable();
    });
}

public function down()
{
    Schema::table('health_logs', function (Blueprint $table) {
        $table->dropColumn(['steps', 'sleep_hours', 'heart_rate', 'blood_pressure']);
    });
}

};
