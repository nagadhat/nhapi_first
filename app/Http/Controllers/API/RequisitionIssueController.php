<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\RequisitionIssueRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\OutletIssue;
use Illuminate\Support\Facades\Validator;
use App\Models\OutletRequisition;
// use Illuminate\Http\Response;

class RequisitionIssueController extends BaseController
{
    protected $requisitionIssueRepository;
    protected $outlet;
    protected $outletIssue;
    public function __construct(RequisitionIssueRepository $requisitionIssueRepository, Outlet $outlet, OutletIssue $outletIssue)
    {
        $this->requisitionIssueRepository = $requisitionIssueRepository;
        $this->outlet = $outlet;
        $this->outletIssue = $outletIssue;
    }

    public function newRequisition(Request $request)
    {
        return $request['requisition']['outlet_id'];
        // Valodation Request Data
        $outletID = $request['requisition']['outlet_id'];
        $requisitionProduct = $request['requisition']['product'];

        if (empty($this->outlet::find($outletID))) {
            return $this->sendError('Invalid Outlet ID', ['error' => 'Outlet Not Found!']);
        }

        foreach($requisitionProduct as $requisition){
            $validator = Validator::make($requisition, [
                'product_id' => 'required|integer',
                'product_quantity' => 'required|integer',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }


        // Execute after successfully validation =>
        return response()->json([
            'data' => $this->requisitionIssueRepository->newRequisition($request),
        ]);
    }
    
    public function readOutletIssues(Request $request)
    {        
        // $validator = Validator::make($request->all(), [
        //     'issue_id' => 'required',
        // ]);
   
        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());       
        // }
        
        // if(empty($request->input('issue_id'))){
        //     return $this->sendError('Validation Error.', 'Issue ID can not be null'); 
        // }

        // return 'OK';
        $issueID = $request['receive_data']['issue_id'];
        $receiveProducts = $request['receive_data']['productInfos'];

        if (empty($this->outletIssue::find($issueID))) {
            return $this->sendError('Invalid Issue ID', ['error' => 'Issue Not Found!']);
        }

        // foreach($receiveProducts as $receiveProduct){
        //     $validator = Validator::make($receiveProduct, [
        //         'product_id' => 'required|integer',
        //         'product_quantity' => 'required|integer',
        //     ]);

        //     if($validator->fails()){
        //         return $this->sendError('Validation Error.', $validator->errors());
        //     }
        // }
        
        // Execute after successfully validation =>
        return response()->json([
            'data' => $this->requisitionIssueRepository->readOutletIssues($request),
        ]);
    }

    public function outletIssues($outletID)
    {
        return response()->json([
            'data' => $this->requisitionIssueRepository->outletIssues($outletID),
        ]);
    }
    
    public function newOutletIssues($outletID)
    {
        return response()->json([
            'data' => $this->requisitionIssueRepository->newOutletIssues($outletID),
        ]);
    }

    public function outletIssuesByRequisition($outletID, $reqID)
    {
        return response()->json([
            'data' => $this->requisitionIssueRepository->outletIssuesByRequisition($outletID, $reqID),
        ]);
    }

    public function outletRequisitionsStatus($outletID)
    {
        return response()->json([
            'data' => $this->requisitionIssueRepository->outletRequisitionsStatus($outletID),
        ]);
    }
}
