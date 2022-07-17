<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\OutletOrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutletOrderController extends BaseController
{
    protected $outletOrderRepository;
    public function __construct(OutletOrderRepository $outletOrderRepository)
    {
        $this->outletOrderRepository = $outletOrderRepository;
    }
    /**
     * @OA\Get(
     *     path="/api/orders-list/{outlet_id}",
     *     tags={"POS Orders"},
     *     summary="Get outlet orders list",
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
    public function orderList(Request $request)
    {
        $outletId = $request->route('outlet_id');

        return response()->json([
            'data' => $this->outletOrderRepository->getOutletOrder($outletId)
        ]);
    }

    public function syncOrders($outletId, $dateTime)
    {
        return response()->json([
            'data' => $this->outletOrderRepository->getOutletOrderByDateTime($outletId, $dateTime)
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/orders-list/{outlet_id}/{status}",
     *     tags={"POS Orders"},
     *     summary="Get outlet orders list by status",
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
    public function orderListByStatus(Request $request)
    {
        $outletId = $request->route('outlet_id');
        $status = $request->route('status');

        return response()->json([
            'data' => $this->outletOrderRepository->getOutletOrderByStatus($outletId, $status)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/orders-details/{outlet_id}/{order_id}",
     *     tags={"POS Orders"},
     *     summary="Get order details by outlet id and order id",
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
    public function orderDetailsById(Request $request)
    {
        $outlet_id = $request->route('outlet_id');
        $order_id = $request->route('order_id');

        return response()->json([
            'data' => $this->outletOrderRepository->getOutletOrderDetailsById($outlet_id, $order_id)
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/order-process",
     *      operationId="update",
     *      tags={"POS Orders"},
     *      summary="Update order",
     *      security={{"passport": {}}},
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"order_id", "outlet_id", "status"},
     *              @OA\Property(property="order_id", type="integer", example="39753"),
     *              @OA\Property(property="outlet_id", type="integer", example="2"),
     *              @OA\Property(property="status", type="integer", example="5")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="bool", example="true"),
     *              @OA\Property(property="msg", type="string", example="Order updated successfully."),
     *          ),
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
    public function updateOrderByStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'outlet_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return response()->json(
            [
                'data' => $this->outletOrderRepository->updateOutletOrder($request->all())
            ]
        );
    }
}
