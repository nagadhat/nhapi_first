<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\UserLoginRepository;
use App\Models\UserCustomer;
use App\Models\User;

class UserLoginController extends Controller
{
    protected $userLoginRepository;
    public function __construct(UserLoginRepository $userLoginRepository)
    {
        $this->userLoginRepository = $userLoginRepository;
    }

    /**
     * @OA\Post(
     * path="/api/nh-login",
     * summary="NH Login",
     * description="Login by username, password. Username must be phone number and 11 digit.",
     * operationId="nh-login",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials (username and password).",
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", example="01xxxxxxxxx"),
     *       @OA\Property(property="password", type="string", format="password", example="12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthorised username or password.")
     *        )
     *     )
     * )
     */
    function userLogin(Request $req)
    {
        // return $this->userLoginRepository->userLogin($req);
        return response()->json([
            'data' => $this->userLoginRepository->userLogin($req)
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/nh-logout",
     * summary="NH Logout",
     * description="Logout user and invalidate token",
     * operationId="nh logout",
     * tags={"Authentication"},
     * security={{"passport": {}}},
     * @OA\Response(
     *    response=200,
     *    description="Success"
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="string", example="basic user info"),
     *       @OA\Property(property="login_status", type="string", example="false"),
     *    )
     * )
     * )
     */

    function userLogout(Request $req)
    {
        return response()->json([
            'data' => $this->userLoginRepository->userLogout($req)
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/nh-forget-password-otp",
     * summary="Get OTP for Forget Password",
     * description="This end-point will provide OTP for Forget Password. You have to pass body param with the key name 'username' and it must be a valid phone number, already registered.",
     * operationId="nh-forget-password-otp",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", example="01xxxxxxxxx"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    function forgetPasswordOTP(Request $req)
    {
        return response()->json([
            'data' => $this->userLoginRepository->forgetPasswordOTP($req)
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/forget-password-otp-verify",
     * summary="Verify OTP for Forget Password",
     * description="For verify the 'Forget Password OTP' you have to pass body param with the key 'username' and 'user_otp'.",
     * operationId="forget-password-otp-verify",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", example="01xxxxxxxxx"),
     *       @OA\Property(property="user_otp", type="string", example="12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    function forgetPasswordOtpVerification(Request $req)
    {
        return response()->json([
            'data' => $this->userLoginRepository->forgetPasswordOtpVerification($req)
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/password-reset",
     * summary="Update Password",
     * description="This end-point will update previous password with given password. You have to pass body param with the key 'username', 'password' and 'password_confirmation'.",
     * operationId="password-reset",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", example="01xxxxxxxxx"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="123456"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="This will return user_info, msg, status",
     *    @OA\JsonContent(
     *       @OA\Property(property="user_info", type="string", example="Basic information of the user"),
     *       @OA\Property(property="status", type="string", example="true"),
     *       @OA\Property(property="msg", type="string", example="Password has been updated")
     *        )
     *     )
     * )
     */
    function passwordReset(Request $req)
    {
        return response()->json([
            'data' => $this->userLoginRepository->passwordReset($req)
        ]);
    }

    function userInfo()
    {
        return response()->json([
            'data' => $this->userLoginRepository->userInfo()
        ]);
    }

    function userAddressCodes()
    {
        return response()->json([
            'data' => $this->userLoginRepository->userAddressCodes()
        ]);
    }

    function userAddress()
    {
        return response()->json([
            'data' => $this->userLoginRepository->userAddress()
        ]);
    }

    function userInfoById(Request $request)
    {
        // $userId = $request->userId;
        $userId = $request->route('userId');

        return response()->json([
            'data' => $this->userLoginRepository->userInfoById($userId)
        ]);
    }

    function userAddressCodesById(Request $request)
    {
        // $userId = $request->userId;
        $userId = $request->route('userId');

        return response()->json([
            'data' => $this->userLoginRepository->userAddressCodesById($userId)
        ]);
    }

    function userAddressByAddressId(Request $request)
    {
        // $userId = $request->userId;
        $addressId = $request->route('addressId');

        return response()->json([
            'data' => $this->userLoginRepository->userAddressByAddressId($addressId)
        ]);
    }

    public function copyCustomersToUsers()
    {
        $users = UserCustomer::where('status', 1)->get();
        if (!$users) {
            return 'No user found!';
        }
        // return count($users);

        $totalUsers = [];
        foreach ($users as $user) {
            $findUserExists = User::where('username', $user->username)->first();
            $uniqId = uniqid($user->id);

            $duplicateUser = 0;
            if ($findUserExists) {
                $duplicateUser = $duplicateUser + 1;
            } else {
                $newUser = User::create([
                    'username'  => $user->username,
                    'password'  => $user->password,
                    'email'     => $user->email,
                    'phone'     => $user->username,
                    'user_type' => 'user',
                    'status'    => $user->status,
                    'unique_key' => $uniqId,
                ]);

                $updateUserCustomer = UserCustomer::where('id', $user->id)->update(['user_id' => $newUser->id]);
                $totalUsers[] = $newUser;
            }
        }
        return 'Operation Done! Duplicate Users Found: ' . $duplicateUser . ', Total User: ' . count($users) . ', Inserted User: ' . count($totalUsers);
    }
}
