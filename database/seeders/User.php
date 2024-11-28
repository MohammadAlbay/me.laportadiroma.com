<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        ModelsUser::create([
            'email' => "admin@holasaci.com",
            'password' => "admin",
            'gender' => "Male",
            'birthdate' => now(),
            'firstname' => "Admin",
            'lastname' => "",
            'role_id' => 1,
            'business_brand_id' => 1
        ]);

    }
}
