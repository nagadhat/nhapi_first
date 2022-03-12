<?php

namespace App\Repositories;

use App\Interfaces\OutletRepositoryInterface;
use App\Models\Outlet;

class OutletRepository implements OutletRepositoryInterface 
{
    protected $outlet;
    public function __construct(Outlet $outlet){
        $this->outlet = $outlet;
    }

    public function getOutlet() 
    {
        return $this->outlet::all();
    }

    public function getOutletById($outletId)
    {
        return $this->outlet::find($outletId);
    }
}