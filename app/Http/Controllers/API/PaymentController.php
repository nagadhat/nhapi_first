<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends BaseController
{
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @OA\Post(
     *      path="/api/payment",
     *      operationId="payment",
     *      tags={"Outlet Payment"},
     *      summary="Recive Online payment",
     *      security={{"passport": {}}},
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Payment")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Payment")
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
    public function reciveOnlinePayment(Request $request)
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
