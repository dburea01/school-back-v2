<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\School;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserGroupTest extends TestCase
{
    use RefreshDatabase;
    use Request;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createSchoolAndUserWithRole('SUPERADMIN'));
    }

    public function test_add_user_to_a_group_without_body_must_return_422()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        User::factory()->create(['school_id' => $school->id]);

        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups");

        $response->assertStatus(422)
            ->assertInvalid('user_id');
    }

    public function test_add_an_unknown_user_to_a_group_must_return_422()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);

        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups", ['user_id' => 'dae33508-783d-4540-a6d9-6ddfccdd54de']);

        $response->assertStatus(422)
            ->assertInvalid('user_id');
    }

    public function test_add_user_to_a_group_must_return_201_and_the_user_is_added()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        $user = User::factory()->create(['school_id' => $school->id]);

        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups", ['user_id' => $user->id]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('user_groups', ['user_id' => $user->id, 'group_id' => $group->id]);
    }

    public function test_add_user_who_already_belongs_to_the_group_must_return_422()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        $user = User::factory()->create(['school_id' => $school->id]);
        UserGroup::factory()->create(['group_id' => $group->id, 'user_id' => $user->id]);

        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups", ['user_id' => $user->id]);

        $response->assertStatus(422);
    }

    public function test_remove_user_from_a_group_must_return_204_and_the_user_is_removed()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        $user = User::factory()->create(['school_id' => $school->id]);
        $userGroup = UserGroup::factory()->create(['group_id' => $group->id, 'user_id' => $user->id]);

        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/groups/$group->id/user-groups/$userGroup->id");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('user_groups', ['user_id' => $user->id, 'group_id' => $group->id]);
    }
}
