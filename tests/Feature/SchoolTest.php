<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolTest extends TestCase
{
    use RefreshDatabase;
    use Request;

    const RESOURCE = 'schools';

    public function test_get_schools()
    {
        School::factory()->count(3)->create();
        $response = $this->getJson($this->getEndPoint() . self::RESOURCE);

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(3, count($data));
    }

    public function test_get_schools_filter_by_name_without_response()
    {
        School::factory()->count(3)->create();

        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '?filter[name]=Test');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(0, count($data));
    }

    public function test_get_schools_filter_by_name_with_response()
    {
        School::factory()->count(3)->create();
        School::factory()->create(['name' => 'Test']);
        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '?filter[name]=Test');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(1, count($data));
    }

    public function test_get_schools_sorted_by_name()
    {
        $schoolA = School::factory()->create(['name' => 'a']);
        $schoolB = School::factory()->create(['name' => 'b']);

        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '?sort=name');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals($schoolA->name, $data[0]['name']);
        $this->assertEquals($schoolB->name, $data[1]['name']);
    }

    public function test_get_schools_sorted_by_name_desc()
    {
        $schoolA = School::factory()->create(['name' => 'a']);
        $schoolB = School::factory()->create(['name' => 'b']);

        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '?sort=-name');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals($schoolB->name, $data[0]['name']);
        $this->assertEquals($schoolA->name, $data[1]['name']);
    }

    public function test_get_schools_sorted_by_max_users_desc()
    {
        $schoolA = School::factory()->create(['max_users' => '1']);
        $schoolB = School::factory()->create(['max_users' => '2']);

        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '?sort=-max_users');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals($schoolB->max_users, $data[0]['max_users']);
        $this->assertEquals($schoolA->max_users, $data[1]['max_users']);
    }

    public function test_get_schools_some_fields()
    {
        School::factory()->count(3)->create();
        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '?fields=id,name');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                        ],
                    ],
        ]);
    }

    public function test_get_school()
    {
        $school = School::factory()->create();
        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '/' . $school->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
        ]);
    }

    public function test_get_school_with_not_uuid_must_return_404()
    {
        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '/123');
        $response->assertStatus(404);
    }

    /** a comment */
    public function test_get_school_with_an_unknown_school_must_return_404()
    {
        $response = $this->getJson($this->getEndPoint() . self::RESOURCE . '/123e4567-e89b-12d3-a456-426614174000');
        $response->assertStatus(404);
    }

    public function test_post_school_without_body_must_return_422_with_the_list_of_errors()
    {
        $response = $this->postJson($this->getEndPoint() . self::RESOURCE);
        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'name',
                        'address1',
                        'city',
                        'country_id',
                        'zip_code',
                        'status',
                        'max_users',
                    ],
        ]);
    }

    public function test_post_school_without_errors_must_return_201_and_the_school_is_created()
    {
        $data = [
            'name' => 'School test',
            'address1' => 'address1',
            'address2' => 'address2',
            'address3' => 'address3',
            'zip_code' => 'zip_code',
            'city' => 'city',
            'country_id' => 'FR',
            'max_users' => '123',
            'status' => 'ACTIVE',
        ];
        $response = $this->postJson($this->getEndPoint() . self::RESOURCE, $data);
        $response->assertStatus(201);

        $createdSchoolId = json_decode($response->getContent(), true)['data']['id'];
        $createdSchool = School::find($createdSchoolId);

        $this->assertSchoolBdd($createdSchool, $data);
    }

    public function test_put_unknown_school_must_return_404()
    {
        $response = $this->putJson($this->getEndPoint() . self::RESOURCE . '/123e4567-e89b-12d3-a456-426614174000');
        $response->assertStatus(404);
    }

    public function test_put_school_with_an_error_on_the_country_must_return_422_with_the_list_of_errors()
    {
        $data = [
            'country_id' => 'AZERTY',
        ];

        $school = School::factory()->create();
        $response = $this->putJson($this->getEndPoint() . self::RESOURCE . '/' . $school->id, $data);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'message',
                    'errors' => [
                        'country_id',
                    ],
        ]);
    }

    public function test_put_school_without_errors_must_return_200_and_the_school_is_updated()
    {
        $data = [
            'name' => 'School test updated',
            'address1' => 'address1 updated',
            'address2' => 'address2 updated',
            'address3' => 'address3 updated',
            'zip_code' => 'zip_code updated',
            'city' => 'city updated',
            'country_id' => 'BE',
            'max_users' => '1234',
            'status' => 'INACTIVE',
        ];

        $school = School::factory()->create();
        $response = $this->putJson($this->getEndPoint() . self::RESOURCE . '/' . $school->id, $data);

        $response->assertStatus(200);

        $updatedSchoolId = json_decode($response->getContent(), true)['data']['id'];
        $updatedSchool = School::find($updatedSchoolId);

        $this->assertSchoolBdd($updatedSchool, $data);
    }

    public function test_delete_unknown_school_must_return_404()
    {
        $response = $this->deleteJson($this->getEndPoint() . self::RESOURCE . '/unknown');
        $response->assertStatus(404);
    }

    public function test_delete_school_must_return_204_and_the_school_is_deleted()
    {
        $school = School::factory()->create();
        $response = $this->deleteJson($this->getEndPoint() . self::RESOURCE . '/' . $school->id);
        $response->assertStatus(204);

        $schoolDeleted = School::find($school->id);
        $this->assertNull($schoolDeleted);
    }

    public function assertSchoolBdd(School $school, array $data)
    {
        $this->assertEquals($data['name'], $school->name);
        $this->assertEquals($data['address1'], $school->address1);
        $this->assertEquals($data['address2'], $school->address2);
        $this->assertEquals($data['address3'], $school->address3);
        $this->assertEquals($data['zip_code'], $school->zip_code);
        $this->assertEquals($data['city'], $school->city);
        $this->assertEquals($data['country_id'], $school->country_id);
        $this->assertEquals($data['max_users'], $school->max_users);
        $this->assertEquals($data['status'], $school->status);
        $this->assertNotNull($school->created_at);
        $this->assertNotNull($school->created_by);
    }
}
