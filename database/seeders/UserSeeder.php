<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => Uuid::uuid4()->toString(),
                'first_name' => 'Owner',
                'email' => 'owner@gmail.com',
                'telephone' => '62895617545306',
                'password' => bcrypt('123456789'),
                'type' => 0,
                'active_status' => 0,
            ],

            [
                'id' => Uuid::uuid4()->toString(),
                'first_name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'telephone' => '62895617545308',
                'password' => bcrypt('123456789'),
                'type' => 1,
                'active_status' => 0,
            ],

            [
                'id' => Uuid::uuid4()->toString(),
                'first_name' => 'Pelanggan',
                'email' => 'pelanggan@gmail.com',
                'telephone' => '62895617545307',
                'password' => bcrypt('123456789'),
                'type' => 2,
                'active_status' => 0,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
