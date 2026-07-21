<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'penulis@rowosari3r.com'],
            [
                'name' => 'Penulis',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'author',
            ]
        );
    }
}
