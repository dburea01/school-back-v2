<?php

declare(strict_types=1);

namespace App\Http\Swagger;

class SchoolController
{
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
     *     @OA\Response(response=403, description="Forbidden"),
     *     security={
     *         {
     *             "oauth2_security_example"={"write:schools", "read:schools"}
     *         }
     *     },
     * )
     */
}
