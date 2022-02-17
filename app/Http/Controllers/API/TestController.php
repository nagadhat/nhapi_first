<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\OrderRepository;

// class TestController extends Controller
// {
//     //
// }


class TestController extends BaseController
{
    protected $orderRepository;
    public function __construct(OrderRepository $orderRepository) 
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/all-orders",
     *     tags={"Order List"},
     *     summary="Get orders list",
     *     security={{"passport": {}}},
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
    public function index() 
    {
        return response()->json([
            'data' => $this->orderRepository->getAllOrders()
        ]);
    }
}