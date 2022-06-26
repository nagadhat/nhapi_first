<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface RequisitionIssueRepositoryInterface
{
    public function newRequisition($request);
    public function editARequisition($request);
    public function readOutletIssues($request);
    public function outletIssues($outletID);
    public function newOutletIssues($outletID);
    public function outletRequisitionsStatus($outletID);
    public function outletIssuesByRequisition($outletID, $reqID);
}
