<?php
namespace App\Interfaces;

interface OutletRepositoryInterface
{
    public function getOutlet();
    public function getOutletById($outletId);
    public function getAllDeliveryLocation();
    public function getDeliveryLocationByOutletId($outlet_id);
    public function getOutletByDeliveryLocation($location_id);
    public function getCustomerList();
}
