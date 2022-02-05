<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest_toremove extends TestCase
{
    use RefreshDatabase;
    use Request;

    public function test_get_roles_of_a_school()
    {
        $school = School::factory()->create();
        Role::factory()->count(3)->create(['school_id' => $school->id]);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/roles");

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(3, count($data));
    }

    public function test_get_a_role()
    {
        $school = School::factory()->create();
        $role = Role::factory()->create(['school_id' => $school->id]);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/roles/$role->id");

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals($data['id'], $role->id);
        $this->assertEquals($data['school_id'], $role->school_id);
        $this->assertEquals($data['name'], $role->name);
        $this->assertEquals($data['comment'], $role->comment);
        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('created_by', $data);
        $this->assertArrayHasKey('updated_at', $data);
        $this->assertArrayHasKey('created_by', $data);
    }

    public function test_get_an_unknown_role_must_return_404()
    {
        $school = School::factory()->create();
        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/roles/123e4567-e89b-12d3-a456-426614174000");

        $response->assertStatus(404);
    }

    public function test_get_a_role_from_not_his_belonging_school_must_return_404()
    {
        $school1 = School::factory()->create();
        $school2 = School::factory()->create();
        $role = Role::factory()->create(['school_id' => $school1->id]);
        $response = $this->getJson($this->getEndPoint() . "schools/$school2->id/roles/$role->id");

        $response->assertStatus(404);
    }

    public function test_post_role_without_body_must_return_422_with_the_list_of_errors()
    {
        $school = School::factory()->create();
        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/roles");
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                ],
            ]);
    }

    public function test_post_role_without_errors_must_return_201_and_the_role_is_created()
    {
        $school = School::factory()->create();
        $roleToCreate = [
            'name' => 'role_name',
            'comment' => 'role_comment',
        ];
        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/roles", $roleToCreate);
        $response->assertStatus(201);

        $createdRoleId = json_decode($response->getContent(), true)['data']['id'];
        $createdRole = role::find($createdRoleId);

        $this->assertRoleBdd($createdRole, $roleToCreate);
    }

    public function test_put_role_without_errors_must_return_200_and_the_role_is_updated()
    {
        $school = School::factory()->create();
        $role = Role::factory()->create(['school_id' => $school->id]);

        $roleToModify = [
            'name' => 'role_name_modified',
            'comment' => 'role_comment_modified',
        ];
        $response = $this->putJson($this->getEndPoint() . "schools/$school->id/roles/$role->id", $roleToModify);
        $response->assertStatus(200);

        $modifiedRole = role::find($role->id);

        $this->assertRoleBdd($modifiedRole, $roleToModify);
    }

    public function test_delete_unknown_role_must_return_404()
    {
        $school = School::factory()->create();
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/roles/unknown");
        $response->assertStatus(404);
    }

    public function test_delete_role_must_return_204_and_the_role_is_deleted()
    {
        $school = School::factory()->create();
        $role = Role::factory()->create(['school_id' => $school->id]);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/roles/$role->id");
        $response->assertStatus(204);

        $roleDeleted = Role::find($role->id);
        $this->assertNull($roleDeleted);
    }

    public function assertRoleBdd(Role $role, array $data)
    {
        $this->assertEquals($data['name'], $role->name);
        $this->assertEquals($data['comment'], $role->comment);
        $this->assertNotNull($role->created_at);
        $this->assertNotNull($role->created_by);
    }
}
