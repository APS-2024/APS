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
        Schema::create('media_files', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('file_name',255)->nullable();

            $table->string('file_path',255)->nullable();

            $table->string('file_size',255)->nullable();

            $table->string('file_type',255)->nullable();

            $table->string('file_extension',255)->nullable();

            $table->string('driver',255)->nullable();

            $table->tinyInteger('is_private')->nullable()->default(0);

            $table->integer('create_user')->nullable();

            $table->integer('update_user')->nullable();

            $table->softDeletes();

            $table->integer('app_id')->nullable();

            $table->integer('app_user_id')->nullable();

            $table->integer('file_width')->nullable();

            $table->integer('file_height')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');

    }
};
