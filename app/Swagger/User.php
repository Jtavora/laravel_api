<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"name", "email", "password"},
 *     @OA\Property(property="name", type="string", example="Sample User"),
 *    @OA\Property(property="email", type="string", example="example@email.com"),
 *   @OA\Property(property="password", type="string", example="password")
 * )
 */
class User {}
