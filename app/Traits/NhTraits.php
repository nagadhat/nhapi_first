<?php

namespace App\Traits;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TempUserCustomer;
use App\Models\User;
use Validator;

trait NhTraits
{
    public function FilterMobileNumber($number) {        
        $phoneNumber = $number;
        if (Str::startsWith($phoneNumber, "+8801")) {
            $phoneNumber = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace('-', '', Str::substrReplace($phoneNumber, '', 0, 3)));
        }
        $phoneNumber = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace('-', '', $phoneNumber));
        return $phoneNumber;
    }

    public function CreateTempCustomer(Request $req)
    {
        $input['username'] = $this->FilterMobileNumber($req->username);
        $input['user_password'] = bcrypt($req->password);
        $input['user_otp_code'] = rand(11111,99999);
        if($req->email){
            $input['user_email'] = $req->email;
        }
        $tempUser = TempUserCustomer::create($input);

        $smsContent = "Nagadhat Registration OTP is " . $tempUser['user_otp_code'] . ".\nHelp line: 09602444444";
        $smsContent = $this->sendSingleSms($tempUser["username"], $smsContent);
        if($smsContent){
            return ['user_info'=>$tempUser, 'sms_sending_status'=>true, 'otp'=>$tempUser['user_otp_code']];
        }
        return ['user_info'=>$tempUser, 'sms_sending_status'=>false];
    }

    public function CreateNewCustomer($req)
    {
        $input['username'] = $req->username;
        $input['password'] = $req->user_password;
        $input['user_type'] = 'customer';
        if($req->user_email){
            $input['email'] = $req->user_email;
        }       
        $user = User::create($input);
        $token =  $user->createToken('MyApp')->accessToken;
        $deleteTempUser = TempUserCustomer::where('username', $req->username)->delete();
        return ['msg'=>'New user created successfully', 'user_info'=>$user, 'token'=>$token];
    }

    public function CreatePasswordResetOTP(Request $req)
    {
        $input['username'] = $this->FilterMobileNumber($req->username);
        $input['user_otp_code'] = rand(11111,99999);

        $tempUser = TempUserCustomer::create($input);    
        return $tempUser;
    }

    public function otpVerification(Request $req){
        $validator = Validator::make($req->all(), [
            'username' => 'required',
            'user_otp' => 'required|min:5',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $tempUser = TempUserCustomer::where('username', $req->username)->get()->toArray();

        if ($tempUser && !empty($tempUser)) {
            $user = TempUserCustomer::where('user_otp_code', $req->user_otp)->first();
            if(!empty($user)){
                return ['user'=>$user, 'status'=>true, 'msg'=>'Valide OTP'];
            }
            return ['status'=>false, 'msg'=>'Incorrect OTP'];
        }
        return ['status'=>false, 'msg'=>'User not found.'];       
    }
}