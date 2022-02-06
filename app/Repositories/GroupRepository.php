<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\School;
use App\Models\Group;
use Spatie\QueryBuilder\QueryBuilder;

class GroupRepository
{
    public function index(School $school)
    {
        return QueryBuilder::for(Group::class)
            ->allowedFilters(['name'])
            ->allowedSorts('name')
            ->allowedFields(['id', 'name', 'address1', 'address2', 'address3', 'zip-code', 'city', 'country_id', 'comment', 'status'])
            ->where('school_id', $school->id)
            ->paginate(10);
    }

    public function get(string $schoolId): School
    {
        return QueryBuilder::for(School::class)
            ->allowedFields(['id', 'name', 'address1', 'address2', 'address3', 'zip-code', 'city', 'country_id', 'comment', 'status', 'max_users'])
            ->find($schoolId);
    }

    public function update(Group $group, array $data): Group
    {
        $group->fill($data);
        $group->save();

        return $group;
    }

    public function delete(Group $group): void
    {
        $group->delete();
    }

    public function insert(School $school, array $data): Group
    {
        $group = new Group();

        $group->school_id = $school->id;
        $group->fill($data);
        $group->save();

        return $group;
    }
}
