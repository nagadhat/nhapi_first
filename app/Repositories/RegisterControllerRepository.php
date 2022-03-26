<?php

namespace App\Repositories;

use App\Interfaces\RegisterControllerRepositoryInterface;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Traits\NhTraits;
use App\Traits\SmsTraits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Hash;
use App\Models\User;
use App\Models\TempUserCustomer;


class RegisterControllerRepository extends BaseController implements RegisterControllerRepositoryInterface
{
    use NhTraits;
    use SmsTraits;
    protected $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    public function registration(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed|min:6',
        ]);

        if($req->email){
            $validator = Validator::make($req->all(), [
                'email' => 'email',
            ]);
        }      
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        // Calling a method from NhTraits
        return $this->CreateTempCustomer($req);
    }

    public function regOtpVerification(Request $req){
        $validator = Validator::make($req->all(), [
            'username' => 'required|unique:users,username',
        ]);     
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $otpVerify = $this->otpVerification($req);

        if(!empty($otpVerify['status']) && $otpVerify['status'] == true){
            $userData = $this->CreateNewCustomer($otpVerify['user']);
            return $this->sendResponse($userData, 'New user created successfully');
        }
        return $this->sendError('Validation Error.', $otpVerify);     
    }
}