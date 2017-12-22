<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Administrador
        $user = new User();
        $user->name = 'Administrador';
        $user->email = 'admin@mail.com';
        $user->password = bcrypt('123456');
        $user->phone = '04120000000';
        $user->level = User::LEVEL_ADMIN;
        $user->save();

        // Usuario
        $user = new User();
        $user->name = 'Usuario';
        $user->email = 'user@mail.com';
        $user->password = bcrypt('123456');
        $user->phone = '04120000000';
        $user->level = User::LEVEL_USER;
        $user->save();
    }
}
