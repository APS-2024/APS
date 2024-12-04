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

        Schema::create('deducation_revenue', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->date('date');

            $table->bigInteger('ad_unit_id');

            $table->bigInteger('deducation')->nullable();

            $table->bigInteger('final_revenue')->nullable();

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
        Schema::dropIfExists('deducation_revenue');

    }
};
