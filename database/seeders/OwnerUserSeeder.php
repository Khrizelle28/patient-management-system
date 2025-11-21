<?php

namespace Database\Seeders;

use App\Classes\UserStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::updateOrCreate(['email' => "sablanbibay1@gmail.com"],[
            'first_name'        => "Khrizelle",
            'middle_name'       => "Primero",
            'last_name'         => "Sablan",
            'username'          => "owner",
            'email'             => "sablanbibay1@gmail.com",
            'username'          => "sablanbibay1@gmail.com",
            'password'          => 'Secret123',
            'status'            => UserStatus::ACTIVATED,
            'sex'               => 'Female',
            'email_verified_at' => Carbon::now(),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        $adminUser->assignRole('Owner');
    }
}
