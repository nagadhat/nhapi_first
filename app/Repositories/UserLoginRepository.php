<?php

namespace App\Repositories;

use App\Interfaces\UserLoginRepositoryInterface;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;
use Validator;
use App\Traits\NhTraits;
use App\Traits\SmsTraits;
use App\Models\User;
use App\Models\UserCustomer;
use App\Models\TempUserCustomer;
use App\Models\Address_assign;
use App\Models\Address;
// use App\Models\Brand;
// use App\Models\categorie;
// use App\Models\FlashSaleProduct;
// use App\Models\ProductsCategorie;
// use App\Models\FlashSale;
// use App\Models\NhCustomer;


class UserLoginRepository extends BaseController implements UserLoginRepositoryInterface 
{
    use NhTraits;
    use SmsTraits;
    protected $user;
    protected $customer;
    protected $addressCodes;
    protected $address;
    protected $tempUserCustomer;
    // protected $product;
    // protected $brand;
    // protected $categorie;
    // protected $flash_sale;
    // protected $flash_sale_product;
    // protected $products_categorie;
    public function __construct(User $user, UserCustomer $customer, TempUserCustomer $tempUserCustomer, Address_assign $addressCodes, Address $address){
        $this->user = $user;
        $this->customer = $customer;
        $this->addressCodes = $addressCodes;
        $this->address = $address;
        $this->tempUserCustomer = $tempUserCustomer;
    }

    public function userLogin(Request $req)
    {
        // Login from single table as well as multiple role
        if(Auth::attempt(['username' => $req->username,'password' => $req->password])){
            if(Auth::user()->user_type == 'superAdmin'){               
                $msg = 'SuperAdminDashboard';
            } 
            if(Auth::user()->user_type == 'agent'){               
                $msg = 'AgentDashboard';
            }
            if(Auth::user()->user_type == 'user'){                               
                $msg = 'CustomerDashboard';
            }
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['user'] =  $user;
            return $this->sendResponse($success, $msg);         
        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised username or password.']);
        }
    }

    public function userLogout(Request $req){
        $user = Auth::user();
        Auth::user()->tokens()->delete();        
        return ['user'=>$user, 'login_status'=>false];
    }

    public function forgetPasswordOTP(Request $req){
        $validator = Validator::make($req->all(), [
            'username' => 'required|min:11,max:15',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user = $this->user::where('username', $req->username)->first();
        if (!$user) {
            return 'User not found!';
        }

        $tempUser = $this->CreatePasswordResetOTP($req);
        $smsContent = "Your Password Reset OTP for Nagadhat is " . $tempUser['user_otp_code'] . ".\nHelp line: 09602444444";
        $smsContent = $this->sendSingleSms($tempUser["username"], $smsContent);
        if($smsContent){
            return ['user_info'=>$user, 'sms_sending_status'=>true, 'otp'=>$tempUser['user_otp_code']];
        }
        return ['sms_sending_status'=>false];

    }

    public function forgetPasswordOtpVerification(Request $req){
        $result = $this->otpVerification($req);
        $this->tempUserCustomer::where('username', $result['user']['username'])->delete();
        return $result;
    }

    public function passwordReset(Request $req){
        $validator = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);     
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $user = $this->user::where('username', $req->username)->first();
        if (!$user) {
            return 'User not found!';
        }

        $user->password = bcrypt($req->password);
        if($user->save()){
            return ['user_info'=>$user, 'status'=>true, 'msg'=>'Password has been updated'];
        }
        return "Something wrong, can't update password!";

        // $user = $this->user::where('username', $req->username)->first();
        // $updatePassword = $user->update(['password'=>bcrypt($req->password)]);
        // return [$updatePassword, $user->password, $req->password];
    }

    public function userInfo()
    {
        $user = Auth::user();
        $user['first_name'] = $this->customer::where('username', Auth::user()->username)->pluck('first_name')->first();    
        return $user;
    }

    public function userAddressCodes()
    {
        return $this->addressCodes::where('username', Auth::user()->username)->pluck('address_id');
    }

    public function userAddress()
    {
        $addressIds = $this->addressCodes::where('username', Auth::user()->username)->pluck('address_id');
        return $this->address::whereIn('id', $addressIds)->get();
    }

    public function userInfoById($userId)
    {
        $user = $this->user::where('id', $userId)->first();
        if(!$user){
            return 'User not found!';
        }return $user;

        // return $this->user::findOrFail($userId);
    }

    public function userAddressCodesById($userId)
    {
        $addressCode = $this->addressCodes::where('user_id', $userId)->pluck('address_id')->toArray();
        if(!$addressCode || empty($addressCode)){
            return 'Address not found!';
        }
        return $addressCode;

        // return $this->addressCodes::where('user_id', $userId)->pluck('address_id');
    }

    public function userAddressByAddressId($addressId)
    {
        $address = $this->address::where('id', $addressId)->first();
        if(!$address || empty($address)){
            return 'Addresses not found!';
        }
        return $address;

        // return $this->addressCodes::where('user_id', $userId)->pluck('address_id');
    }

    // This function will create a new user
    // function createNewUser($userDetails)
    // {
    //     $userCreate = User::create([
    //         "name" => $userDetails["username"],            
    //         "email" => $userDetails["user_email"],            
    //         "password" => $userDetails["user_password"],
    //     ]);

    //     $userExtraInfo = NhCustomer::create([            
    //         "gender" => $userDetails["gender"],
    //         "first_name" => $userDetails["first_name"],
    //         "first_name" => $userDetails["last_name"],
    //         "phone_number" => $userDetails["username"],
    //     ]);
    //     return $userCreate;
    // }

    // public function regOtpVerification(Request $request)
    // {
    //     $userGivenOtp = $request["user-otp"];
    //     $tempUserFound = temp_user_customer::where("username", Session("tempUsername"))->where("user_otp_code", $userGivenOtp)->get();
    //     if (sizeof($tempUserFound) >= 1) {
    //         $userDetails["username"] = $tempUserFound[0]["username"];
    //         $userDetails["user_name"] = $tempUserFound[0]["user_name"];
    //         $userDetails["user_email"] = $tempUserFound[0]["user_email"];
    //         $userDetails["user_password"] = $tempUserFound[0]["user_password"];
    //         $userDetails["gender"] = $tempUserFound[0]["gender"];

    //         // create new user
    //         $newUserDetails = $this->createNewUser($userDetails);

    //         // affiliate user referance if signed up with a link
    //         if (session('referer')) {
    //             $findRefUser = affiliate_user::where('user_id', session('referer'))->first();
    //             $findRefUserData = user_customer::where('affiliate_id', $findRefUser->id)->first();
    //             $userDetails["referrer_id"] = $findRefUser->user_id;
    //             $findRefUser->update([
    //                 'refer_count' => $findRefUser->refer_count + 1,
    //             ]);
    //             $newUserDetails->update([
    //                 "referrer_id" => $userDetails["referrer_id"],
    //             ]);

    //             // create referance generation tree
    //             $createAffiliateRefGen = new AffiliateController();
    //             $getAffiliateGenInfo = $createAffiliateRefGen->getUserDetaisAndUpdateGen($findRefUserData, $newUserDetails);
    //         } else {
    //             // nagadhat default user's id is 8
    //             $newUserDetails->update([
    //                 "referrer_id" => 8,
    //             ]);
    //             // update refer count
    //             $defaultRefUserData = user_customer::find(8);
    //             $defaultRefUserData->update([
    //                 'refer_count' => $defaultRefUserData->refer_count + 1,
    //             ]);
    //         }
    //         // affiliate user referance if signed up with a link end


    //         // delete temp user data
    //         $tempUserData = temp_user_customer::where("username", Session("tempUsername"))->delete();
    //         // Save session for auto login
    //         if ($newUserDetails->id) {
    //             session([
    //                 'logedIn' => 1,
    //                 'userId' => $newUserDetails["id"],
    //                 'username' => $newUserDetails["username"],
    //                 'name' => $newUserDetails["first_name"],
    //                 'email' => $newUserDetails["email"],
    //             ]);
    //         }
    //         if (Session("createAccountDestination") != null) {
    //             return redirect(Session("createAccountDestination"));
    //         } else {
    //             return redirect()->route('home_dashboard');
    //         }
    //     } else {
    //         // User not Found or Otp not Verify
    //         alert()->error('User not found or Otp not Verified..', 'Error !!');
    //         return redirect()->route('websiteUrl');
    //     }
    // }

    // public function sendResponse($result, $message)
    // {
    //     $response = [
    //         'success' => true,
    //         'data'    => $result,
    //         'message' => $message,
    //     ];

    //     return response()->json($response, 200);
    // }

    // public function sendError($error, $errorMessages = [], $code = 404)
    // {
    //     $response = [
    //         'success' => false,
    //         'message' => $error,
    //     ];

    //     if(!empty($errorMessages)){
    //         $response['data'] = $errorMessages;
    //     }

    //     return response()->json($response, $code);
    // }
}