<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;// use Illuminate\Support\Facades\Schema;

class EtatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            DB::table('etats')->insert([
                ['id' => 'CL', 'libelle' => 'Saisie clôturée'],
                ['id' => 'CR', 'libelle' => 'Fiche créée, saisie en cours'],
                ['id' => 'RB', 'libelle' => 'Remboursée'],
                ['id' => 'VA', 'libelle' => 'Validée et mise en paiement'],
            ]);
        }
    }
}
