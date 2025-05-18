<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        //
        // 1) VISITEURS
        //
        $visiteurs = [
            [
                'nom'          => 'Opa',
                'prenom'       => 'Julie',
                'adresse'      => '2 Place de l\'Eglise',
                'ville'        => 'Nice',
                'cp'           => '06000',
                'dateEmbauche' => '2021-04-20',
                'role'         => 'visiteur',
                'email'        => 'jopa@example.com',
                'password'     => bcrypt('pass1'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nom'          => 'Leclerc',
                'prenom'       => 'Michel',
                'adresse'      => '10 Rue Victor Hugo',
                'ville'        => 'Toulouse',
                'cp'           => '31000',
                'dateEmbauche' => '2019-10-01',
                'role'         => 'visiteur',
                'email'        => 'mleclerc@example.com',
                'password'     => bcrypt('visitorpass2'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nom'          => 'Petit',
                'prenom'       => 'Sophie',
                'adresse'      => '3 Boulevard des Alpes',
                'ville'        => 'Grenoble',
                'cp'           => '38000',
                'dateEmbauche' => '2022-02-12',
                'role'         => 'visiteur',
                'email'        => 'spetit@example.com',
                'password'     => bcrypt('visitorpass3'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nom'          => 'Robert',
                'prenom'       => 'Alain',
                'adresse'      => '4 Chemin du Canal',
                'ville'        => 'Strasbourg',
                'cp'           => '67000',
                'dateEmbauche' => '2020-06-25',
                'role'         => 'visiteur',
                'email'        => 'arobert@example.com',
                'password'     => bcrypt('visitorpass4'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nom'          => 'Lambert',
                'prenom'       => 'Camille',
                'adresse'      => '12 Route de Paris',
                'ville'        => 'Bordeaux',
                'cp'           => '33000',
                'dateEmbauche' => '2018-11-13',
                'role'         => 'visiteur',
                'email'        => 'clambert@example.com',
                'password'     => bcrypt('visitorpass5'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nom'          => 'Dumont',
                'prenom'       => 'Pierre',
                'adresse'      => '9 Rue des Lilas',
                'ville'        => 'Lille',
                'cp'           => '59000',
                'dateEmbauche' => '2017-03-03',
                'role'         => 'visiteur',
                'email'        => 'pdumont@example.com',
                'password'     => bcrypt('visitorpass6'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        DB::table('users')->insert($visiteurs);

        //
        // 2) COMPTABLES
        //
        $comptables = [
            [
                'nom'          => 'Durand',
                'prenom'       => 'Claire',
                'adresse'      => '7 Rue du Siège',
                'ville'        => 'Marseille',
                'cp'           => '13001',
                'dateEmbauche' => '2016-01-10',
                'role'         => 'comptable',
                'email'        => 'cdurand@example.com',
                'password'     => bcrypt('passcp1'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nom'          => 'Martin',
                'prenom'       => 'Laurent',
                'adresse'      => '15 Avenue de la Gare',
                'ville'        => 'Lyon',
                'cp'           => '69002',
                'dateEmbauche' => '2018-05-17',
                'role'         => 'comptable',
                'email'        => 'lmartin@example.com',
                'password'     => bcrypt('passcp2'),
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        DB::table('users')->insert($comptables);
    }
}
