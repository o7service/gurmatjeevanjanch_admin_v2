<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(attributes: [
            'autoId' => 1,
            'name' => "Admin",
            'phone' => 7876576575,
            'email' => "admin@gmail.com",
            'password' => Hash::make('123'),
            'userType' => 1,

        ]);
    }
}
