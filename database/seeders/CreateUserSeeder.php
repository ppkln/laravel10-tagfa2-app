<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = [
            [
                'name' => 'AdminSeeder3',
                'email' => 'adminseeder3@gg.com',
                'lv_working' => '9',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'UserSeeder1',
                'email' => 'userseeder1@gg.com',
                'lv_working' => '0',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'AdminSeeder4',
                'email' => 'AdminSeeder4@gg.com',
                'lv_working' => '9',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'AdminSeeder5',
                'email' => 'adminseeder5@gg.com',
                'lv_working' => '9',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'UserSeeder2',
                'email' => 'userseeder2@gg.com',
                'lv_working' => '0',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'AdminSeeder6',
                'email' => 'AdminSeeder6@gg.com',
                'lv_working' => '9',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'UserSeeder3',
                'email' => 'userseeder3@gg.com',
                'lv_working' => '0',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'UserSeeder4',
                'email' => 'userseeder4@gg.com',
                'lv_working' => '0',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'UserSeeder5',
                'email' => 'userseeder5@gg.com',
                'lv_working' => '0',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'UserSeeder6',
                'email' => 'userseeder6@gg.com',
                'lv_working' => '0',
                'password' => bcrypt('123456789')
            ],
            [
                'name' => 'UserSeeder7',
                'email' => 'userseeder7@gg.com',
                'lv_working' => '0',
                'password' => bcrypt('123456789')
            ]
        ];

        foreach ($user as $key => $value){
            User::create($value);
        }
    }
}
