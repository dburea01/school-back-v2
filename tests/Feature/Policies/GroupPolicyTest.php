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

    public function test_only_the_director_can_see_the_groups_of_his_school()
    {
        $school1 = School::factory()->create();

        $director = $this->createSchoolAndUserWithRole('DIRECTOR');
        $this->actingAs($director);
        $response = $this->getJson($this->getEndPoint() . "schools/$school1->id/groups");
        $response->assertStatus(403);
        $response = $this->getJson($this->getEndPoint() . "schools/$director->school_id/groups");
        $response->assertStatus(200);

        $parent = $this->createSchoolAndUserWithRole('PARENT');
        $this->actingAs($parent);
        $response = $this->getJson($this->getEndPoint() . "schools/$school1->id/groups");
        $response->assertStatus(403);
        $response = $this->getJson($this->getEndPoint() . "schools/$parent->school_id/groups");
        $response->assertStatus(403);
    }
}
