<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="LaraSchool API Documentation",
 *      description="You will find here all the necessary documentation to consume the APIs. Remind : you must be connected to use these APIs.",
 *      @OA\Contact(
 *          email="dominique.bureau@free.fr"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 *
 * @OA\Tag(name="Schools")
 * 
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints of Users"
 * )
 * 
 * @OA\Tag(
 *     name="Groups",
 *     description="API Endpoints of Groups"
 * )
 */


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
