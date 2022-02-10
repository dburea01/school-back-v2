<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Repositories\SchoolRepository;

class SchoolController extends Controller
{
    private $schoolRepository;

    public function __construct(SchoolRepository $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
        $this->authorizeResource(School::class);
    }




    public function index()
    {
        $schools = $this->schoolRepository->index();

        return SchoolResource::collection($schools);
    }

    public function store(StoreSchoolRequest $request)
    {
        try {
            $school = $this->schoolRepository->insert($request->all());

            return new SchoolResource($school);
        } catch (\Throwable $t) {
            return response()->json('bad request :' . $t->getMessage(), 400);
        }
    }

    public function show(School $school)
    {
        return new SchoolResource($school);
    }

    public function update(UpdateSchoolRequest $request, School $school)
    {
        try {
            $updatedSchool = $this->schoolRepository->update($school, $request->all());

            return new SchoolResource($updatedSchool);
        } catch (\Throwable $e) {
            return response()->json('Bad request : ' . $e->getMessage());
        }
    }

    public function destroy(School $school)
    {
        try {
            $this->schoolRepository->delete($school);

            return response()->noContent();
        } catch (\Throwable $t) {
            return response()->json('Bad request : ' . $t->getMessage());
        }
    }
}
