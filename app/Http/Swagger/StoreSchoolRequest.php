<?php

declare(strict_types=1);

namespace App\Http\Swagger;

/**
 * @OA\Schema(
 *     title="StoreSchoolRequest",
 *     description="Store School request body data",
 *     type="object",
 *     required={"name", "zip_code", "city", "country_id", "school_type_id", "school_status", "max_users"}
 * )
 */
class StoreSchoolRequest
{
    /**
     * @OA\Property(
     *     title="name",
     *     description="Name of the new school",
     *     format="string",
     *     example="Lycée Sainte Marie"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *     title="zip_code",
     *     description="zip code of the new school",
     *     format="string",
     *     example="59320"
     * )
     *
     * @var string
     */
    public $zip_code;

    /**
     * @OA\Property(
     *     title="city",
     *     description="city of the new school",
     *     format="string",
     *     example="Beaucamps Ligny"
     * )
     *
     * @var string
     */
    public $city;

    /**
     * @OA\Property(
     *     title="country_id",
     *     description="country id of the new school",
     *     format="string",
     *     example="FR"
     * )
     *
     * @var string
     */
    public $country_id;

    /**
     * @OA\Property(
     *     title="address1",
     *     description="Line 1 address of the new school",
     *     format="string",
     *     example="5 rue de la gare"
     * )
     *
     * @var string
     */
    public $address1;

    /**
     * @OA\Property(
     *     title="addressé",
     *     description="Line 2 address of the new school",
     *     format="string"
     * )
     *
     * @var string
     */
    public $address2;

    /**
     * @OA\Property(
     *     title="address3",
     *     description="Line 3 address of the new school",
     *     format="string",
     * )
     *
     * @var string
     */
    public $address3;

    /**
     * @OA\Property(
     *     title="school_type_id",
     *     description="type of the new school",
     *     format="string",
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
     *     description="status of the new school. Available value : ACTIVE | INACTIVE. If INACTIVE, the school is not usable by any user.",
     *     format="string",
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
     *     description="Max users of the new school",
     *     format="int",
     *     example=123
     * )
     *
     * @var string
     */
    public $max_users;
}