<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        // Creating projects
        $projects = [
            ['name' => 'Project A', 'status' => 'pending'],
            ['name' => 'Project B', 'status' => 'in_progress'],
            ['name' => 'Project C', 'status' => 'completed'],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Assign random users to each project
            $users = User::inRandomOrder()->limit(rand(1, 3))->pluck('id'); // Get 1 to 3 random users
            $project->users()->attach($users);
        }
    }
}
