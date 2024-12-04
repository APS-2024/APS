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
        Schema::create('ad_unit_percentage', function (Blueprint $table) {
            $table->id();
            $table->string('ad_unit_id');
            $table->unsignedBigInteger('user_id');
            $table->string('site_name')->nullable();
            $table->string('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_ad_unit_percentage');
    }
};
