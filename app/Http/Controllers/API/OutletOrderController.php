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
     *     tags={"Outlet Orders"},
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


    /**
     * @OA\Get(
     *     path="/api/orders-list/{outlet_id}/{status}",
     *     tags={"Outlet Orders"},
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
     *     path="/api/orders-list/{outlet_id}/{status}",
     *     tags={"Outlet Orders"},
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
     *      tags={"Outlet Orders"},
     *      summary="Update order",
     *      security={{"passport": {}}},
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Order")
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
