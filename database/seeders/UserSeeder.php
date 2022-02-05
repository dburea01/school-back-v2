<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1 superadmin user
        User::factory()->create([
            'school_id' => null,
            'role_id' => 'SUPERADMIN',
            'status' => 'ACTIVE',
        ]);

        // for each school, create 1 director + some teachers
        $schools = School::all();

        foreach ($schools as $school) {
            User::factory()->count(2)->create([
                'school_id' => $school->id,
                'role_id' => 'DIRECTOR',
                'status' => 'ACTIVE',
            ]);

            User::factory()->count(10)->create([
                'school_id' => $school->id,
                'role_id' => 'TEACHER',
            ]);

            User::factory()->count(10)->create([
                'school_id' => $school->id,
                'role_id' => 'PARENT',
            ]);

            User::factory()->count(20)->create([
                'school_id' => $school->id,
                'role_id' => 'STUDENT',
            ]);
        }
    }
}
