<?php

declare(strict_types=1);

namespace App\Http\Swagger;

/**
 * @OA\Schema(
 *     title="School",
 *     description="School model",
 *     @OA\Xml(
 *         name="School"
 *     )
 * )
 */
class SchoolModel
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="id of the school",
     *     format="uuid",
     *     example="08e2139d-79b7-4518-a5a5-73ecc161c4bb"
     * )
     *
     * @var string
     */
    public $id;

    /**
     * @OA\Property(
     *     title="name",
     *     description="Name of the school",
     *     example="Lycée Sainte Marie"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *     title="city",
     *     description="City of the school",
     *     example="BEAUCAMPS LIGNY"
     * )
     *
     * @var string
     */
    public $city;

    /**
     * @OA\Property(
     *     title="address1",
     *     description="Line 1 address of the school",
     *     example="5 rue de la gare"
     * )
     *
     * @var string
     */
    public $address1;

    /**
     * @OA\Property(
     *     title="address2",
     *     description="Line 2 address of the school",
     * )
     *
     * @var string
     */
    public $address2;

    /**
     * @OA\Property(
     *     title="address3",
     *     description="Line 3 address of the school",
     * )
     *
     * @var string
     */
    public $address3;

    /**
     * @OA\Property(
     *     title="country_id",
     *     description="Country id of the school",
     *     example="FR"
     * )
     *
     * @var string
     */
    public $country_id;

    /**
     * @OA\Property(
     *     title="school_type_id",
     *     description="Type the school",
     *     example="LYCEE",
     *     enum={"LYCEE", "COLLEGE", "PRIMAIRE", "MATERNELLE"}
     * )
     *
     * @var string
     */
    public $school_type_id;

    /**
     * @OA\Property(
     *     title="school_status",
     *     description="Status of the school.",
     *     example="ACTIVE",
     *     enum={"ACTIVE", "INACTIVE"}
     * )
     *
     * @var string
     */
    public $school_status;

    /**
     * @OA\Property(
     *     title="max_users",
     *     description="The max users the school can have.",
     *     example=123
     * )
     *
     * @var int
     */
    public $max_users;

    /**
     * @OA\Property(
     *     title="count_users",
     *     description="The quantity of users of the school (if the connected user is admin).",
     *     example=45
     * )
     *
     * @var int
     */
    public $count_users;
}