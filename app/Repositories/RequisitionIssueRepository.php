<?php

namespace App\Repositories;

use App\Interfaces\RequisitionIssueRepositoryInterface;
use App\Models\OutletRequisition;
use App\Models\Outlet;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;

class RequisitionIssueRepository implements RequisitionIssueRepositoryInterface 
{
    protected $requisition;
    public function __construct(OutletRequisition $requisition){
        $this->requisition = $requisition;
    }

    public function newRequisition($request){
        $outletID =  $request['requisition']['outlet_id'];
        $requisitionProduct = $request['requisition']['product'];

        $newRequisition = array();
        foreach($requisitionProduct as $requisition){
            $requisition['outlet_id'] = $outletID;
            $newRequisition[] = $this->requisition::create($requisition);
        }
        return $newRequisition;
    }
}