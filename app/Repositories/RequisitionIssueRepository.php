<?php

namespace App\Repositories;

use App\Interfaces\RequisitionIssueRepositoryInterface;
use App\Models\OutletRequisitionProduct;
use App\Models\OutletRequisition;
use App\Models\OutletProduct;
use App\Models\Outlet;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;

class RequisitionIssueRepository implements RequisitionIssueRepositoryInterface 
{
    protected $outletRequisition;
    protected $outletRequisitionProduct;
    protected $outletProduct;
    public function __construct(OutletRequisition $outletRequisition, OutletRequisitionProduct $outletRequisitionProduct, OutletProduct $outletProduct){
        $this->outletRequisition = $outletRequisition;
        $this->outletRequisitionProduct = $outletRequisitionProduct;
        $this->outletProduct = $outletProduct;
    }

    public function newRequisition($request){
        $outletID =  $request['requisition']['outlet_id'];
        $requisitionProduct = $request['requisition']['product'];

        $createRequisition = $this->outletRequisition::create($request['requisition']);

        $newRequisition = array();
        foreach($requisitionProduct as $requisition){
            // $requisition['outlet_id'] = $outletID;
            $requisition['requisition_id'] = $createRequisition->id;
            $newRequisition[] = $this->outletRequisitionProduct::create($requisition);

            $exist_in_outlet = $this->outletProduct::where('product_id', $requisition['product_id'])->first();
            if(empty($exist_in_outlet)){
                $outlet_product['outlet_id'] = $outletID;
                $outlet_product['product_id'] = $requisition['product_id'];
                $this->outletProduct::create($outlet_product);
            }
        }

        
        return $newRequisition;
    }
}