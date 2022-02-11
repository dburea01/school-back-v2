<?php

namespace App\Http\Controllers;

use App\Models\UserGroup;
use App\Http\Requests\StoreUserGroupRequest;
use App\Http\Resources\UserGroupResource;
use App\Models\Group;
use App\Models\School;
use App\Repositories\UserGroupRepository;

class UserGroupController extends Controller
{
    private $userGroupRepository;

    public function __construct(UserGroupRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->authorizeResource(UserGroup::class);
    }

    public function store(School $school, Group $group, StoreUserGroupRequest $request)
    {
        try {
            $userGroup = $this->userGroupRepository->insert($group, $request->user_id);
            return new UserGroupResource($userGroup);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }
    }

    public function destroy(School $school, Group $group, UserGroup $userGroup)
    {


        try {
            $this->userGroupRepository->delete($userGroup);
            return response()->noContent();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }
    }
}
