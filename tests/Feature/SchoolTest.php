<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_schools()
    {
        School::factory(3)->create();
        $response = $this->get('/api/v1/schools');

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(3, count($data));
    }

    public function test_get_schools_filter_by_name_without_response()
    {
        School::factory(3)->create();

        $response = $this->get('/api/v1/schools?filter[name]=Test');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(0, count($data));
    }

    public function test_get_schools_filter_by_name_with_response()
    {
        School::factory(3)->create();
        School::factory(1)->create(['name' => 'Test']);
        $response = $this->get('/api/v1/schools?filter[name]=Test');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(1, count($data));
    }

    public function test_get_schools_sorted_by_name()
    {
        $schoolA = School::factory(1)->create(['name' => 'a']);
        $schoolB = School::factory(1)->create(['name' => 'b']);

        $response = $this->get('/api/v1/schools?sort=name');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals('a', $data[0]['name']);
        $this->assertEquals('b', $data[1]['name']);
    }

    public function test_get_schools_sorted_by_name_desc()
    {
        $schoolA = School::factory(1)->create(['name' => 'a']);
        $schoolB = School::factory(1)->create(['name' => 'b']);

        $response = $this->get('/api/v1/schools?sort=-name');
        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true)['data'];
        $this->assertEquals('b', $data[0]['name']);
        $this->assertEquals('a', $data[1]['name']);
    }

    public function test_get_schools_some_fields()
    {
        School::factory(3)->create();
        $response = $this->get('/api/v1/schools?fields=id,name');

        $response->assertStatus(200)
                ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'namee',
                    ],
                ],
            ]);
    }
}
