<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepositoryInterface;
    
    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * @OA\Get(
     *    path="/api/products",
     *    summary="Get all products",
     *    tags={"Products"},
     *    security={{"bearerAuth":{}}},
     *    @OA\Response(
     *        response=200,
     *        description="Get all products",
     *        @OA\Schema(
     *            type="array",
     *            @OA\Items(ref="#/components/schemas/Product")
     *        )
     *    )
     * )
     */
    public function index()
    {
        $data = $this->productRepositoryInterface->index();

        return ApiResponseClass::sendResponse(ProductResource::collection($data),'',200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

   /**
     * @OA\Post(
     *   path="/api/products",
     *   summary="Create a product",
     *   tags={"Products"},
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          required={"name", "details"},
     *          @OA\Property(property="name", type="string", example="Product Example"),
     *          @OA\Property(property="details", type="number", format="float", example="Product Test")
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *      description="Product created successfully",
     *      @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/Product"
     *      )
     *   )
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $details =[
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try{
             $product = $this->productRepositoryInterface->store($details);

             DB::commit();
             return ApiResponseClass::sendResponse(new ProductResource($product),'Product Create Successful',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/products/{id}",
     *   summary="Get a product",
     *   tags={"Products"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of the product",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Get a product",
     *      @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/Product"
     *      )
     *   )
     * )
     */
    public function show($id)
    {
        $product = $this->productRepositoryInterface->getById($id);

        return ApiResponseClass::sendResponse(new ProductResource($product),'',200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * @OA\Put(
     *   path="/api/products/{id}",
     *   summary="Update a product",
     *   tags={"Products"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      description="ID of the product to update",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          required={"name", "details"},
     *          @OA\Property(property="name", type="string", example="Updated Product"),
     *          @OA\Property(property="details", type="number", format="float", example="Example Product Test")
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Product updated successfully",
     *      @OA\JsonContent(
     *          type="object",
     *          ref="#/components/schemas/Product"
     *      )
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="Product not found"
     *   )
     * )
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $updateDetails =[
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try{
             $product = $this->productRepositoryInterface->update($updateDetails,$id);

             DB::commit();
             return ApiResponseClass::sendResponse('Product Update Successful','',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->productRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse('Product Delete Successful','',204);
    }
}
