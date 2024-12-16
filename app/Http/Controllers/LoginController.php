<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserResquest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for managing Auth"
 * )
 */
class LoginController extends Controller
{
    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Login"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="Bearer eyJhbGciOiJIUzI1Ni..."),
     *             @OA\Property(property="message", type="string", example="User logged in successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $data = (object) [
            'email' => $request->email,
            'password' => $request->password
        ];

        DB::beginTransaction();
        try {
            $response = $this->userRepositoryInterface->login($data);

            if (!$response['success']) {
                return ApiResponseClass::sendResponse($response, 'Invalid credentials', 401);
            }

            DB::commit();
            return ApiResponseClass::sendResponse($response, 'User logged in successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout a user",
     *     tags={"Auth"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged out successfully")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            $response = $this->userRepositoryInterface->logout();
            return ApiResponseClass::sendResponse($response, 'User logged out successfully', 200);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }
}
