<?php

namespace App\Repositories;

use App\Interfaces\OutletOrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrdersProduct;
use App\Models\Outlet;

class OutletOrderRepository implements OutletOrderRepositoryInterface
{
    protected $outlet;
    public function __construct(Outlet $outlet, Order $order, OrdersProduct $ordersProduct)
    {
        $this->outlet = $outlet;
        $this->order = $order;
        $this->ordersProduct = $ordersProduct;
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

        for ($i = 0; $i < count($orders); $i++) {
            $orderProducts = $this->ordersProduct::where('order_id', $orders[$i]->id)->get();

            $items = array();
            foreach ($orderProducts as $orderProduct) {
                $items[] = array(
                    'product_id' => $orderProduct->product_id,
                    'product_quantity' => $orderProduct->product_quantity,
                    'product_unit_price' => $orderProduct->product_unit_price,
                );
            }

            $orders[$i]['products'] = $items;
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

        for ($i = 0; $i < count($orders); $i++) {
            $orderProducts = $this->ordersProduct::where('order_id', $orders[$i]->id)->get();

            $items = array();
            foreach ($orderProducts as $orderProduct) {
                $items[] = array(
                    'product_id' => $orderProduct->product_id,
                    'product_quantity' => $orderProduct->product_quantity,
                    'product_unit_price' => $orderProduct->product_unit_price,
                );
            }

            $orders[$i]['products'] = $items;
        }

        return $orders;
    }
}
