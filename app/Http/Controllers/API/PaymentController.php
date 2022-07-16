<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    protected $paymentRepository;
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @OA\Post(
     *      path="/api/online-order/payment",
     *      operationId="payment",
     *      tags={"POS Payment"},
     *      summary="Receive Online payment",
     *      security={{"passport": {}}},
     *      description="Receive online payment when customer comes to the outlet for order pickup",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"order_id", "outlet_id", "transaction_amount", "payer_name", "payer_phone"},
     *              @OA\Property(property="order_id", type="integer", example="39753"),
     *              @OA\Property(property="outlet_id", type="integer", example="2"),
     *              @OA\Property(property="transaction_amount", type="double", example="5156.25"),
     *              @OA\Property(property="payer_name", type="string", example="Md Frank"),
     *              @OA\Property(property="payer_phone", type="string", example="0123456789"),
     *              @OA\Property(property="note", type="string", example="something"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *              @OA\Property(property="status", type="string", example="true"),
     *              @OA\Property(property="msg", type="string", example="Payment received successfully."),
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

    public function receiveOnlinePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'outlet_id' => 'required',
            'transaction_amount' => 'required',
            'payer_name' => 'required',
            'payer_phone' => 'required',
            // 'note'  => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paymentdetails['order_id'] = $request->order_id;
        $paymentdetails['outlet_id'] = $request->outlet_id;
        $paymentdetails['transaction_amount'] = $request->transaction_amount;
        $paymentdetails['payer_name'] = $request->payer_name;
        $paymentdetails['payer_phone'] = $request->payer_phone;
        if ($request->note) {
            $paymentdetails['note']  = $request->note;
        }
        return response()->json(
            [
                'data' => $this->paymentRepository->createPayment($paymentdetails)
            ],
        );
    }
}
