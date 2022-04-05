<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\OutletRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OutletController extends BaseController
{
    protected $outletRepository;
    public function __construct(OutletRepository $outletRepository)
    {
        $this->outletRepository = $outletRepository;
    }

    function getOutlet()
    {
        return response()->json([
            'data' => $this->outletRepository->getOutlet()
        ]);
    }

    function getOutletById(Request $request)
    {
        $outletId = $request->route('outletId');
        return response()->json([
            'data' => $this->outletRepository->getOutletById($outletId)
        ]);
    }
}
