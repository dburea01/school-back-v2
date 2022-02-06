<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\School;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = School::all();

        // create some groups for each school
        foreach ($schools as $school) {
            Group::factory()->count(random_int(50, 100))->create([
                'school_id' => $school->id
            ]);
        }
    }
}
