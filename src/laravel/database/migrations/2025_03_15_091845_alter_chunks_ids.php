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
            DB::statement("UPDATE texts SET chunks_ids = '{}'::int[] WHERE chunks_ids IS NULL;");
            DB::statement("ALTER TABLE texts ALTER COLUMN chunks_ids SET DEFAULT '{}'::int[];");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('texts', function (Blueprint $table) {
            DB::statement("ALTER TABLE texts ALTER COLUMN chunks_ids SET DEFAULT NULL");
        });
    }
};
