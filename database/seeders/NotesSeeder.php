<?php

namespace Database\Seeders;

use App\Models\Notes;
use Illuminate\Database\Seeder;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notes::create([
            'Title' => 'Exercise',
            'Description' => 'Health is wealth'
        ]);
    }
}
