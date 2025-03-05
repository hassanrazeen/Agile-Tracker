<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        Attribute::create(['name' => 'Priority', 'type' => 'select']);
        Attribute::create(['name' => 'Deadline', 'type' => 'date']);
    }
}
