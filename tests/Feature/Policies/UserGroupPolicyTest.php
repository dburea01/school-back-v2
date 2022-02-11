<?php

namespace tests\Feature\Policies;

use App\Models\Group;
use App\Models\School;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Request;

class UserGroupPolicyTest extends TestCase
{
    use RefreshDatabase;
    use Request;

    public function test_only_the_director_can_add_a_user_to_a_group()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        $user = User::factory()->create(['school_id' => $school->id]);

        $teacher = User::factory()->create(['school_id' => $school->id, 'role_id' => 'TEACHER']);
        $this->actingAs($teacher);
        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups", ['user_id' => $user->id]);
        $response->assertStatus(403);

        $director = User::factory()->create(['school_id' => $school->id, 'role_id' => 'DIRECTOR']);
        $this->actingAs($director);
        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups", ['user_id' => $user->id]);
        $response->assertStatus(201);
    }

    public function test_only_the_director_can_remove_a_user_from_a_group()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        $user = User::factory()->create(['school_id' => $school->id]);
        $userGroup = UserGroup::factory()->create(['group_id' => $group->id, 'user_id' => $user->id]);

        $teacher = User::factory()->create(['school_id' => $school->id, 'role_id' => 'TEACHER']);
        $this->actingAs($teacher);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups/$userGroup->id");
        $response->assertStatus(403);

        $director = User::factory()->create(['school_id' => $school->id, 'role_id' => 'DIRECTOR']);
        $this->actingAs($director);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups/$userGroup->id");
        $response->assertStatus(204);
    }

    public function test_impossible_to_add_user_to_a_group_of_another_school()
    {
        $school1 = School::factory()->create();
        $group1 = Group::factory()->create(['school_id' => $school1->id]);
        $user1 = User::factory()->create(['school_id' => $school1->id]);
        $director1 = User::factory()->create(['school_id' => $school1->id, 'role_id' => 'DIRECTOR']);

        $school2 = School::factory()->create();
        $group2 = Group::factory()->create(['school_id' => $school2->id]);
        $user2 = User::factory()->create(['school_id' => $school2->id]);
        $director2 = User::factory()->create(['school_id' => $school2->id, 'role_id' => 'DIRECTOR']);

        $this->actingAs($director2);
        $response = $this->postJson($this->getEndPoint() . "schools/$school1->id/groups/$group1->id/user-groups", ['user_id' => $user2->id]);
        $response->assertStatus(403);
    }

    public function test_impossible_to_remove_user_from_a_group_of_another_school()
    {
        $school1 = School::factory()->create();
        $group1 = Group::factory()->create(['school_id' => $school1->id]);
        $user1 = User::factory()->create(['school_id' => $school1->id]);
        $director1 = User::factory()->create(['school_id' => $school1->id, 'role_id' => 'DIRECTOR']);
        $userGroup1 = UserGroup::factory()->create(['group_id' => $group1->id, 'user_id' => $user1->id]);

        $school2 = School::factory()->create();
        $group2 = Group::factory()->create(['school_id' => $school2->id]);
        $user2 = User::factory()->create(['school_id' => $school2->id]);
        $director2 = User::factory()->create(['school_id' => $school2->id, 'role_id' => 'DIRECTOR']);

        $this->actingAs($director2);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school1->id/groups/$group1->id/user-groups/$userGroup1->id", ['user_id' => $user2->id]);
        $response->assertStatus(403);
    }
}
