<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Comptables
        DB::table('users')->insert([
            [
                'nom' => 'Durand',
                'prenom' => 'Claire',
                'adresse' => '7 Rue du Siège',
                'ville' => 'Marseille',
                'cp' => '13001',
                'dateEmbauche' => '2016-01-10',
                'role' => 'comptable',
                'login' => 'cdurand',
                'password' => bcrypt('passcp1'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Laurent',
                'adresse' => '15 Avenue de la Gare',
                'ville' => 'Lyon',
                'cp' => '69002',
                'dateEmbauche' => '2018-05-17',
                'role' => 'comptable',
                'login' => 'lmartin',
                'password' => bcrypt('passcp2'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Visiteurs 
        $visiteurs = [
            [
                'nom' => 'Opa',
                'prenom' => 'Julie',
                'adresse' => '2 Place de l\'Eglise',
                'ville' => 'Nice',
                'cp' => '06000',
                'dateEmbauche' => '2021-04-20',
                'role' => 'visiteur',
                'login' => 'jopa',
                'password' => bcrypt('pass1'),
            ],
            [
                'nom' => 'Leclerc',
                'prenom' => 'Michel',
                'adresse' => '10 Rue Victor Hugo',
                'ville' => 'Toulouse',
                'cp' => '31000',
                'dateEmbauche' => '2019-10-01',
                'role' => 'visiteur',
                'login' => 'mleclerc',
                'password' => bcrypt('visitorpass2'),
            ],
            [
                'nom' => 'Petit',
                'prenom' => 'Sophie',
                'adresse' => '3 Boulevard des Alpes',
                'ville' => 'Grenoble',
                'cp' => '38000',
                'dateEmbauche' => '2022-02-12',
                'role' => 'visiteur',
                'login' => 'spetit',
                'password' => bcrypt('visitorpass3'),
            ],
            [
                'nom' => 'Robert',
                'prenom' => 'Alain',
                'adresse' => '4 Chemin du Canal',
                'ville' => 'Strasbourg',
                'cp' => '67000',
                'dateEmbauche' => '2020-06-25',
                'role' => 'visiteur',
                'login' => 'arobert',
                'password' => bcrypt('visitorpass4'),
            ],
            [
                'nom' => 'Lambert',
                'prenom' => 'Camille',
                'adresse' => '12 Route de Paris',
                'ville' => 'Bordeaux',
                'cp' => '33000',
                'dateEmbauche' => '2018-11-13',
                'role' => 'visiteur',
                'login' => 'clambert',
                'password' => bcrypt('visitorpass5'),
            ],
            [
                'nom' => 'Dumont',
                'prenom' => 'Pierre',
                'adresse' => '9 Rue des Lilas',
                'ville' => 'Lille',
                'cp' => '59000',
                'dateEmbauche' => '2017-03-03',
                'role' => 'visiteur',
                'login' => 'pdumont',
                'password' => bcrypt('visitorpass6'),
            ],
        ];

        foreach ($visiteurs as $visiteur) {
            DB::table('users')->insert(array_merge($visiteur, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
