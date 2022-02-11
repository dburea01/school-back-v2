<?php

declare(strict_types=1);

namespace App\Repositories;


use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;

class UserGroupRepository
{


    public function delete(UserGroup $userGroup): void
    {

        $userGroup->delete();
    }

    public function insert(Group $group, String $userId): UserGroup
    {
        $userGroup = new UserGroup();

        $userGroup->group_id = $group->id;
        $userGroup->user_id = $userId;
        $userGroup->save();

        return $userGroup;
    }
}
