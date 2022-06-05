<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\RegisterControllerRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    protected $registerControllerRepository;
    public function __construct(RegisterControllerRepository $registerControllerRepository)
    {
        $this->registerControllerRepository = $registerControllerRepository;
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"Authentication"},
     *      summary="Register",
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }




    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="login",
     * tags={"Authentication"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="salam.pustcse@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="12345"),
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
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Logout",
     * description="Logout user and invalidate token",
     * operationId="logout",
     * tags={"Authentication"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success"
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Not authorized"),
     *    )
     * )
     * )
     */

    public function logout(Request $request)
    {
        $user = Auth::user();
        $success['name'] =  $user->name;
        Auth::user()->tokens()->delete();
        return $this->sendResponse($success, 'User logout successfully.');
    }

    /**
     * @OA\Post(
     *      path="/api/nh-registration",
     *      operationId="nh-registration",
     *      tags={"Authentication"},
     *      summary="NH Registration",
     *      description="This end-point will be used for registration new user. You have to provide body params with the keys of- 'username', 'password', 'password_confirmation', and 'email' (Optional). The 'username' must be a valid phone number and password length at least 6 digit. After registration, system will send an OTP to given username (phone), that you must verify in '/api/registration-otp-verify'.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username","password","password_confirmation","email"},
     *              @OA\Property(property="username", type="string", example="01xxxxxxxxx"),
     *              @OA\Property(property="password", type="string", format="password", example="123456"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="123456"),
     *              @OA\Property(property="email", type="string", format="email", example="name@domain.com"),
     *              @OA\Property(property="user_type", type="string", format="string", example="customer"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    function registration(Request $req)
    {
        return response()->json([
            'data' => $this->registerControllerRepository->registration($req)
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/registration-otp-verify",
     *      operationId="registration-otp-verify",
     *      tags={"Authentication"},
     *      summary="Verify Registration OTP",
     *      description="This end-point will be used for verify Registration OTP.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username","user_otp"},
     *              @OA\Property(property="username", type="string", example="01xxxxxxxxx"),
     *              @OA\Property(property="user_otp", type="string", example="12345"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    function regOtpVerification(Request $req)
    {
        $user_exist = User::where('username', $req->username)->first();
        if ($user_exist) {
            return $this->sendError('Failed.', ['error' => 'User already exist']);
        } else return response()->json([
            'data' => $this->registerControllerRepository->regOtpVerification($req)
        ]);
    }
}
