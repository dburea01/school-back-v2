<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\School;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(School $school)
    {
        $users = $this->userRepository->index($school);
        return UserResource::collection($users);
    }

    public function store(School $school, StoreUserRequest $request)
    {
        try {
            $user = $this->userRepository->insert($school, $request->all());

            return new UserResource($user);
        } catch (\Throwable $th) {
            return response()->json('bad request :' . $th->getMessage(), 400);
        }
    }

    public function show(School $school, User $user)
    {

        return new UserResource($user);
    }

    public function update(StoreUserRequest $request, School $school, User $user)
    {
        try {
            $userUpdated = $this->userRepository->update($user, $request->all());

            return new UserResource($userUpdated);
        } catch (\Throwable $th) {
            return response()->json('bad request :' . $th->getMessage(), 400);
        }
    }

    public function destroy(School $school, User $user)
    {
        try {
            $this->userRepository->delete($user);

            return response()->noContent();
        } catch (\Throwable $th) {
            return response()->json('bad request :' . $th->getMessage(), 400);
        }
    }
}
