<?php

declare(strict_types=1);

namespace App\Http\Swagger;

/**
 * @OA\Schema(
 *     title="SchoolResource",
 *     description="School resource",
 *     @OA\Xml(
 *         name="SchoolResource"
 *     )
 * )
 */
class SchoolResource
{
    /**
     * @OA\Property(
     *     title="data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Http\Swagger\SchoolModel[]
     */
    private $data;

    /**
     * @OA\Property(
     *     title="links",
     *     description="Pagination links"
     * )
     */
    private $links;

    /**
     * @OA\Property(
     *     title="meta",
     *     description="Meta informations"
     * )
     */
    private $meta;
}