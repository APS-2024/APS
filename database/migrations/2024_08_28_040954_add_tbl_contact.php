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
        Schema::create('contact', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->string('first_name',126);

            $table->string('last_name',126);

            $table->string('email',126);

            $table->string('web_link',126)->nullable();

            $table->string('skype_contact',126)->nullable(); 

            $table->string('whatsapp_contact',126)->nullable(); 

            $table->string('page_view',126)->nullable(); 

            $table->string('adsense',126)->nullable(); 

            $table->string('message',256)->nullable();

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
        Schema::dropIfExists('contact');
    }
};
