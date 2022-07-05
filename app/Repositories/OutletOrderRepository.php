<?php

namespace App\Repositories;

use App\Interfaces\OutletOrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrdersProduct;
use App\Models\OrderTimeline;
use App\Models\Outlet;
use App\Models\Payment;
use App\Traits\AffiliateTraits;
use Carbon\Carbon;

class OutletOrderRepository implements OutletOrderRepositoryInterface
{
    use AffiliateTraits;

    protected $outlet;
    public function __construct(Outlet $outlet, Order $order, OrdersProduct $ordersProduct, OrderTimeline $orderTimeline, Payment $payment)
    {
        $this->outlet = $outlet;
        $this->order = $order;
        $this->ordersProduct = $ordersProduct;
        $this->orderTimeline = $orderTimeline;
        $this->payment = $payment;
    }

    public function getOutletOrder($outletId)
    {
        $checkOutlet = $this->outlet::find($outletId);
        if (!$checkOutlet) {
            return 'Invalid outlet_id';
        }
        $orders = $this->order::where('outlet_id', $outletId)
            ->whereIn('order_status', [1, 2, 3, 4, 5, 6, 8, 9])
            ->where('restricted', 0)
            ->get();

        // product info
        for ($i = 0; $i < count($orders); $i++) {
            $orderProducts = $this->ordersProduct::where('order_id', $orders[$i]->id)->get();

            $items = array();
            foreach ($orderProducts as $orderProduct) {
                $items[] = array(
                    'id' => $orderProduct->id,
                    'product_id' => $orderProduct->product_id,
                    'product_quantity' => $orderProduct->product_quantity,
                    'product_unit_price' => $orderProduct->product_unit_price,
                );
            }

            $orders[$i]['products'] = $items;
        }
        // payment info
        for ($i = 0; $i < count($orders); $i++) {
            $payments = $this->payment::where('order_id', $orders[$i]->id)->where('transaction_status', 1)->get();

            $items = array();
            foreach ($payments as $payment) {
                $items[] = array(
                    'id' => $payment->id,
                    'payer_name' => $payment->payer_name,
                    'payer_phone' => $payment->payer_phone,
                    'date_time' => $payment->date_time,
                    'transaction_amound' => $payment->transaction_amound,
                    'payment_getway' => $payment->payment_getway,
                    'bank_name' => $payment->bank_name,
                    'payment_slip' => $payment->payment_slip
                );
            }
            $orders[$i]['payments'] = $items;
        }

        return $orders;
    }

    public function getOutletOrderByDateTime($outletId, $dateTime)
    {
        $checkOutlet = $this->outlet::find($outletId);
        if (!$checkOutlet) {
            return 'Invalid outlet_id';
        }

        $orders = $this->order::where('outlet_id', $outletId)
            ->whereIn('order_status', [1, 2, 3, 4, 5, 6, 8, 9])
            ->where('restricted', 0)
            ->where('updated_at', '>', $dateTime)
            ->get();

        // product info
        for ($i = 0; $i < count($orders); $i++) {
            $orderProducts = $this->ordersProduct::where('order_id', $orders[$i]->id)->get();

            $items = array();
            foreach ($orderProducts as $orderProduct) {
                $items[] = array(
                    'id' => $orderProduct->id,
                    'product_id' => $orderProduct->product_id,
                    'product_quantity' => $orderProduct->product_quantity,
                    'product_unit_price' => $orderProduct->product_unit_price,
                );
            }

            $orders[$i]['products'] = $items;
        }
        // payment info
        for ($i = 0; $i < count($orders); $i++) {
            $payments = $this->payment::where('order_id', $orders[$i]->id)->where('transaction_status', 1)->get();

            $items = array();
            foreach ($payments as $payment) {
                $items[] = array(
                    'id' => $payment->id,
                    'payer_name' => $payment->payer_name,
                    'payer_phone' => $payment->payer_phone,
                    'date_time' => $payment->date_time,
                    'transaction_amound' => $payment->transaction_amound,
                    'payment_getway' => $payment->payment_getway,
                    'bank_name' => $payment->bank_name,
                    'payment_slip' => $payment->payment_slip
                );
            }
            $orders[$i]['payments'] = $items;
        }

        return $orders;
    }

    public function getOutletOrderByStatus($outletId, $status)
    {
        $checkOutlet = $this->outlet::find($outletId);
        if (!$checkOutlet) {
            return 'Invalid outlet_id';
        }

        $status_list = [1, 2, 3, 4, 5, 6, 8, 9];
        if (!in_array($status, $status_list)) {
            return 'Invalid status';
        }

        $orders = $this->order::where('outlet_id', $outletId)
            ->where('order_status', $status)
            ->where('restricted', 0)
            ->get();

        // product info
        for ($i = 0; $i < count($orders); $i++) {
            $orderProducts = $this->ordersProduct::where('order_id', $orders[$i]->id)->get();

            $items = array();
            foreach ($orderProducts as $orderProduct) {
                $items[] = array(
                    'id' => $orderProduct->id,
                    'product_id' => $orderProduct->product_id,
                    'product_quantity' => $orderProduct->product_quantity,
                    'product_unit_price' => $orderProduct->product_unit_price,
                );
            }
            $orders[$i]['products'] = $items;
        }

        // payment info
        for ($i = 0; $i < count($orders); $i++) {
            $payments = $this->payment::where('order_id', $orders[$i]->id)->where('transaction_status', 1)->get();

            $items = array();
            foreach ($payments as $payment) {
                $items[] = array(
                    'id' => $payment->id,
                    'payer_name' => $payment->payer_name,
                    'payer_phone' => $payment->payer_phone,
                    'date_time' => $payment->date_time,
                    'transaction_amound' => $payment->transaction_amound,
                    'payment_getway' => $payment->payment_getway,
                    'bank_name' => $payment->bank_name,
                    'payment_slip' => $payment->payment_slip
                );
            }
            $orders[$i]['payments'] = $items;
        }

        return $orders;
    }

    // function to update outlet orders
    public function updateOutletOrder($orderDetails)
    {
        $status = $orderDetails['status'];
        $order_id = $orderDetails['order_id'];
        $outlet_id = $orderDetails['outlet_id'];


        $order = $this->order::find($order_id);
        if (!isset($order) || $order->outlet_id != $outlet_id) {
            return 'Invalid order_id !!';
        }

        $orderTimeline = $this->orderTimeline::where('order_id', $order_id)->first();

        $approvedAmount = $this->payment::approvedAmountByOrder($order_id);
        $paidAmount = $this->payment::paidAmountByOrder($order_id);

        if ($order->outlet_id != $outlet_id) {
            return 'Invalid outlet_id';
        }
        $status_list = [2, 3, 4, 5, 6, 8];
        if (!in_array($status, $status_list) || $status <= $order->order_status) {
            return 'Invalid status request !!';
        }
        if ($status == 4 || $status == 5 || $status == 6) {
            if ($approvedAmount < ($order->total_products_price + $order->total_delivery_charge)) {
                return 'Order not fully paid by the customer !!';
            }
        }
        if ($status == 8 && $paidAmount > 0) {
            return 'This order is already processing, can not Cancel this order !!';
        }

        // process
        if ($status == 4) {
            if (in_array($order->order_status, [1, 2, 3])) {
                // ************************************************************************************************************

                $orderTimeline->update([
                    'processing_on' => Carbon::now(),
                ]);

                // condition to create affiliate buyback policy package if any product in the order contains special audience
                $this->createBuybackPolicy($order);

                // ************************************************************************************************************
            } else {
                return 'Invalid status request !!';
            }
        }
        if ($status == 5) {
            if ($order->order_status == 4) {
                $orderTimeline->update([
                    'shipped_on' => Carbon::now(),
                ]);
            } else {
                return 'Invalid status request !!';
            }
        }
        if ($status == 6) {
            if (in_array($order->order_status, [4, 5])) {
                if (!isset($orderTimeline->shipped_on)) {
                    $orderTimeline->update([
                        'shipped_on' => Carbon::now(),
                    ]);
                }

                $orderTimeline->update([
                    'delivered_on' => Carbon::now(),
                ]);
            } else {
                return 'Invalid status request !!';
            }
        }
        if ($status == 8) {
            $orderTimeline->update([
                'canceled_on' => Carbon::now()
            ]);

            // restore product stock
            $this->restoreProductStock($order);

            // cancel affiliate bonus record when admin cancel and save to database , only for 1st generation will get affiliate bonus
            $this->cancelAffiliateBonusRecord($order);
        }

        $order->update([
            "order_status" => $status,
        ]);

        return [
            'status' => true,
            'msg' => 'Order updated successfully.'
        ];
    }

    // function to show outlet order details by order id
    public function getOutletOrderDetailsById($outlet_id, $order_id)
    {
        $order = $this->order::find($order_id);
        if ($order->outlet_id != $outlet_id) {
            return 'Invalid outlet_id';
        }

        // product info

        $orderProducts = $this->ordersProduct::where('order_id', $order->id)->get();
        $items = array();
        foreach ($orderProducts as $orderProduct) {
            $items[] = array(
                'id' => $orderProduct->id,
                'product_id' => $orderProduct->product_id,
                'product_quantity' => $orderProduct->product_quantity,
                'product_unit_price' => $orderProduct->product_unit_price,
            );
        }
        $order['products'] = $items;

        // payment info
        $payments = $this->payment::where('order_id', $order->id)->where('transaction_status', 1)->get();
        $items = array();
        foreach ($payments as $payment) {
            $items[] = array(
                'id' => $payment->id,
                'payer_name' => $payment->payer_name,
                'payer_phone' => $payment->payer_phone,
                'date_time' => $payment->date_time,
                'transaction_amound' => $payment->transaction_amound,
                'payment_getway' => $payment->payment_getway,
                'bank_name' => $payment->bank_name,
                'payment_slip' => $payment->payment_slip
            );
        }
        $order['payments'] = $items;


        return $order;
    }
}
