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
        Schema::create('ligne_frais_forfait', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiche_frais_id')->constrained('fiches_frais');
            $table->foreignId('frais_forfait_id')->constrained('frais_forfait');
            $table->integer('quantite')->default(0);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_frais_forfait');
    }
};
