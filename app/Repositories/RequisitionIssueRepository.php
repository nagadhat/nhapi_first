<?php

namespace App\Repositories;

use App\Interfaces\RequisitionIssueRepositoryInterface;
use App\Models\OutletRequisitionProduct;
use App\Models\OutletRequisition;
use App\Models\OutletProduct;
use App\Models\OutletIssue;
use App\Models\OutletIssueProduct;
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
    
    public function outletIssues($outletID){
        $issues = OutletIssue::where('outlet_id', $outletID)->get();

        // $outletIssues = array();
        for($i = 0; $i < count($issues); $i++) {
            $issuedProducts = OutletIssueProduct::where('issue_id', $issues[$i]->id)->get();
            
            $items = array();
            foreach ($issuedProducts as $issuedProduct) {
                $items[] = array(
                    'product_id' => $issuedProduct->product_id,
                    'product_quantity' => $issuedProduct->product_quantity,
                    'purchase_price' => $issuedProduct->purchase_price,
                );
            }
            
            // $outletIssues[] = array(
            //     'id' => $issues[$i]->id,
            //     'requisition_id' => $issues[$i]->requisition_id,
            //     'outlet_id' => $issues[$i]->outlet_id,
            //     'amount' => $issues[$i]->amount,
            //     'products' => $items,
            // );

            $issues[$i]['products'] = $items;
        }

        return $issues;
        // return $outletIssues;
    }
}