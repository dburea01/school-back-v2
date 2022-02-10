<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\School;
use App\Models\User;
use App\Models\UserGroup;
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

            // create groups and users of groups
            $groups = Group::factory()->count(10)->create(['school_id' => $school->id]);

            foreach ($groups as $group) {
                $parents = User::factory()->count(random_int(1, 3))->create([
                    'school_id' => $school->id,
                    'role_id' => 'PARENT',
                    'last_name' => $group->name
                ]);

                $students = User::factory()->count(random_int(2, 3))->create([
                    'school_id' => $school->id,
                    'role_id' => 'STUDENT',
                    'last_name' => $group->name
                ]);

                $this->createUserGroup($group, $parents);
                $this->createUserGroup($group, $students);
            }
        }
    }

    public function createUserGroup(Group $group, $users)
    {
        foreach ($users as $user) {
            UserGroup::factory()->create([
                'group_id' => $group->id,
                'user_id' => $user->id
            ]);
        }
    }
}
