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
    Schema::table('habits', function (Blueprint $table) {
        $table->time('schedule_time')->nullable()->after('target');
    });
}

public function down()
{
    Schema::table('habits', function (Blueprint $table) {
        $table->dropColumn('schedule_time');
    });
}
};
