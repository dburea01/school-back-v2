<?php

namespace Tests\Feature;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use Request;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createSchoolAndUserWithRole('SUPERADMIN'));
    }

    public function test_get_users_of_a_school()
    {
        $school = School::factory()->create();
        User::factory()->count(17)->create(['school_id' => $school->id, 'role_id' => 'TEACHER']);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/users");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(10, count($data));
    }

    public function test_get_users_of_a_school_filtered_by_role()
    {
        $school = School::factory()->create();
        User::factory()->count(5)->create(['school_id' => $school->id, 'role_id' => 'TEACHER']);
        User::factory()->count(15)->create(['school_id' => $school->id, 'role_id' => 'STUDENT']);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/users?filter[role_id]=TEACHER");

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(5, count($data));
    }

    public function test_get_users_of_a_school_filtered_by_last_name()
    {
        $school = School::factory()->create();
        $userA = User::factory()->create(['school_id' => $school->id, 'role_id' => 'TEACHER', 'last_name' => 'userA']);
        $userB = User::factory()->create(['school_id' => $school->id, 'role_id' => 'TEACHER', 'last_name' => 'userB']);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/users?sort=last_name");

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(2, count($data));
        $this->assertEquals('USERA', $data[0]['last_name']);
        $this->assertEquals('USERB', $data[1]['last_name']);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/users?sort=-last_name");

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(2, count($data));
        $this->assertEquals('USERA', $data[1]['last_name']);
        $this->assertEquals('USERB', $data[0]['last_name']);
    }

    public function test_get_user_of_a_school()
    {
        $school = School::factory()->create();

        $userToInsertInBdd = [
            'school_id' => $school->id,
            'last_name' => 'last name',
            'first_name' => 'first name',
            'role_id' => 'STUDENT',
            'address1' => 'address1',
            'address2' => 'address2',
            'address3' => 'address3',
            'zip_code' => 'zip_code',
            'city' => 'city',
            'country_id' => 'FR',
            'status' => 'INACTIVE',
        ];

        $userInsertedInBdd = User::factory()->create($userToInsertInBdd);

        $response = $this->getJson($this->getEndPoint() . "schools/$school->id/users/$userInsertedInBdd->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
            ]);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertUserBdd($userInsertedInBdd, $data);
    }

    public function test_get_user_not_belonging_to_school_must_return_404()
    {
        $schoolA = School::factory()->create();
        $userA = User::factory()->create(['school_id' => $schoolA->id]);

        $schoolB = School::factory()->create();
        $userB = User::factory()->create(['school_id' => $schoolB->id]);

        $response = $this->getJson($this->getEndPoint() . "schools/$schoolA->id/users/$userB->id");
        $response->assertStatus(404);
    }

    public function test_post_user_without_body_must_return_422_with_the_list_of_errors()
    {
        $school = School::factory()->create();
        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/users");
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'role_id',
                    'last_name',
                    'first_name',
                    'status',
                ],
            ]);
    }

    public function test_post_user_without_errors_must_return_201_and_the_user_is_created()
    {
        $school = School::factory()->create();
        $userToPost = [
            'school_id' => $school->id,
            'last_name' => 'last name',
            'first_name' => 'first name',
            'role_id' => 'STUDENT',
            'address1' => 'address1',
            'address2' => 'address2',
            'address3' => 'address3',
            'zip_code' => 'zip_code',
            'city' => 'city',
            'country_id' => 'FR',
            'status' => 'INACTIVE',
            'birth_date' => '18/10/1992',
            'gender_id' => '1',
        ];

        $response = $this->postJson($this->getEndPoint() . "schools/$school->id/users", $userToPost);
        $response->assertStatus(201);

        $data = json_decode($response->getContent(), true)['data'];
        $userCreated = User::find($data['id']);
        $this->assertUserBdd($userCreated, $data);
    }

    public function test_put_user_without_errors_must_return_201_and_the_user_is_updated()
    {
        $school = School::factory()->create();
        $user = User::factory()->create(['school_id' => $school->id]);
        $userToPut = [
            'last_name' => 'last name modified',
            'first_name' => 'first name modified',
            'school_id' => $school->id,
            'role_id' => 'STUDENT',
            'address1' => 'address1 modified',
            'address2' => 'address2 modified',
            'address3' => 'address3 modified',
            'zip_code' => 'zip_code modified',
            'city' => 'city modified',
            'country_id' => 'BE',
            'status' => 'INACTIVE',
            'birth_date' => '27/11/1997',
            'gender_id' => '2',
        ];

        $response = $this->putJson($this->getEndPoint() . "schools/$school->id/users/$user->id", $userToPut);
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $userUpdated = User::find($data['id']);
        $this->assertUserBdd($userUpdated, $userToPut);
    }

    public function test_put_unknown_user_must_return_404()
    {
        $school = School::factory()->create();
        $response = $this->putJson($this->getEndPoint() . "schools/$school->id/users/07b530be-1ae2-4711-8240-024bbfa99230");
        $response->assertStatus(404);
    }

    public function test_delete_user_must_return_204_and_the_user_is_deleted()
    {
        $school = School::factory()->create();
        $user = User::factory()->create(['school_id' => $school->id]);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/users/$user->id");
        $response->assertStatus(204);

        $userDeleted = User::find($user->id);
        $this->assertNull($userDeleted);
    }

    public function test_delete_unknown_user_must_return_404()
    {
        $school = School::factory()->create();
        $response = $this->deleteJson($this->getEndPoint() . "schools/$school->id/users/07b530be-1ae2-4711-8240-024bbfa99230");
        $response->assertStatus(404);
    }

    public function assertUserBdd(User $user, array $data)
    {
        $this->assertEquals(strtoupper($data['last_name']), $user->last_name);
        $this->assertEquals(ucwords($data['first_name']), $user->first_name);
        $this->assertEquals($data['school_id'], $user->school_id);
        $this->assertEquals($data['role_id'], $user->role_id);
        $this->assertEquals($data['address1'], $user->address1);
        $this->assertEquals($data['address2'], $user->address2);
        $this->assertEquals($data['address3'], $user->address3);
        $this->assertEquals($data['zip_code'], $user->zip_code);
        $this->assertEquals($data['city'], $user->city);
        $this->assertEquals($data['country_id'], $user->country_id);
        $this->assertEquals($data['status'], $user->status);
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->created_by);
    }
}
