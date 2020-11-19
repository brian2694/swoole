<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserMongo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Brian Sánchez",
            "email" => "brian2694@hotmail.com",
            "password"  => bcrypt("secret")
        ]);

        User::create([
            "name" => "Laura Arias",
            "email" => "laura@hotmail.com",
            "password"  => bcrypt("secret")
        ]);
        // UserMongo::create([
        //     "name" => "Brian Sánchez",
        //     "email" => "brian2694@hotmail.com",
        //     "password"  => bcrypt("secret")
        // ]);

        // UserMongo::create([
        //     "name" => "Laura Arias",
        //     "email" => "laura@hotmail.com",
        //     "password"  => bcrypt("secret")
        // ]);
    }
}
