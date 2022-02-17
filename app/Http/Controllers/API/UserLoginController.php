<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\UserLoginRepository;

class UserLoginController extends Controller
{
    protected $userLoginRepository;
    public function __construct(UserLoginRepository $userLoginRepository) 
    {
        $this->userLoginRepository = $userLoginRepository;
    }

    function userLogin(Request $req){
        return $this->userLoginRepository->userLogin($req);
    }

    function userLogout(Request $req){
        return response()->json([
            'data' => $this->userLoginRepository->userLogout($req)
        ]);
    }

    function forgetPasswordOTP(Request $req){
        return response()->json([
            'data' => $this->userLoginRepository->forgetPasswordOTP($req)
        ]);
    }

    function forgetPasswordOtpVerification(Request $req){
        return response()->json([
            'data' => $this->userLoginRepository->forgetPasswordOtpVerification($req)
        ]);
    }

    function userInfo(Request $req){
        return response()->json([
            'data' => $this->userLoginRepository->userInfo($req)
        ]);
    }
    
}
