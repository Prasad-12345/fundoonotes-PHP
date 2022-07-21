<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Hardik',
            'email' => 'hardik@gmail.com',
            'password' => Hash::make('Hardik@123'),
        ]);
    }
}
