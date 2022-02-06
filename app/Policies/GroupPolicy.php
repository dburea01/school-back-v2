<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;
    protected $school;

    public function __construct()
    {
        $this->school = request()->route()->parameter('school');
    }

    public function before(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->isDirector() && $user->school_id === $this->school->id;
    }

    public function view(User $user, Group $group)
    {
        return $user->isDirector() && $user->school_id === $group->school_id;
    }

    public function create(User $user)
    {
        return $user->isDirector() && $user->school_id === $this->school->id;
    }

    public function update(User $user, Group $group)
    {
        return $user->isDirector() && $user->school_id === $group->school_id;
    }

    public function delete(User $user, Group $group)
    {
        return $user->isDirector() && $user->school_id === $group->school_id;
    }
}
