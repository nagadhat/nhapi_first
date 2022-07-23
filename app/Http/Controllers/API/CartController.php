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
     *      path="/api/cart-item-by-user/{locationId}/{userId}",
     *      tags={"Cart Product"},
     *      summary="Get cart data",
     *      security={{"passport": {}}},
     *      description="Returns cart data by user_id and location_id when user is logged in.",
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorize Access, Invalid Token or Token has expired",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *           response=400,
     *           description="Bad Request"
     *          ),
     *      @OA\Response(
     *           response=404,
     *           description="not found"
     *          ),
     *      )
     *
     */

    public function allCartProductByUserId($locationId, $userId)
    {
        return response()->json([
            'data' => $this->cartRepository->allCartProductById($userId, $locationId)
        ]);
    }


    /**
     * @OA\Get(
     *      path="/api/cart-item-by-public/{locationId}/{uId}",
     *      tags={"Cart Product"},
     *      summary="Get cart data",
     *      description="Returns cart data by unique identifier according to location when user is not logged in.",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorize Access, Invalid Token or Token has expired",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *           response=400,
     *           description="Bad Request"
     *          ),
     *      @OA\Response(
     *           response=404,
     *           description="not found"
     *          ),
     *      )
     *
     */

    public function cartItemByUId($locationId, $uId)
    {
        return response()->json([
            'data' => $this->cartRepository->getCartItemByUId($uId, $locationId)
        ]);
    }


    /**
     *  @OA\Post(
     *  path="/api/add-to-cart",
     *  summary="Add to cart",
     *  description="This end-point will add products to cart, outlet_id, location_id, product_id, order_type, quantity fields are required, including user_id / uid, product_variation_size field is optional. When user is logged in include user-id, when user is not logged in or not a registered user enter uid. uid is a unique identifier which will represent a public users identity according to the location they are. if location changes cart item could change accordingly.",
     *  operationId="add-to-cart",
     *  tags={"Cart Product"},
     *  @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"outlet_id","location_id"},
     *       @OA\Property(property="user_id", type="integer", example="3121"),
     *       @OA\Property(property="uid", type="string", example="userAgentUniqueId"),
     *       @OA\Property(property="outlet_id", type="integer", example="1"),
     *       @OA\Property(property="location_id", type="integer", example="67"),
     *       @OA\Property(property="order_type", type="string", example="Regular"),
     *       @OA\Property(property="product_id", type="integer", example="1559"),
     *       @OA\Property(property="quantity", type="integer", example="5"),
     *       @OA\Property(property="product_variation_size", type="integer", example="0"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="This will return status, msg, and basic information about the cart",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="true"),
     *       @OA\Property(property="msg", type="string", example="Product added to cart successfully"),
     *       @OA\Property(property="data", type="string", example="Basic information about the cart")
     *        )
     *     )
     *  )
     */

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable',
            'uid' => 'nullable',
            'order_type' => 'required',
            'product_id' => 'required',
            'outlet_id' => 'required',
            'location_id' => 'required',
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
