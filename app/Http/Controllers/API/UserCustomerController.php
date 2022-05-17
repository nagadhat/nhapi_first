<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\UserCustomerRepository;
use Illuminate\Http\Request;

class UserCustomerController extends Controller
{
    protected $userCustomerRepository;
    public function __construct(UserCustomerRepository $userCustomerRepository)
    {
        $this->userCustomerRepository = $userCustomerRepository;
    }

    function userDetailsByUserName(Request $request)
    {
        $userName = $request->route('userName');

        return response()->json([
            'data' => $this->userCustomerRepository->userDetailsByUserName($userName)
        ]);
    }
}
