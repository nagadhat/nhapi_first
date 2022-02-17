<?php

namespace App\Repositories;

use App\Interfaces\RegisterControllerRepositoryInterface;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Traits\NhTraits;
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
        $tempCustomer = $this->CreateTempCustomer($req);
        return $tempCustomer;   
        
        // $success['token'] =  $user->createToken('MyApp')->accessToken;
        // $success['name'] =  $user->name;
    }

    public function regOtpVerification(Request $req){
        $otpVerify = $this->otpVerification($req);

        if($otpVerify['status']){
            return $this->CreateNewCustomer($otpVerify['user']);
        }
        return $otpVerify;      
    }
}