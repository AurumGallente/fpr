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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->index('id');
        });

        Schema::create('permission_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('permission_id');
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->ondelete('cascade');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permissions');
    }
};
