<?php

namespace App\Repositories;

use App\Interfaces\OutletRepositoryInterface;
use App\Models\DeliveryLocation;
use App\Models\Outlet;
use App\Models\OutletLocation;
use App\Models\UserCustomer;

class OutletRepository implements OutletRepositoryInterface
{
    protected $outlet;
    public function __construct(Outlet $outlet, DeliveryLocation $deliveryLocation, OutletLocation $outletLocation)
    {
        $this->outlet = $outlet;
        $this->deliveryLocation = $deliveryLocation;
        $this->outletLocation = $outletLocation;
    }

    public function getOutlet()
    {
        return $this->outlet::all();
    }

    public function getOutletById($outletId)
    {
        $outlet = $this->outlet::find($outletId);
        if ($outlet) {
            return $outlet;
        } else {
            return 'Invalid outlet_id';
        }
    }

    public function getAllDeliveryLocation()
    {
        return $this->deliveryLocation::all();
    }

    public function getDeliveryLocationByOutletId($outlet_id)
    {
        $outlet = $this->outlet::find($outlet_id);
        if ($outlet) {
            $location_ids = $this->outletLocation::where('outlet_id', $outlet_id)->get()->pluck('location_id');
            return $this->deliveryLocation::whereIn('id', $location_ids)->get();
        } else {
            return 'Invalid outlet_id';
        }
    }

    public function getOutletByDeliveryLocation($location_id)
    {
        $location = $this->deliveryLocation::find($location_id);
        if ($location) {
            $outletLocation = $this->outletLocation::where('location_id', $location_id)->first();
            $outlet = $this->outlet::find($outletLocation->outlet_id);
            return $outlet;
        } else {
            return 'Invalid location_id';
        }
    }

    public function getCustomerList()
    {
        return UserCustomer::select('id', 'first_name as name')->where('status', 1)->get();
    }
}
