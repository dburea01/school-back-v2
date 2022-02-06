<?php

namespace tests\Feature;

use App\Models\School;
use App\Models\User;

trait Request
{
    public function getEndPoint(): string
    {
        return '/api/v1/';
    }

    public function getHeaders(string $token): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
    }

    public function createSchoolAndUserWithRole(string $roleId): User
    {
        $school = School::factory()->create();
        return User::factory()->create([
            'school_id' => $school->id,
            'role_id' => $roleId,
        ]);
    }
}
