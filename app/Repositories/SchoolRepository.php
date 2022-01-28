<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\School;
use Spatie\QueryBuilder\QueryBuilder;

class SchoolRepository
{
    public function all()
    {
        $schools = QueryBuilder::for(School::class)
        ->allowedFilters(['name'])
        ->allowedSorts('name', 'max_users')
        ->allowedFields(['id', 'name', 'address1', 'address2', 'address3', 'zip-code', 'city', 'country_id', 'comment', 'status', 'max_users'])
        ->get();

        return $schools;
    }

    public function get($schoolId, array $request): void
    {
    }

    public function update($schoolId, array $schoolData)
    {
        $school = School::find($schoolId);
        $school->fill($schoolData);
        $school->save();

        return $school;
    }

    public function destroy($schoolId): void
    {
        School::destroy($schoolId);
    }

    public function insert($schoolData)
    {
        $school = new School();
        $school->fill($schoolData);
        $school->save();

        return $school;
    }
}
