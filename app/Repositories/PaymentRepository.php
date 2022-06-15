<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Traits\SmsTraits;
use App\Models\Order;
use App\Models\Payment;
use App\Models\UserCustomer;
use Illuminate\Support\Carbon;

class PaymentRepository implements PaymentRepositoryInterface
{
    use SmsTraits;
    protected $order;
    public function __construct(Order $order, UserCustomer $userCustomer, Payment $payment)
    {
        $this->order = $order;
        $this->userCustomer = $userCustomer;
        $this->payment = $payment;
    }


    public function createPayment(array $paymentdetails)
    {
        $order = $this->order::where('id', $paymentdetails['order_id'])->first();

        if ($paymentdetails['outlet_id'] != $order->outlet_id) {
            return 'Invalid outlet_id';
        }
        // return $order;
        $customer = $this->userCustomer::find($order->user_id);
        $totalPaid = $this->payment::where('order_id', $order->id)->sum('transaction_amound');
        // check payment amount and due
        if ($paymentdetails['transaction_amount'] > ($order->total_products_price + $order->total_delivery_charge - $totalPaid)) {
            return 'Please recheck your Payment Amount...';
        } else {
            $this->payment::create([
                "date_time" => Carbon::now(),
                "order_id" => $order->id,
                "user_id" => $order->user_id,
                "user_name" => $order->username,
                "payer_name" => $paymentdetails['payer_name'],
                "payer_phone" => $paymentdetails['payer_phone'],
                "transaction_id" => $paymentdetails['outlet_id'],
                "transaction_amound" => $paymentdetails['transaction_amount'],
                "payment_getway" => "Outlet",
                "note_1" => $paymentdetails['note'],
                "payment_method" => 'Cash',
                "transaction_status" => 1
            ]);
        }

        return [
            'status' => true,
            'msg' => 'Payment recived successfully.'
        ];
    }
}
