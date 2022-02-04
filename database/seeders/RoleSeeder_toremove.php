<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\School;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = School::all();

        foreach ($schools as $school) {
            Role::factory()->count(random_int(3, 8))->create(['school_id' => $school->id]);
        }
    }
}
