<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BranchUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = bcrypt('123456789');

        // Super Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'admin.cebu.abc@gmail.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => $password,
                'plain_password' => '123456789',
                'is_super_admin' => true,
                'position' => 'Super Admin',
                'branch' => 'All Branches'
            ]
        );

        $users = [
            ['first_name' => 'Nemia Bless', 'last_name' => 'Ederango', 'branch' => 'Mandaue Branch', 'email' => 'nemiabless.ederango@gmail.com'],
            ['first_name' => 'John Paul', 'last_name' => 'Hynson', 'branch' => 'Lapu-Lapu Branch', 'email' => 'johnpaulhynson1999@gmail.com'],
            ['first_name' => 'Kris Loren', 'last_name' => 'Ogoc', 'branch' => 'Lapu-Lapu Branch', 'email' => 'krisloren.ogoc@gmail.com'],
            ['first_name' => 'Nichole', 'last_name' => 'Sario', 'branch' => 'Balamban Branch', 'email' => 'sarionichole@gmail.com'],
            ['first_name' => 'Judy Anne', 'last_name' => 'Bartolaba', 'branch' => 'Talisay Branch', 'email' => 'abalotrab13@gmail.com'],
            ['first_name' => 'Roland Troy', 'last_name' => 'Yguinto', 'branch' => 'Talisay Branch', 'email' => 'yguintorolandtroy@gmail.com'],
            ['first_name' => 'Divine Grace', 'last_name' => 'Cumayas', 'branch' => 'Bogo Branch', 'email' => 'divinecumayas@gmail.com'],
            ['first_name' => 'Aileen May', 'last_name' => 'Orcullo', 'branch' => 'Tubigon Branch', 'email' => 'allen22catel@gmail.com'],
            ['first_name' => 'John Kelly', 'last_name' => 'Gimena', 'branch' => 'Guadalupe Branch', 'email' => 'johnkellygimena@gmail.com'],
            ['first_name' => 'John Kent', 'last_name' => 'Zamora', 'branch' => 'Inabanga Branch', 'email' => 'zjohnkent@gmail.com'],
            ['first_name' => 'Ma. Cristina', 'last_name' => 'Melecio', 'branch' => 'Tagbiliran Branch', 'email' => 'macristinamelecio12@gmail.com'],
            ['first_name' => 'Stephanie Dulce', 'last_name' => 'Tulod', 'branch' => 'Guadalupe Branch', 'email' => 'tulodstephaniedulce@gmail.com'],
            ['first_name' => 'Florabel', 'last_name' => 'Amba', 'branch' => 'Talibon Branch', 'email' => 'florabelpolestico21@gmail.com'],
            ['first_name' => 'Lyra Faith', 'last_name' => 'Loon', 'branch' => 'Camotes Branch', 'email' => 'lyraloon00@gmail.com'],
            ['first_name' => 'Kent Algae', 'last_name' => 'Villacorta', 'branch' => 'Camotes Branch', 'email' => 'kentvillacorta@gmail.com'],
            ['first_name' => 'Kyla Sheena', 'last_name' => 'Nudalo', 'branch' => 'Camotes Branch', 'email' => 'kylasheena21@gmail.com'],
            ['first_name' => 'Melanie Ann', 'last_name' => 'Taladtad', 'branch' => 'Guadalupe Branch', 'email' => 'melanieannlibradillataladtad@gmail.com'],
            ['first_name' => 'Danica Mae', 'last_name' => 'Palacio', 'branch' => 'Talisay Branch', 'email' => 'danicamaepalacio@gmail.com'],
            ['first_name' => 'Gienickxan Jade', 'last_name' => 'Abrazado', 'branch' => 'Guadalupe Branch', 'email' => 'gienickxan.abrazado@gmail.com'],
            ['first_name' => 'Ajiel', 'last_name' => 'Naciongay', 'branch' => 'Mandaue Branch', 'email' => 'ajielvnaciongayo@gmail.com'],
            ['first_name' => 'Joselle Mae', 'last_name' => 'Torregosa', 'branch' => 'Inabanga Branch', 'email' => 'torregosajoselle@gmail.com'],
            ['first_name' => 'Gwyneth', 'last_name' => 'Mendez', 'branch' => 'Consolacion Branch', 'email' => 'mendez17gwyneth@gmail.com'],
            ['first_name' => 'Divine', 'last_name' => 'Dag-uman', 'branch' => 'Balamban Branch', 'email' => 'deborahdaguman@gmail.com'],
            ['first_name' => 'Rose Marie', 'last_name' => 'Resane', 'branch' => 'Balamban Branch', 'email' => 'resanerosemarieb123@gmail.com'],
            ['first_name' => 'Ma. Nica', 'last_name' => 'Porlas', 'branch' => 'Carmen Branch', 'email' => 'manicaporlas@gmail.com'],
            ['first_name' => 'Angel Kay', 'last_name' => 'Atup', 'branch' => 'Carmen Branch', 'email' => 'jaihoo_kay@yahoo.com'],
            ['first_name' => 'Jully Ann', 'last_name' => 'Petecio', 'branch' => 'Panglao Branch', 'email' => 'jullyannpetecio1@gmail.com'],
            ['first_name' => 'Ivy Rizza', 'last_name' => 'Aparre', 'branch' => 'Panglao Branch', 'email' => 'vyrizza14@gmail.com'],
            ['first_name' => 'Mary Ann Grace', 'last_name' => 'Ybañez', 'branch' => 'Carmen Branch', 'email' => 'annybanez12@gmail.com'],
            ['first_name' => 'Vince Elvel', 'last_name' => 'Pales', 'branch' => 'Liloan Branch', 'email' => 'vincelvel@gmail.com'],
            ['first_name' => 'Renalyn', 'last_name' => 'Saladaga', 'branch' => 'Admin Head', 'email' => 'renalynvillarino162820@gmail.com'],
            ['first_name' => 'Ariame', 'last_name' => 'Negro', 'branch' => 'Talibon Branch', 'email' => 'ariamenegro18@gmail.com'],
            ['first_name' => 'Debbie Mae', 'last_name' => 'Taladro', 'branch' => 'Bogo Branch', 'email' => 'taladrodebbiemae@gmail.com'],
            ['first_name' => 'Mariae Frances Ianne', 'last_name' => 'Raut', 'branch' => 'Jagna Branch', 'email' => 'ianneraut@gmail.com'],
            ['first_name' => 'Ray Anne', 'last_name' => 'Felisilda', 'branch' => 'Ubay Branch', 'email' => 'Yanhpilvera@gmail.com'],
            ['first_name' => 'Jess Nichole', 'last_name' => 'Martinote', 'branch' => 'Jagna Branch', 'email' => 'jessnicholemartinote@gmail.com'],
            ['first_name' => 'Chrisyl', 'last_name' => 'Pales', 'branch' => 'Consolacion Branch', 'email' => 'chrisylcpales@gmail.com'],
            ['first_name' => 'Tiffany', 'last_name' => 'Cañadilla', 'branch' => 'Lapu-Lapu Branch', 'email' => 'limtiffany832@gmail.com'],
            ['first_name' => 'Thomas', 'last_name' => 'Piape', 'branch' => 'HR Office', 'email' => 'piapethomas579@gmail.com'],
            ['first_name' => 'Marymil', 'last_name' => 'Cane', 'branch' => 'Mandaue Branch', 'email' => 'limyram715@gmail.com'],
            ['first_name' => 'Marglen', 'last_name' => 'Pamplona', 'branch' => 'Mandaue Branch', 'email' => 'marglenpamplona23@gmail.com'],
            ['first_name' => 'Feliz Iva', 'last_name' => 'Muaña', 'branch' => 'Liloan Branch', 'email' => 'felizmuana25@gmail.com'],
            ['first_name' => 'Patrick Dave', 'last_name' => 'Booc', 'branch' => 'Consolacion Branch', 'email' => 'bbgaven1@gmail.com'],
            ['first_name' => 'Vithil', 'last_name' => 'Tagalog', 'branch' => 'Liloan Branch', 'email' => 'vithiltagalog1986@gmail.com'],
            ['first_name' => 'Allyza Mae', 'last_name' => 'Gabutan', 'branch' => 'HR Office', 'email' => 'allyzamaegabutan1999@gmail.com'],
            ['first_name' => 'Darel', 'last_name' => 'Tibong', 'branch' => 'Assistant Admin', 'email' => 'darelamizolatibong.official@gmail.com'],
            ['first_name' => 'Junibe', 'last_name' => 'Yecyec', 'branch' => 'Tagbiliran Branch', 'email' => 'junibeyecyec_15@yahoo.com'],
            ['first_name' => 'Ghiane Irish', 'last_name' => 'Ausejo', 'branch' => 'Tubigon Branch', 'email' => 'ghianeirishmanigo@gmail.com'],
            ['first_name' => 'Sheena', 'last_name' => 'Cerelejia', 'branch' => 'Ubay Branch', 'email' => 'scerelijia@gmail.com'],
        ];

        foreach ($users as $userData) {
            \App\Models\User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => $password,
                    'plain_password' => '123456789',
                    'status' => true,
                    'position' => 'Staff'
                ])
            );
        }
    }
}
