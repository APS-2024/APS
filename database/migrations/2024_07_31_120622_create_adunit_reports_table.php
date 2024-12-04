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
        Schema::create('adunit_reports', function (Blueprint $table) {
            $table->id();
            $table->string('ad_unit_id');
            $table->string('date');
            $table->integer('impressions');
            $table->decimal('revenue', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adunit_reports');
    }
};
