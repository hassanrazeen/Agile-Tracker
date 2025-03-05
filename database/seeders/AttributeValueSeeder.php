<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AttributeValue;
use App\Models\Attribute;
use App\Models\Project;

class AttributeValueSeeder extends Seeder
{
    public function run()
    {
        $priority = Attribute::where('name', 'Priority')->first();
        $deadline = Attribute::where('name', 'Deadline')->first();
        $project = Project::first();

        AttributeValue::create([
            'attribute_id' => $priority->id,
            'entity_id' => $project->id,
            'value' => 'High'
        ]);

        AttributeValue::create([
            'attribute_id' => $deadline->id,
            'entity_id' => $project->id,
            'value' => '2025-03-10'
        ]);
    }
}
