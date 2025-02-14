<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chunk_text', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chunk_id');
            $table->unsignedBigInteger('text_id');
            $table->foreign('chunk_id')->references('id')->on('chunks')->onDelete('cascade');
            $table->foreign('text_id')->references('id')->on('texts')->onDelete('cascade');
        });

        Schema::table('chunks', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chunk_text');
        Schema::table('texts', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable();
        });
    }
};
