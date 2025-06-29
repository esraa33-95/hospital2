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
        Schema::create('addresses', function (Blueprint $table) {
         $table->id();
         $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
         $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
         $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
         $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');

         $table->decimal('lat');   
         $table->decimal('lng');
         $table->boolean('is_main')->default(false);
           
         $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
