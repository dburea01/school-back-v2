<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;
    use Request;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createSchoolAndUserWithRole('SUPERADMIN'));
    }

    public function test_get_groups_of_a_school()
    {
        $school = School::factory()->create();
        Group::factory()->count(17)->create(['school_id' => $school->id]);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/groups");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(10, count($data));
    }

    public function test_get_groups_of_a_school_filtered_by_name()
    {
        $groupName = 'groupA';
        $school = School::factory()->create();
        Group::factory()->create(['school_id' => $school->id, 'name' => $groupName]);
        Group::factory()->count(15)->create(['school_id' => $school->id]);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/groups?filter[name]=groupA");

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0]['name'], strtoupper($groupName));
    }

    public function test_get_group_of_a_school()
    {
        $school = School::factory()->create();

        $groupToInsertInBdd = [
            'school_id' => $school->id,
            'name' => 'group name',
            'address1' => 'address1',
            'address2' => 'address2',
            'address3' => 'address3',
            'zip_code' => 'zip_code',
            'city' => 'city',
            'country_id' => 'FR',
            'status' => 'INACTIVE',
            'comment' => 'comment'
        ];

        $groupInsertedInBdd = Group::factory()->create($groupToInsertInBdd);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/groups/$groupInsertedInBdd->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
            ]);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertGroupBdd($groupInsertedInBdd, $data);
    }

    public function test_get_group_not_belonging_to_school_must_return_404()
    {
        $schoolA = School::factory()->create();
        Group::factory()->create(['school_id' => $schoolA->id]);

        $schoolB = School::factory()->create();
        $userB = Group::factory()->create(['school_id' => $schoolB->id]);

        $response = $this->getJson($this->getEndPoint() . "schools/$schoolA->id/groups/$userB->id");
        $response->assertStatus(404);
    }

    public function test_post_group_without_body_must_return_422_with_the_list_of_errors()
    {
        $school = School::factory()->create();
        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups");
        $response->assertStatus(422)
            ->assertInvalid([
                'name',
                'address1',
                'zip_code',
                'city',
                'country_id',
                'status'
            ]);
    }

    public function test_post_group_without_errors_must_return_201_and_the_group_is_created()
    {
        $school = School::factory()->create();
        $groupToPost = [
            'school_id' => $school->id,
            'name' => 'group name',
            'address1' => 'address1',
            'address2' => 'address2',
            'address3' => 'address3',
            'zip_code' => 'zip_code',
            'city' => 'city',
            'country_id' => 'FR',
            'status' => 'INACTIVE',
            'comment' => 'comment'
        ];

        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/groups", $groupToPost);
        $response->assertStatus(201);

        $data = json_decode($response->getContent(), true)['data'];
        $groupCreated = Group::find($data['id']);
        $this->assertGroupBdd($groupCreated, $data);
    }

    public function test_put_group_without_errors_must_return_201_and_the_group_is_updated()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        $groupToPut = [
            'name' => 'name modified',
            'school_id' => $school->id,
            'address1' => 'address1 modified',
            'address2' => 'address2 modified',
            'address3' => 'address3 modified',
            'zip_code' => 'zip_code modified',
            'city' => 'city modified',
            'country_id' => 'BE',
            'status' => 'INACTIVE',
            'comment' => 'comment modified',
        ];

        $response = $this->putJson($this->getEndPoint() . "schools/$school->id/groups/$group->id", $groupToPut);
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $groupUpdated = Group::find($data['id']);
        $this->assertGroupBdd($groupUpdated, $groupToPut);
    }

    public function test_put_unknown_group_must_return_404()
    {
        $school = School::factory()->create();
        $response = $this->putJson($this->getEndPoint() . "schools/$school->id/groups/07b530be-1ae2-4711-8240-024bbfa99230");
        $response->assertStatus(404);
    }

    public function test_delete_group_must_return_204_and_the_group_is_deleted()
    {
        $school = School::factory()->create();
        $group = Group::factory()->create(['school_id' => $school->id]);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/groups/$group->id");
        $response->assertStatus(204);

        $groupDeleted = Group::find($group->id);
        $this->assertNull($groupDeleted);
    }

    public function test_delete_unknown_group_must_return_404()
    {
        $school = School::factory()->create();
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/groups/07b530be-1ae2-4711-8240-024bbfa99230");
        $response->assertStatus(404);
    }

    public function assertGroupBdd(Group $group, array $data)
    {
        $this->assertEquals(strtoupper($data['name']), $group->name);
        $this->assertEquals($data['school_id'], $group->school_id);
        $this->assertEquals($data['address1'], $group->address1);
        $this->assertEquals($data['address2'], $group->address2);
        $this->assertEquals($data['address3'], $group->address3);
        $this->assertEquals($data['zip_code'], $group->zip_code);
        $this->assertEquals($data['city'], $group->city);
        $this->assertEquals($data['country_id'], $group->country_id);
        $this->assertEquals($data['status'], $group->status);
        $this->assertEquals($data['comment'], $group->comment);
        $this->assertNotNull($group->created_at);
        $this->assertNotNull($group->created_by);
    }
}
