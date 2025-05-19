<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FraisForfaitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('frais_forfait')->insert([
            [
                'id'         => 1,
                'libelle'    => 'Repas restaurant',
                'montant'    => 25.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 2,
                'libelle'    => 'Nuitée',
                'montant'    => 80.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 3,
                'libelle'    => 'Forfait étape',
                'montant'    => 110.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 4,
                'libelle'    => 'Kilométrage',
                'montant'    => 0.62,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
