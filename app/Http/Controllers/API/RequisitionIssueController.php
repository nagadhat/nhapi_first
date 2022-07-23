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
        // Validate Request Data
        $outletID = $request['requisition']['outlet_id'];
        $requisitionProduct = $request['requisition']['product'];

        $val = Validator::make($request['requisition'], [
            'outlet_id' => 'required',
            'requisition_no' => 'required',
        ]);

        if ($val->fails()) {
            return $this->sendError('Validation Error.', $val->errors());
        }

        if (empty($this->outlet::find($outletID))) {
            return $this->sendError('Invalid Outlet ID', ['error' => 'Outlet Not Found!']);
        }

        foreach ($requisitionProduct as $requisition) {
            $validator = Validator::make($requisition, [
                'product_id' => 'required|integer',
                'product_quantity' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }


        // Execute after successfully validation =>
        return response()->json([
            'data' => $this->requisitionIssueRepository->newRequisition($request),
        ]);
    }


    public function editRequisition(Request $request)
    {
        $validator = Validator::make($request['requisition'], [
            'requisition_id' => 'required|integer',
            'outlet_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $productValidator = Validator::make($request['requisition']['product'][0], [
            'product_id' => 'required|integer',
            'product_quantity' => 'required|integer',
        ]);

        if ($productValidator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return response()->json([
            'data' => $this->requisitionIssueRepository->editARequisition($request),
        ]);
    }
    public function readOutletIssues(Request $request)
    {
        $validator = Validator::make($request['receive_data'], [
            'issue_id' => 'required',
            'productInfos' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        foreach ($request['receive_data']['productInfos'] as $receiveProduct) {
            $validator = Validator::make($receiveProduct, [
                'product_id' => 'required|integer',
                'product_quantity' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }

        // Execute after successfully validation =>
        return response()->json([
            'data' => $this->requisitionIssueRepository->readOutletIssues($request),
        ]);
    }

    public function outletIssues($outletID)
    {
        return response()->json([
            'data' => $this->requisitionIssueRepository->getOutletIssues($outletID),
        ]);
    }

    public function syncIssueByDateTime($outletID, $dateTime)
    {
        return response()->json([
            'data' => $this->requisitionIssueRepository->getIssueByDateTime($outletID, $dateTime),
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
