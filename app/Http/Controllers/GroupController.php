<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\School;
use App\Repositories\GroupRepository;

class GroupController extends Controller
{
    protected $GroupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->GroupRepository = $groupRepository;
        $this->authorizeResource(Group::class);
    }

    public function index(School $school)
    {
        $groups = $this->GroupRepository->index($school);
        return GroupResource::collection($groups);
    }

    public function store(School $school, StoreGroupRequest $request)
    {
        try {
            $group = $this->GroupRepository->insert($school, $request->all());
            return new GroupResource($group);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }
    }

    public function show(School $school, Group $group)
    {
        return new GroupResource($group);
    }

    public function update(School $school, Group $group, StoreGroupRequest $request)
    {
        try {
            $groupModified = $this->GroupRepository->update($group, $request->all());
            return new GroupResource($groupModified);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }
    }

    public function destroy(School $school, Group $group)
    {
        try {
            $this->GroupRepository->delete($group);
            return response()->noContent();
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 400);
        }
    }
}
