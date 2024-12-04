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
        Schema::create('blog', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title',255)->nullable();
            $table->text('content')->nullable();
            $table->string('slug',255)->nullable();
            $table->string('status',50)->nullable();
            $table->integer('cat_id')->nullable();
            $table->integer('image_id')->nullable();
            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();
            $table->softDeletes();

            //Languages
            $table->bigInteger('origin_id')->nullable();
            $table->string('lang',10)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');

    }
};
