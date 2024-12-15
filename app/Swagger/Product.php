<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"name", "details"},
 *     @OA\Property(property="name", type="string", example="Sample Product"),
 *     @OA\Property(property="details", type="string", example="Details about the product"),
 * )
 */
class Product {}
