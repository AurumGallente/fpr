<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('texts', function (Blueprint $table) {
            $table->dropColumn(['chunks_ids']);
        });
        Schema::table('texts', function (Blueprint $table) {
            DB::statement('ALTER TABLE texts ADD COLUMN chunks_ids integer[]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('texts', function (Blueprint $table) {
            $table->dropColumn(['chunks_ids']);
        });
    }
};
