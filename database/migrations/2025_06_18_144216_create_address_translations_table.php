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
        Schema::create('address_translations', function (Blueprint $table) {
            $table->id();
            $table->text('street_name')->default(false);
            $table->text('building_number')->default(false);
            $table->text('floor_number')->default(false);
            $table->text('landmark')->nullable();

            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
           
            $table->unique(['address_id','locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_translations');
    }
};
