<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\Validator;

class CartController extends BaseController
{
    protected $cartRepository;
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/all-cart-product/{userId}",
     *      tags={"Cart Product"},
     *      summary="Get Cart Product by User ID",
     *      security={{"passport": {}}},
     *      description="Returns Products",
     *      @OA\Parameter(
     *          name="userId",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="This will return all product listed in cart of the given user id.",
     *    @OA\JsonContent(
     *       @OA\Property(property="cartId", type="intiger", example="567"),
     *       @OA\Property(property="productId", type="intiger", example="2562"),
     *       @OA\Property(property="orderType", type="string", example="Regular"),
     *       @OA\Property(property="cartProductImage", type="string", example="media/products/26072021737515495.webp"),
     *       @OA\Property(property="cartProductName", type="string", example="DIBAOLING Hand Sanitizer Formula"),
     *       @OA\Property(property="cartProductQuantity", type="intiger", example="10"),
     *       @OA\Property(property="cartProductUnitPrice", type="intiger", example="500"),
     *       @OA\Property(property="cartProductVendorId", type="intiger", example="40"),
     *       @OA\Property(property="cartProductReturnPolicy", type="string", example="null"),
     *       @OA\Property(property="cartProductSlug", type="string", example="DIBAOLING-Hand-Sanitizer-Formula-610abac3cc9c4"),
     *        )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function allCartProductById(Request $request)
    {
        $userId = $request->route('userId');

        return response()->json([
            'data' => $this->cartRepository->allCartProductById($userId)
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/add-to-cart",
     * summary="Add to cart",
     * security={{"passport": {}}},
     * description="This end-point will add products to cart",
     * operationId="add-to-cart",
     * tags={"Cart Product"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="user_id", type="integer", example="3121"),
     *       @OA\Property(property="order_type", type="string", example="Regular"),
     *       @OA\Property(property="product_id", type="integer", example="1559"),
     *       @OA\Property(property="quantity", type="integer", example="5"),
     *       @OA\Property(property="product_variation_size", type="integer", example="0"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="This will return status, msg, and basic information about the cart",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="true"),
     *       @OA\Property(property="msg", type="string", example="Product added to cart successfully"),
     *       @OA\Property(property="data", type="string", example="Basic information about the cart")
     *        )
     *     )
     * )
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'order_type' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'product_variation_size' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return response()->json([
            'data' => $this->cartRepository->addToCart($request)
        ]);
    }
}
