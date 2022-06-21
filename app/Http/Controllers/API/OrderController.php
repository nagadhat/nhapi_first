<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Outlet;


/**
 *     @SWG\SecurityScheme(
 *          securityDefinition="passport",
 *          type="apiKey",
 *          in="header",
 *          name="Authorization"
 *      )
 */
class OrderController extends BaseController
{
    protected $orderRepository;
    protected $outlet;
    public function __construct(OrderRepository $orderRepository, Outlet $outlet)
    {
        $this->orderRepository = $orderRepository;
        $this->outlet = $outlet;
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders CRUD"},
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

    /**
     * @OA\Post(
     *      path="/api/orders",
     *      operationId="store",
     *      tags={"Orders CRUD"},
     *      summary="Store new order",
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'shipping_address'  => 'required',
            'delivery_address'  => 'required',
            'shipping_type'     => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $orderDetails['user_id']            = $request->user_id;
        $orderDetails['shipping_address']   = $request->shipping_address;
        $orderDetails['delivery_address']   = $request->delivery_address;
        $orderDetails['shipping_type']      = $request->shipping_type;
        if ($request->delivery_note) {
            $orderDetails['delivery_note']  = $request->delivery_note;
        }
        return response()->json(
            [
                'data' => $this->orderRepository->createOrder($orderDetails)
            ],
            Response::HTTP_CREATED
        );
        // return $request->all();
        // $input = $request->all();

        // $validator = Validator::make($input, [
        //     'client'    => 'required',
        //     'details' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return $this->handleError($validator->errors());
        // }

        // $orderDetails = $request->only([
        //     'client',
        //     'details'
        // ]);
    }

    public function storePOSsale(Request $request)
    {
        // Validation Request Data
        $outletID = $request['sales_data']['outlet_id'];
        $sales_data = $request['sales_data'];
        $cartProducts = $request['sales_data']['cart_products'];

        if (empty($this->outlet::find($outletID))) {
            return $this->sendError('Invalid Outlet ID', ['error' => 'Outlet Not Found!']);
        }

        $validator = Validator::make($sales_data, [
            'user_id' => 'required|integer',
            'outlet_id' => 'required|integer',
            'pos_sale_id' => 'required',
            'shipping_address' => 'required',
            'delivery_address' => 'required',
            'shipping_type' => 'required',
            'total_product' => 'required|integer',
            'total_price' => 'required|integer',
            'total_paid' => 'required|integer',
            'delivery_charge' => 'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        foreach($cartProducts as $product){
            $validator = Validator::make($product, [
                'product_id' => 'required|integer',
                'product_quantity' => 'required|integer',
                'product_unit_price' => 'required|integer',
                'order_type' => 'required',
                'product_variation_size' => 'required|integer',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }

        // Execute after successfully validation =>
        $orderDetails['user_id']            = $sales_data['user_id'];
        $orderDetails['shipping_address']   = $sales_data['shipping_address'];
        $orderDetails['delivery_address']   = $sales_data['delivery_address'];
        $orderDetails['shipping_type']      = $sales_data['shipping_type'];
        if ($sales_data['delivery_note']) {
            $orderDetails['delivery_note']  = $sales_data['delivery_note'];
        }

        return response()->json([
            'data' => $this->orderRepository->createPosOrder($orderDetails, $cartProducts, $sales_data)
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/orders/{id}",
     *      tags={"Orders CRUD"},
     *      summary="Get order information",
     *      security={{"passport": {}}},
     *      description="Returns order data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Project id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
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
    public function show(Request $request)
    {
        $orderId = $request->route('id');

        return response()->json([
            'data' => $this->orderRepository->getOrderById($orderId)
        ]);
    }
    /**
     * @OA\Put(
     *      path="/api/orders/{id}",
     *      operationId="updateProject",
     *      tags={"Orders CRUD"},
     *      summary="Update existing order",
     *      security={{"bearer":{}}},
     *      description="Returns updated project data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Order id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Order")
     *      ),
     *      @OA\Response(
     *          response=202,
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(Request $request)
    {
        $orderId = $request->route('id');
        $orderDetails = $request->only([
            'client',
            'details'
        ]);

        return response()->json([
            'data' => $this->orderRepository->updateOrder($orderId, $orderDetails)
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/api/orders/{id}",
     *      operationId="destroy",
     *      tags={"Orders CRUD"},
     *      summary="Delete existing order",
     *      security={{"bearer":{}}},
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Order id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy(Request $request)
    {
        $orderId = $request->route('id');
        $this->orderRepository->deleteOrder($orderId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function test_api()
    {
        echo 'OKay';
        return true;
    }
}
