<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface RequisitionIssueRepositoryInterface 
{
    public function newRequisition($request);
}