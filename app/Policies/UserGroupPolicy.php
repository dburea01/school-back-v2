<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserGroupPolicy
{
    use HandlesAuthorization;

    protected $school;

    public function __construct()
    {
        $this->school = request()->route()->parameter('school');
        $this->group = request()->route()->parameter('group');
    }

    public function before(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }





    public function create(User $user)
    {
        return
            $user->isDirector()
            && $user->school_id === $this->school->id
            && $this->group->school_id === $user->school->id;
    }



    public function delete(User $user)
    {
        return
            $user->isDirector()
            && $user->school_id === $this->school->id
            && $this->group->school_id === $user->school->id;
    }
}
