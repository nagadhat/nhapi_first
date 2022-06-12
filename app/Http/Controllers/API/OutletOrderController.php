<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\OutletOrderRepository;
use Illuminate\Http\Request;

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
     *     tags={"Orders CRUD"},
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
     *     tags={"Orders CRUD"},
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
    public function orderListByStatus(Request $request)
    {
        $outletId = $request->route('outlet_id');
        $status = $request->route('status');

        return response()->json([
            'data' => $this->outletOrderRepository->getOutletOrderByStatus($outletId, $status)
        ]);
    }
}
