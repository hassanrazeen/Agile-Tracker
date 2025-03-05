<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\Project;

class TimesheetSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $project = Project::first();

        Timesheet::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'task_name' => 'Design Database',
            'date' => now()->subDays(5),
            'hours' => 4
        ]);

        Timesheet::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'task_name' => 'Develop API',
            'date' => now()->subDays(3),
            'hours' => 6
        ]);
    }
}
