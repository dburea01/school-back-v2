<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Role;
use App\Models\School;

class RoleRepository
{


    public function update(Role $role, array $data): Role
    {
        $role->fill($data);
        $role->save();

        return $role;
    }

    public function delete(Role $role): void
    {
        $role->delete();
    }

    public function insert(School $school, array $data): Role
    {
        $role = new Role();
        $role->fill($data);
        $role->school_id = $school->id;
        $role->save();

        return $role;
    }
}
