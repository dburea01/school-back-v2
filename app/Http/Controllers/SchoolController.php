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

    /**
     * @OA\Get(
     *     path="/schools",
     *     operationId="getSchoolsList",
     *     tags={"Schools"},
     *     summary="Get list of schools",
     *     description="Returns list of schools.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="filter by school name",
     *         required=false
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="filter by school city",
     *         required=false
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="sort by ...",
     *         required=false
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/SchoolResource")
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index()
    {
        $schools = $this->schoolRepository->index();

        return SchoolResource::collection($schools);
    }

    /**
     * @OA\Post(
     *      path="/schools",
     *      operationId="storeSchool",
     *      tags={"Schools"},
     *      summary="Store new school",
     *      description="Returns school data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreSchoolRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SchoolResource")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function store(StoreSchoolRequest $request)
    {
        try {
            $school = $this->schoolRepository->insert($request->all());

            return new SchoolResource($school);
        } catch (\Throwable $t) {
            return response()->json('bad request :' . $t->getMessage(), 400);
        }
    }

    /**
     * @OA\Get(
     *      path="/schools/{id}",
     *      operationId="getSchoolById",
     *      tags={"Schools"},
     *      summary="Get school information",
     *      description="Returns project data",
     *      @OA\Parameter(
     *          name="id",
     *          description="School id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SchoolResource")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function show(School $school)
    {
        return new SchoolResource($school);
    }

    /**
     * @OA\Put(
     *      path="/schools/{id}",
     *      operationId="updateSchool",
     *      tags={"Schools"},
     *      summary="Update existing school",
     *      description="Returns updated school data",
     *      @OA\Parameter(
     *          name="id",
     *          description="School id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreSchoolRequest")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SchoolResource")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(UpdateSchoolRequest $request, School $school)
    {
        try {
            $updatedSchool = $this->schoolRepository->update($school, $request->all());

            return new SchoolResource($updatedSchool);
        } catch (\Throwable $e) {
            return response()->json('Bad request : ' . $e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/schools/{id}",
     *      operationId="deleteSchool",
     *      tags={"Schools"},
     *      summary="Delete existing school",
     *      description="Deletes a school and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="School id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
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
