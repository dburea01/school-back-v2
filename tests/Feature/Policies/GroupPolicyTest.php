<?php

namespace tests\Feature\Policies;

use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Request;

class GroupPolicyTest extends TestCase
{
    use RefreshDatabase;
    use Request;

    public function test_only_the_superadmin_can_see_the_groups_of_any_school()
    {
        $school1 = School::factory()->create();
        $school2 = School::factory()->create();

        $superAdmin = $this->createSchoolAndUserWithRole('SUPERADMIN');
        $this->actingAs($superAdmin);

        $response = $this->getJson($this->getEndPoint() . "schools/$school1->id/groups");
        $response->assertStatus(200);
        $response = $this->getJson($this->getEndPoint() . "schools/$school2->id/groups");
        $response->assertStatus(200);

        $director = $this->createSchoolAndUserWithRole('DIRECTOR');
        $this->actingAs($director);
        $response = $this->getJson($this->getEndPoint() . "schools/$school1->id/groups");
        $response->assertStatus(403);
        $response = $this->getJson($this->getEndPoint() . "schools/$director->school_id/groups");
        $response->assertStatus(200);
    }
}
