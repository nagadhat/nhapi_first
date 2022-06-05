<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TempUserCustomer;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

trait NhTraits
{
    public function FilterMobileNumber($number)
    {
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
        $input['user_otp_code'] = rand(11111, 99999);
        $input['user_type'] = $req->user_type;
        if ($req->email) {
            $input['user_email'] = $req->email;
        }
        $tempUser = TempUserCustomer::create($input);

        $smsContent = "Nagadhat Registration OTP is " . $tempUser['user_otp_code'] . ".\nHelp line: 09602444444";
        $smsContent = $this->sendSingleSms($tempUser["username"], $smsContent);
        if ($smsContent) {
            return ['user_info' => $tempUser, 'sms_sending_status' => true, 'otp' => $tempUser['user_otp_code']];
        }
        return ['user_info' => $tempUser, 'sms_sending_status' => false];
    }

    public function CreateNewCustomer($req)
    {
        $input['username']  = $req->username;
        $input['phone']  = $req->username;
        $input['password']  = $req->user_password;
        $input['user_type'] = $req->user_type;
        $input['status'] = 1;
        $input['unique_key'] = uniqid();
        if ($req->user_email) {
            $input['email'] = $req->user_email;
        }
        $user = User::create($input);
        $token =  $user->createToken('MyApp')->accessToken;
        $deleteTempUser = TempUserCustomer::where('username', $req->username)->delete();
        return ['user_info' => $user, 'token' => $token];
    }

    public function CreatePasswordResetOTP(Request $req)
    {
        $input['username'] = $this->FilterMobileNumber($req->username);
        $input['user_otp_code'] = rand(11111, 99999);

        $tempUser = TempUserCustomer::create($input);
        return $tempUser;
    }

    public function otpVerification(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'username' => 'required',
            'user_otp' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        }

        $tempUser = TempUserCustomer::where('username', $req->username)->get()->toArray();

        if ($tempUser && !empty($tempUser)) {
            $user = TempUserCustomer::where('user_otp_code', $req->user_otp)->first();
            if (!empty($user)) {
                return ['user' => $user, 'status' => true, 'msg' => 'Valide OTP'];
            }
            return ['status' => false, 'msg' => 'Incorrect OTP'];
        }
        return ['status' => false, 'msg' => 'User not found.'];
    }

    // Upload File to public/media/products/images
    // $path = "\storage\app\public\media\products\images
    function uploadAndGetPath($file, $path = "/public/media/products/images")
    {
        // $destination = storage_path().'/public/new/'.$image_name;
        $image = $file;
        $actual_image = $image->getClientOriginalName();
        $filename_image = time() . '_' . $actual_image;
        $path = $image->storeAs($path, $filename_image, 'storage_in_customer');
        // $path = $image->storeAs($path, $filename_image, 'public_web_url');
        //$path = $file->store($path);
        // Remove /public from $path
        //$path = substr($path, 7);
        return "media/products/images/" . $filename_image;
    }

    function uploadFile($file, $path, $return_path)
    {
        $actual_image = $file->getClientOriginalName();
        $filename_image = time() . '_' . $actual_image;
        $path = $file->storeAs($path, $filename_image, 'storage_in_customer');

        return $return_path . $filename_image;
    }
}
