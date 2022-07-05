<?php

namespace App\Repositories;

use App\Interfaces\RequisitionIssueRepositoryInterface;
use App\Models\Outlet;
use App\Models\OutletRequisitionProduct;
use App\Models\OutletRequisition;
use App\Models\OutletProduct;
use App\Models\OutletIssue;
use App\Models\OutletIssueProduct;
use App\Models\OutletReceive;
use App\Models\OutletReceiveProduct;
use App\Models\Product;

class RequisitionIssueRepository implements RequisitionIssueRepositoryInterface
{
    protected $outletRequisition;
    protected $outletRequisitionProduct;
    protected $outletProduct;
    protected $outletIssue;
    protected $outletIssueProduct;
    public function __construct(Outlet $outlet, OutletRequisition $outletRequisition, OutletRequisitionProduct $outletRequisitionProduct, OutletProduct $outletProduct, OutletIssue $outletIssue, OutletIssueProduct $outletIssueProduct, Product $product, OutletReceive $outletReceive, OutletReceiveProduct $outletReceiveProduct)
    {
        $this->outlet = $outlet;
        $this->outletRequisition = $outletRequisition;
        $this->outletRequisitionProduct = $outletRequisitionProduct;
        $this->outletProduct = $outletProduct;
        $this->outletIssue = $outletIssue;
        $this->outletIssueProduct = $outletIssueProduct;
        $this->product = $product;
        $this->outletReceive = $outletReceive;
        $this->outletReceiveProduct = $outletReceiveProduct;
    }

    public function newRequisition($request)
    {
        $outletID =  $request['requisition']['outlet_id'];
        $requisitionProduct = $request['requisition']['product'];

        // check if product_id valid or not
        $noProduct = [];
        foreach ($requisitionProduct as $requisition) {
            $checkProduct = $this->product::find($requisition['product_id']);
            if (!$checkProduct) {
                $noProduct[] = $requisition['product_id'];
            }
        }
        if (!empty($noProduct)) {
            return [
                'msg' => 'Product not found !!',
                'product_id' => $noProduct
            ];
        }

        $createRequisition = $this->outletRequisition::create([
            'outlet_id' => $request['requisition']['outlet_id']
        ]);

        $newRequisition = array();
        foreach ($requisitionProduct as $requisition) {
            // $requisition['outlet_id'] = $outletID;
            $requisition['requisition_id'] = $createRequisition->id;
            $requisition['issued_quantity'] = 0;
            $requisition['remaining_quantity'] = $requisition['product_quantity'];
            $newRequisition[] = $this->outletRequisitionProduct::create($requisition);

            $exist_in_outlet = $this->outletProduct::where('product_id', $requisition['product_id'])->where('outlet_id', $outletID)->first();
            if (empty($exist_in_outlet)) {
                $outlet_product['outlet_id'] = $outletID;
                $outlet_product['product_id'] = $requisition['product_id'];
                $this->outletProduct::create($outlet_product);
            }
        }

        return $newRequisition;
    }

    public function editARequisition($request)
    {
        $outlet_id =  $request['requisition']['outlet_id'];
        $requisition_id =  $request['requisition']['requisition_id'];
        $requisitionProduct = $request['requisition']['product'];

        $requisition = $this->outletRequisition::find($requisition_id);
        if (empty($requisition)) {
            return 'invalid requisition_id';
        }

        if (empty($this->outlet::find($outlet_id)) || $requisition->outlet_id != $outlet_id) {
            return 'invalid outlet_id';
        }

        if ($requisition->partial_status) {
            return 'requisition already issued, invalid request';
        }

        $this->outletRequisitionProduct::where('requisition_id', $requisition_id)->delete();

        // check if product_id valid or not
        $noProduct = [];
        foreach ($requisitionProduct as $req) {
            $checkProduct = $this->product::find($req['product_id']);
            if (!$checkProduct) {
                $noProduct[] = $req['product_id'];
            }
        }
        if (!empty($noProduct)) {
            return [
                'msg' => 'Product not found !!',
                'product_id' => $noProduct
            ];
        }


        $newRequisitionProduct = array();
        foreach ($requisitionProduct as $req) {
            $req['requisition_id'] = $requisition_id;
            $req['issued_quantity'] = 0;
            $req['remaining_quantity'] = $req['product_quantity'];
            $newRequisitionProduct[] = $this->outletRequisitionProduct::create($req);

            $exist_in_outlet = $this->outletProduct::where('product_id', $req['product_id'])->where('outlet_id', $outlet_id)->first();
            if (empty($exist_in_outlet)) {
                $outlet_product['outlet_id'] = $outlet_id;
                $outlet_product['product_id'] = $req['product_id'];
                $this->outletProduct::create($outlet_product);
            }
        }


        return [
            'msg' => 'updated successfully',
            'requisition' => $requisition,
            'products' => $newRequisitionProduct
        ];
    }

    // If POS send issue_id, product_id and product_quantity
    public function readOutletIssues($request)
    {
        $issue_id = $request['receive_data']['issue_id'];
        $productInfos = $request['receive_data']['productInfos'];
        $issue = $this->outletIssue::find($issue_id);

        if (empty($issue)) {
            return 'Issue Not Found!';
        }


        // check if product_id valid or not
        $noProduct = [];
        foreach ($productInfos as $issueProduct) {
            $checkProduct = $this->outletIssueProduct::where('issue_id', $issue_id)->where('product_id', $issueProduct['product_id'])->first();
            if (!$checkProduct) {
                $noProduct[] = $issueProduct['product_id'];
            }
        }
        if (!empty($noProduct)) {
            return [
                'msg' => 'Product not found !!',
                'product_id' => $noProduct
            ];
        }

        // Find, If the issue already received or not
        if ($issue->read_by_pos == 1) {
            return 'Sorry, You have already received the issue!';
        }
        $issue->read_by_pos = 1;
        $issue->save();

        // Create record in Outlet_Receive table
        $receiveDatas['issue_id'] = $issue_id;
        $receiveDatas['requisition_id'] = $issue->requisition_id;
        $receiveDatas['outlet_id'] = $issue->outlet_id;
        $receiveDatas['amount'] = $issue->amount;
        $outletReceive = $this->outletReceive::create($receiveDatas);

        foreach ($productInfos as $issuedProduct) {
            $outletProductdata = $this->outletProduct::where('outlet_id', $issue->outlet_id)->where('product_id', $issuedProduct['product_id'])->first();
            if (!empty($outletProductdata)) {
                // Increase outlet products in Outlet_Products table
                $outletProductdata->update([
                    'quantity' => $outletProductdata->quantity + $issuedProduct['product_quantity'],
                ]);
            } else {
                // oe create initial quantity
                $this->outletProduct->create([
                    'outlet_id' => $issue->outlet_id,
                    'product_id' => $issuedProduct['product_id'],
                    'quantity' => $issuedProduct['product_quantity']
                ]);
            }

            // Create record in Outlet_Receive_Products table
            $receiveProductDatas['issue_id'] = $issue_id;
            $receiveProductDatas['receive_id'] = $outletReceive->id;
            $receiveProductDatas['product_id'] = $issuedProduct['product_id'];
            $receiveProductDatas['product_quantity'] = $issuedProduct['product_quantity'];
            $receiveProductDatas['purchase_price'] = $issuedProduct['purchase_price'];
            $outletReceiveProduct = $this->outletReceiveProduct::create($receiveProductDatas);
        }
        return "Successfully received the issue and updated product quantity!";
    }

    // If POS send only issue_id
    public function readOutletIssues2($request)
    {
        $issue = $this->outletIssue::find($request->issue_id);
        if ($issue->read_by_pos == 1) {
            return 'Sorry, You have already received the issue!';
        }
        $issue->read_by_pos = 1;
        $issue->save();

        $issuedProducts = OutletIssueProduct::where('issue_id', $request->issue_id)->get();
        foreach ($issuedProducts as $issuedProduct) {
            $outletProduct = OutletProduct::where('outlet_id', $issue->outlet_id)->where('product_id', $issuedProduct->product_id)->first();
            if (!empty($outletProduct)) {
                $newProductQuantity = $outletProduct->quantity + $issuedProduct->product_quantity;
            } else {
                $newProductQuantity = $issuedProduct->product_quantity;
            }

            // Increase outlet products in Outlet_Products table
            OutletProduct::updateOrCreate(
                ['outlet_id' => $issue->outlet_id, 'product_id' => $issuedProduct->product_id],
                ['quantity' => $newProductQuantity]
            );
        }
        return "Successfully received the issue and updated product quantity!";
    }

    public function getOutletIssues($outletID)
    {
        $issues = $this->outletIssue::where('outlet_id', $outletID)->get();

        for ($i = 0; $i < count($issues); $i++) {
            $issuedProducts = $this->outletIssueProduct::where('issue_id', $issues[$i]->id)->get();

            $items = array();
            foreach ($issuedProducts as $issuedProduct) {
                $items[] = array(
                    'product_id' => $issuedProduct->product_id,
                    'product_quantity' => $issuedProduct->product_quantity,
                    'purchase_price' => $issuedProduct->purchase_price,
                );
            }

            $issues[$i]['products'] = $items;
        }

        return $issues;
    }

    public function getIssueByDateTime($outletID, $dateTime)
    {
        $issues = $this->outletIssue::where('outlet_id', $outletID)->where('updated_at', '>', $dateTime)->get();

        // $outletIssues = array();
        for ($i = 0; $i < count($issues); $i++) {
            $issuedProducts = $this->outletIssueProduct::where('issue_id', $issues[$i]->id)->get();

            $items = array();
            foreach ($issuedProducts as $issuedProduct) {
                $items[] = array(
                    'product_id' => $issuedProduct->product_id,
                    'product_quantity' => $issuedProduct->product_quantity,
                    'purchase_price' => $issuedProduct->purchase_price,
                );
            }

            $issues[$i]['products'] = $items;
        }

        return $issues;
    }

    public function newOutletIssues($outletID)
    {
        // return 'ok';
        $issues = $this->outletIssue::where('outlet_id', $outletID)->where('read_by_pos', 0)->get();
        // return $issues;

        // $outletIssues = array();
        for ($i = 0; $i < count($issues); $i++) {
            $issuedProducts = $this->outletIssueProduct::where('issue_id', $issues[$i]->id)->get();

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

    public function outletIssuesByRequisition($outletID, $reqID)
    {
        // return 'ok';
        $issues = $this->outletIssue::where('outlet_id', $outletID)->where('requisition_id', $reqID)->get();
        // return $issues;

        // $outletIssues = array();
        for ($i = 0; $i < count($issues); $i++) {
            $issuedProducts = $this->outletIssueProduct::where('issue_id', $issues[$i]->id)->get();

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

    public function outletRequisitionsStatus($outletID)
    {
        $requisitions = $this->outletRequisition::where('outlet_id', $outletID)->get();
        return $requisitions;
    }
}
