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
        Schema::create('texts', function (Blueprint $table) {
            $table->id()->primary();
            $table->longText('content');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
            $table->unsignedSmallInteger('version')->default(1);
            $table->jsonb('metrics')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('texts', function (Blueprint $table) {
            $table->dropForeign('texts_user_id_foreign');
            $table->dropIndex('texts_user_id_index');
            $table->dropForeign('texts_project_id_foreign');
            $table->dropIndex('texts_project_id_foreign');
        });
        Schema::dropIfExists('texts');
    }
};
