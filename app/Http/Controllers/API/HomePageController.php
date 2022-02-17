<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Repositories\HomePageRepository;
use Illuminate\Http\Response;

class HomePageController extends BaseController
{
    protected $homePageRepository;
    public function __construct(HomePageRepository $homePageRepository) 
    {
        $this->homePageRepository = $homePageRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/",
     *     tags={"Landing (home page)"},
     *     summary="Get home page view",
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorize Access, Invalid Token or Token has expired",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *     ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *           response=400,
     *           description="Bad Request"
     *          ),
     *      @OA\Response(
     *           response=404,
     *           description="not found"
     *          ),
     *      )
     *
     */

    function homePageView(){
        // // Get letest 6 product to new arrival product $newArrivalProductList array
        // $newArrivalProductList = product::where('target_audience', 0)->orderBy('id', 'desc')->take(10)->get();
        // // Get all categories which status is 1
        // $homePageHorizontalCat = categorie::where("home_page_top_menu", 1)->where('status', 1)->get();
        // // Get all categories which home_page_top_menu is 1
        // $onSlideCategoriesList = categorie::where("on_slider_menu_view", 1)->where('status', 1)->limit(10)->get();
        // // Finally return the view
        // return view("public.index", ["newArrivalProductList" => $newArrivalProductList, "homePageHorizontalCat" => $homePageHorizontalCat, "onSlideCategoriesList" => $onSlideCategoriesList]);

        return response()->json([
            'data' => $this->homePageRepository->homePageView()
        ]);
    }
}
