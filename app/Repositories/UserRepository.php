<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\School;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository
{
    public function index(School $school)
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters(['first_name', 'last_name', 'role_id'])
            ->allowedSorts('last_name')
            ->allowedFields(['id', 'first_name', 'last_name', 'role_id', 'address1', 'address2', 'address3', 'zip-code', 'city', 'country_id', 'comment', 'status'])
            ->where('school_id', $school->id)
            ->paginate(10);
    }

    public function get(string $schoolId): School
    {
        return QueryBuilder::for(School::class)
            ->allowedFields(['id', 'name', 'address1', 'address2', 'address3', 'zip-code', 'city', 'country_id', 'comment', 'status', 'max_users'])
            ->find($schoolId);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function insert(School $school, array $data): User
    {
        $user = new User();

        $user->school_id = $school->id;
        $user->fill($data);
        $user->save();

        return $user;
    }
}
