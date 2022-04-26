<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\RequisitionIssueRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outlet;
use Illuminate\Support\Facades\Validator;
use App\Models\OutletRequisition;
// use Illuminate\Http\Response;

class RequisitionIssueController extends BaseController
{
    protected $requisitionIssueRepository;
    protected $outlet;
    public function __construct(RequisitionIssueRepository $requisitionIssueRepository, Outlet $outlet)
    {
        $this->requisitionIssueRepository = $requisitionIssueRepository;
        $this->outlet = $outlet;
    }

    public function newRequisition(Request $request)
    {
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

    public function outletIssues($outletID)
    {
        return response()->json([
            'data' => $this->requisitionIssueRepository->outletIssues($outletID),
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
