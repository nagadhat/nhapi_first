<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface UserCustomerRepositoryInterface 
{
    public function userDetailsByUserName(Request $request);
}