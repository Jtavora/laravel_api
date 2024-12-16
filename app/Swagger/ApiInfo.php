<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="API Local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="JWT Authentication using Bearer token"
 * )
 */
class ApiInfo {}