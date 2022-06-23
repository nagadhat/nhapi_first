<?php

namespace App\Repositories;

use App\Interfaces\UserLoginRepositoryInterface;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\NhTraits;
use App\Traits\SmsTraits;
use App\Models\User;
use App\Models\UserCustomer;
use App\Models\TempUserCustomer;
use App\Models\Address_assign;
use App\Models\Address;
use App\Models\AffiliateBankingInfo;
use App\Models\AffiliateUser;
use App\Models\NhAdmin;
use App\Models\NhAgent;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class UserLoginRepository extends BaseController implements UserLoginRepositoryInterface
{
    use NhTraits;
    use SmsTraits;
    protected $user;
    protected $customer;
    protected $addressCodes;
    protected $address;
    protected $tempUserCustomer;
    public function __construct(User $user, UserCustomer $customer, TempUserCustomer $tempUserCustomer, Address_assign $addressCodes, Address $address, AffiliateUser $affiliateUser, AffiliateBankingInfo $affiliateBankingInfo, NhAdmin $nhAdmin, NhAgent $nhAgent, Vendor $vendor)
    {
        $this->user = $user;
        $this->customer = $customer;
        $this->addressCodes = $addressCodes;
        $this->address = $address;
        $this->tempUserCustomer = $tempUserCustomer;
        $this->affiliateUser = $affiliateUser;
        $this->affiliateBankingInfo = $affiliateBankingInfo;
        $this->nhAdmin = $nhAdmin;
        $this->nhAgent = $nhAgent;
        $this->vendor = $vendor;
    }

    public function userLogin(Request $req)
    {
        // Login from single table as well as multiple role
        if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
            $msg = 'NagadhatUser';
            if (Auth::user()->user_type == 'superAdmin') {
                $msg = 'SuperAdminDashboard';
            }
            if (Auth::user()->user_type == 'agent') {
                $msg = 'AgentDashboard';
            }
            if (Auth::user()->user_type == 'user') {
                $msg = 'CustomerDashboard';
            }
            Auth::user()->tokens()->delete();
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['user'] =  $user;
            return $this->sendResponse($success, $msg);
        } else {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized username or password.']);
        }
    }

    public function userLogout(Request $req)
    {
        $user = Auth::user();
        Auth::user()->tokens()->delete();
        return ['user' => $user, 'login_status' => false];
    }

    public function forgetPasswordOTP(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'username' => 'required|min:11,max:15',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = $this->user::where('username', $req->username)->first();
        if (!$user) {
            return 'User not found!';
        }

        $tempUser = $this->CreatePasswordResetOTP($req);
        $smsContent = "Your Password Reset OTP for Nagadhat is " . $tempUser['user_otp_code'] . ".\nHelp line: 09602444444";
        $smsContent = $this->sendSingleSms($tempUser["username"], $smsContent);
        if ($smsContent) {
            return ['user_info' => $user, 'sms_sending_status' => true, 'otp' => $tempUser['user_otp_code']];
        }
        return ['sms_sending_status' => false];
    }

    public function forgetPasswordOtpVerification(Request $req)
    {
        $result = $this->otpVerification($req);
        $this->tempUserCustomer::where('username', $result['user']['username'])->delete();
        return $result;
    }

    public function passwordReset(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = $this->user::where('username', $req->username)->first();
        if (!$user) {
            return 'User not found!';
        }

        $user->password = bcrypt($req->password);
        if ($user->save()) {
            return ['user_info' => $user, 'status' => true, 'msg' => 'Password has been updated'];
        }
        return "Something wrong, can't update password!";
    }

    public function userInfo()
    {
        $user = Auth::user();
        $admin = $this->nhAdmin::where('user_id', auth()->user()->id)->first();
        if ($admin) {
            $user['admin_info'] = $admin;
        }
        $agent = $this->nhAgent::where('uid', auth()->user()->id)->first();
        if ($agent) {
            $user['agent_info'] = $agent;
        }
        $vendor = $this->vendor::where('u_id', auth()->user()->id)->first();
        if ($vendor) {
            $user['vendor_info'] = $vendor;
        }


        $customer = $this->customer::where('u_id', $user->id)->first();
        if ($customer) {
            $user['customer_info'] = $customer;
            $affiliate = $this->affiliateUser::where('user_id', $customer->id)->first();
            if ($affiliate) {
                $user['affiliate_info'] = $affiliate;
            }
            $bankingInfo = $this->affiliateBankingInfo::where('user_id', $customer->id)->first();
            if ($bankingInfo) {
                $user['banking_info'] = $bankingInfo;
            }
        }

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
        if (!$user) {
            return 'User not found!';
        }
        return $user;
    }

    public function userAddressCodesById($userId)
    {
        $userCustomerId =  $this->customer::where('u_id', $userId)->first();
        if (isset($userCustomerId)) {
            $addressCode = $this->addressCodes::where('user_id', $userCustomerId->id)->pluck('address_id')->toArray();
            if (!$addressCode || empty($addressCode)) {
                return 'Address not found!';
            }
            return $addressCode;
        } else {
            return "Invalid userId";
        }
    }

    public function userAddressByAddressId($addressId)
    {
        $address = $this->address::where('id', $addressId)->first();
        if (!$address || empty($address)) {
            return 'Addresses not found!';
        }
        return $address;
    }
}
