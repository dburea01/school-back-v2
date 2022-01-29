<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\School;
use Spatie\QueryBuilder\QueryBuilder;

class SchoolRepository {

    public function index() {
        return QueryBuilder::for(School::class)
                        ->allowedFilters(['name'])
                        ->allowedSorts('name', 'max_users')
                        ->allowedFields(['id', 'name', 'address1', 'address2', 'address3', 'zip-code', 'city', 'country_id', 'comment', 'status', 'max_users'])
                        ->get();
    }

    public function get(string $schoolId): School {
        return QueryBuilder::for(School::class)
                        ->allowedFields(['id', 'name', 'address1', 'address2', 'address3', 'zip-code', 'city', 'country_id', 'comment', 'status', 'max_users'])
                        ->find($schoolId);
    }

    public function update(School $school, array $schoolData) {
        $school->fill($schoolData);
        $school->save();

        return $school;
    }

    public function delete(School $school): void {
        $school->delete();
    }

    public function insert(array $data) {
        $school = new School();
        $school->fill($data);
        $school->save();

        return $school;
    }

}
