<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('habit_tracking', function (Blueprint $table) {
            $table->integer('elapsed_minutes')->default(0)->after('progress');
            $table->timestamp('started_at')->nullable()->after('elapsed_minutes');
            $table->string('state')->default('not_started')->after('started_at'); // not_started, in_progress, done
        });
    }

    public function down(): void
    {
        Schema::table('habit_tracking', function (Blueprint $table) {
            $table->dropColumn(['elapsed_minutes', 'started_at', 'state']);
        });
    }
};

