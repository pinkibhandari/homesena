<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class FixedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::updateOrCreate(
            ['phone' => '9953044591'], // fixed phone
            [
                'name' => 'HomeSena',
                'otp' => bcrypt('123456'),
                'is_fixed' => 1
            ]
        );
    }
}

