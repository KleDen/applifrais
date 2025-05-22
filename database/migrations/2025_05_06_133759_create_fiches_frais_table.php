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
        Schema::create('fiches_frais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->integer('annee'); // Добавили год
            $table->string('mois', 2); // Месяц, например "05"
            $table->integer('nb_justificatifs')->default(0);
            $table->decimal('montant_valide', 10, 2)->default(0);
            $table->string('etat_id', 2); // Статус (например, 'CR')
            $table->foreign('etat_id')->references('id')->on('etats');
            $table->date('date_modif')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiches_frais');
    }
};
