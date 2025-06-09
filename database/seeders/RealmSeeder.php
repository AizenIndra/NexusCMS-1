<?php

namespace Database\Seeders;

use App\Models\Realm;
use Illuminate\Database\Seeder;

class RealmSeeder extends Seeder
{
    public function run()
    {
        Realm::create([
            'name' => 'Nexus',
            'hostname' => '127.0.0.1',
            'expansion' => 2,
            'emulator' => 'Trinity',
            'port' => 8085,
            'auth_database' => json_encode([
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'auth',
                'username' => 'root',
                'password' => ''
            ]),
            'world_database' => json_encode([
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'world',
                'username' => 'root',
                'password' => ''
            ]),
            'console_hostname' => '127.0.0.1',
            'console_username' => 'admin',
            'console_password' => 'admin',
            'console_urn' => 'http://127.0.0.1:8085'
        ]);
    }
} 