<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user= new User();
        $user->name="Pino";
        $user->email="pino.macri@hotmail.it";
        $user->password=bcrypt("prova");
        $user->save();
    }
}