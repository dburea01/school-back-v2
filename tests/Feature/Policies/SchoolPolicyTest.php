<?php

namespace tests\Feature\Policies;

use App\Models\School;
use Tests\TestCase;
use Tests\Feature\Request;

class SchoolPolicyTest extends TestCase
{
    use Request;

    public function test_only_the_superadmin_can_see_the_schools()
    {
        $this->actingAs($this->createSchoolAndUserWithRole('SUPERADMIN'));
        $response = $this->getJson($this->getEndPoint() . 'schools');
        $response->assertStatus(200);

        $this->actingAs($this->createSchoolAndUserWithRole('STUDENT'));
        $response = $this->getJson($this->getEndPoint() . 'schools');
        $response->assertStatus(403);
    }

    public function test_only_the_superadmin_and_the_director_can_see_a_school()
    {
        $superAdmin = $this->createSchoolAndUserWithRole('SUPERADMIN');
        $this->actingAs($superAdmin);
        $response = $this->getJson($this->getEndPoint() . "schools/$superAdmin->school_id");
        $response->assertStatus(200);

        $director = $this->createSchoolAndUserWithRole('DIRECTOR');
        $this->actingAs($director);
        $response = $this->getJson($this->getEndPoint() . "schools/$director->school_id");
        $response->assertStatus(200);

        $student = $this->createSchoolAndUserWithRole('STUDENT');
        $this->actingAs($student);
        $response = $this->getJson($this->getEndPoint() . "schools/$student->school_id");
        $response->assertStatus(403);
    }

    public function test_only_the_superadmin_can_create_a_school()
    {
        $superAdmin = $this->createSchoolAndUserWithRole('SUPERADMIN');
        $this->actingAs($superAdmin);
        $response = $this->postJson($this->getEndPoint() . 'schools');
        $response->assertStatus(422);

        $director = $this->createSchoolAndUserWithRole('DIRECTOR');
        $this->actingAs($director);
        $response = $this->postJson($this->getEndPoint() . 'schools');
        $response->assertStatus(403);
    }

    public function test_a_director_can_update_only_its_school()
    {
        $director = $this->createSchoolAndUserWithRole('DIRECTOR');
        $this->actingAs($director);
        $response = $this->putJson($this->getEndPoint() . "schools/$director->school_id");
        $response->assertStatus(200);

        $anotherSchool = School::factory()->create();
        $response = $this->putJson($this->getEndPoint() . "schools/$anotherSchool->id");
        $response->assertStatus(403);
    }

    public function test_only_the_superadmin_can_delete_a_school()
    {
        $superAdmin = $this->createSchoolAndUserWithRole('SUPERADMIN');
        $this->actingAs($superAdmin);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$superAdmin->school_id");
        $response->assertStatus(204);

        $director = $this->createSchoolAndUserWithRole('DIRECTOR');
        $this->actingAs($director);
        $response = $this->deleteJson($this->getEndPoint() . "schools/$director->school_id");
        $response->assertStatus(403);
    }
}
