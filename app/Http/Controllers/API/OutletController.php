<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\OutletRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OutletController extends BaseController
{
    protected $outletRepository;
    public function __construct(OutletRepository $outletRepository)
    {
        $this->outletRepository = $outletRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/get-all-outlet",
     *     tags={"Outlet & Location"},
     *     summary="Get outlet list",
     *     security={{"passport": {}}},
     *     description="Get outlet list",
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

    public function getOutlet()
    {
        return response()->json([
            'data' => $this->outletRepository->getOutlet()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/get-outlet/{outletId}",
     *     tags={"Outlet & Location"},
     *     summary="Get outlet details by id",
     *     security={{"passport": {}}},
     *     description="Get outlet details by outlet_id",
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

    public function getOutletById(Request $request)
    {
        $outletId = $request->route('outletId');
        return response()->json([
            'data' => $this->outletRepository->getOutletById($outletId)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/outlet-delivery-location",
     *     tags={"Outlet & Location"},
     *     summary="Get outlet delivery location list",
     *     security={{"passport": {}}},
     *     description="Get outlet delivery location list",
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

    public function outletDeliveryLocation()
    {
        return response()->json([
            'data' => $this->outletRepository->getAllDeliveryLocation()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/outlet-delivery-location/{outletId}",
     *     tags={"Outlet & Location"},
     *     summary="Get outlet delivery location list by outlet_id",
     *     security={{"passport": {}}},
     *     description="Get outlet delivery location list by outlet_id, When a customer selects one of these location, they will only see the products of that specific outlets throughout the whole website...",
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

    public function outletDeliveryLocationByOutlet($outlet_id)
    {
        return response()->json([
            'data' => $this->outletRepository->getDeliveryLocationByOutletId($outlet_id)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/outlet/{locationId}",
     *     tags={"Outlet & Location"},
     *     summary="Get outlet info by delivery_id",
     *     security={{"passport": {}}},
     *     description="Get outlet info by delivery location id, When a customer selects a location, they will only see the products of that specific outlets throughout the whole website...",
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

    public function outletByDeliveryLocation($location_id)
    {
        return response()->json([
            'data' => $this->outletRepository->getOutletByDeliveryLocation($location_id)
        ]);
    }

    public function customerList()
    {
        return response()->json([
            'data' => $this->outletRepository->getCustomerList()
        ]);
    }
}
