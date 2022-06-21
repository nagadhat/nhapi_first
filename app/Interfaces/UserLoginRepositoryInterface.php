<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface UserLoginRepositoryInterface
{
    public function userLogin(Request $req);
    public function userLogout(Request $req);
    public function userInfo();
    public function forgetPasswordOTP(Request $req);
    public function forgetPasswordOtpVerification(Request $req);
    public function passwordReset(Request $req);
    public function userAddressCodes();
    public function userAddress();
    public function userInfoById(Request $request);
    public function userAddressCodesById(Request $request);
    public function userAddressByAddressId(Request $request);
    // public function regOtpVerification(Request $request);
}
