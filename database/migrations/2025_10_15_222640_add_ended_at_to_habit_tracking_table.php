<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   public function up()
{
    Schema::table('habit_tracking', function (Blueprint $table) {
        $table->timestamp('ended_at')->nullable()->after('started_at');
    });
}

public function down()
{
    Schema::table('habit_tracking', function (Blueprint $table) {
        $table->dropColumn('ended_at');
    });
}
};
