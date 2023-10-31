<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
            Role::create([
                'name' => 'user'
            ]);
            Role::create([
                'name' => 'admin'
            ]);
            \App\Models\User::create([
                'fio' => 'Admin',
                'login' => 'admin',
                'password' => 'password',
                'phone' => '937711891'
            ])->assignRole('admin');
    }
}
