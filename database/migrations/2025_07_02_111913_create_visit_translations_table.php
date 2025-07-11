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
        Schema::create('visit_translations', function (Blueprint $table) {
            $table->id();
            $table->string('visit_type');
            $table->string('locale')->index();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');

            $table->unique(['visit_id','locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_translations');
    }
};
