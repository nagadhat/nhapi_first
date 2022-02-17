<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\RegisterControllerRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
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
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }

    function registration(Request $req){
        return response()->json([
            'data' => $this->registerControllerRepository->registration($req)
        ]);
    }

    function regOtpVerification(Request $req){
        return response()->json([
            'data' => $this->registerControllerRepository->regOtpVerification($req)
        ]);
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
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
            $success['user'] =  $user;
   
            return $this->sendResponse($success, 'User login successfully.');
        } else { 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
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
}
