<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Models\School;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(School $school)
    {
        return RoleResource::collection(Role::where('school_id', $school->id)->get());
    }

    public function store(School $school, StoreRoleRequest $request)
    {
        try {
            $role = $this->roleRepository->insert($school, $request->all());

            return new RoleResource($role);
        } catch (\Throwable $th) {
            return response()->json('bad request :' . $th->getMessage(), 400);
        }
    }

    public function show(School $school, Role $role)
    {
        return new RoleResource($role);
    }

    public function update(Request $request, School $school, Role $role)
    {
        try {
            $role = $this->roleRepository->update($role, $request->all());

            return new RoleResource($role);
        } catch (\Throwable $th) {
            return response()->json('bad request :' . $th->getMessage(), 400);
        }
    }

    public function destroy(School $school, Role $role)
    {
        try {
            $role = $this->roleRepository->delete($role);

            return response()->noContent();
        } catch (\Throwable $th) {
            return response()->json('bad request :' . $th->getMessage(), 400);
        }
    }
}
