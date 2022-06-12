<?php
namespace App\Interfaces;

interface OutletOrderRepositoryInterface
{
    public function getOutletOrder($outletId);
    public function getOutletOrderByStatus($outletId, $status);
}
