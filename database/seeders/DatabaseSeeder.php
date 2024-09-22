<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use App\Models\WorkLog;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();

        User::factory()->create([
            'name' => 'Amit Kumar Biswas',
            'email' => 'amitkumar@companyname.com',
            'designation' => 'Project Manager',
        ]);

        foreach(range(1, 20) as $project){
            $p = Project::create([
                "code" => "SMPL-" . str()->padLeft($project, 2, 0),
                "full_name" => "Sample Project " .$project,
                "client_name" => fake()->name(),
                "client_email" => fake()->safeEmail(),
            ]);

            $users = User::inRandomOrder()->take(5)->pluck('id')->toArray();
            $p->asignees()->syncWithPivotValues($users, ["added_by_id" => 1]);

            foreach($users as $user){
                WorkLog::create([
                    'project_id' => $p->id,
                    'user_id' => $user,
                    'date_of_work' => now()->addDay()->subDays(mt_rand(1, 2)),
                    'work_duration_in_minutes' => mt_rand(100, 200),
                    'work_description' => fake()->paragraph(),
                ]);
            }
        }
    }
}
