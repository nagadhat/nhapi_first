<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface RequisitionIssueRepositoryInterface
{
    public function newRequisition($request);
    public function editARequisition($request);
    public function readOutletIssues($request);
    public function getOutletIssues($outletID);
    public function getIssueByDateTime($outletID, $dateTime);
    public function newOutletIssues($outletID);
    public function outletRequisitionsStatus($outletID);
    public function outletIssuesByRequisition($outletID, $reqID);
}
