<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Repositories\SchoolRepository;

class SchoolController extends Controller {

    private $schoolRepository;

    public function __construct(SchoolRepository $schoolRepository) {
        $this->schoolRepository = $schoolRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $schools = $this->schoolRepository->index();

        return SchoolResource::collection($schools);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSchoolRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSchoolRequest $request) {
        try {
            $school = $this->schoolRepository->insert($request->all());

            return new SchoolResource($school);
        } catch (\Throwable $t) {
            return response()->json('bad request :' . $t->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school) {
        return new SchoolResource($this->schoolRepository->get($school->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSchoolRequest  $request
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSchoolRequest $request, School $school) {
        try {
            $updatedSchool = $this->schoolRepository->update($school, $request->all());

            return new SchoolResource($updatedSchool);
        } catch (\Throwable $e) {
            return response()->json('Bad request : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school) {
        try {
            $this->schoolRepository->delete($school);

            return response()->noContent();
        } catch (\Throwable $t) {
            return response()->json('Bad request : ' . $t->getMessage());
        }
    }

}
