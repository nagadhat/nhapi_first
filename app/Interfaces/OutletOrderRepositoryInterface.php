<?php
namespace App\Interfaces;

interface OutletOrderRepositoryInterface
{
    public function getOutletOrder($outletId);
    public function getOutletOrderByStatus($outletId, $status);
    public function getOutletOrderDetailsById($outlet_id, $order_id);
    public function updateOutletOrder($orderDetails);

}
