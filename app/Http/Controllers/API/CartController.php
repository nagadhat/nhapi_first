<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CartRepository;

class CartController extends Controller
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
     *       @OA\Property(property="id", type="intiger", example="567"),
     *       @OA\Property(property="user_id", type="intiger", example="3166"),
     *       @OA\Property(property="order_type", type="string", example="Flash"),
     *       @OA\Property(property="product_id", type="intiger", example="1549"),
     *       @OA\Property(property="quantity", type="intiger", example="3"),
     *       @OA\Property(property="product_unit_price", type="intiger", example="15568"),
     *       @OA\Property(property="product_variation_size", type="intiger", example="0"),
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
    public function allCartProductById(Request $request){
        $userId = $request->route('userId');

        return response()->json([
            'data' => $this->cartRepository->allCartProductById($userId)
        ]);
    }
}
