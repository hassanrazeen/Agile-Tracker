<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ProjectSeeder::class,
            TimesheetSeeder::class,
            AttributeSeeder::class,
            AttributeValueSeeder::class,
        ]);
    }
}