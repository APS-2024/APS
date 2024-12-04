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
        Schema::create('blog_category', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('name', 255)->nullable();

            $table->text('content')->nullable();

            $table->string('slug', 255)->nullable();

            $table->string('status', 50)->nullable();

            $table->integer('create_user')->nullable();

            $table->integer('update_user')->nullable();

            $table->softDeletes();



            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_category');

    }
};
