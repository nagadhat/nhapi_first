<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface UserLoginRepositoryInterface 
{
    public function userLogin(Request $req);
    public function userLogout(Request $req);
    public function userInfo(Request $req);
    public function forgetPasswordOTP(Request $req);
    public function forgetPasswordOtpVerification(Request $req);
    // public function regOtpVerification(Request $request);
}