<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\ProductCategoryRepository;

class ProductCategoryController extends Controller
{
    protected $productCategoryRepository;
    public function __construct(ProductCategoryRepository $productCategoryRepository) 
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/all-category",
     *     tags={"Products & Categories"},
     *     summary="Get category full list or short list as your requirment.",
     *     description="By default this end-point will provide a short list of category with three column ('id', 'title', 'slug'). For full list you have to provide a body param with the key 'list_type' and value = full_list. Pass body param with the key 'slider_menu' and value = 'menu_view' to get all the categories for Slider-Menu.  Used in: index",
     *     @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"list_type"},
     *              @OA\Property(property="list_type", type="string", example="short_list"),
     *          ),
     *     ),
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

    function categories(Request $list_type){
        return response()->json([
            'data' => $this->productCategoryRepository->categories($list_type)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-category-top-menu",
     *     tags={"Products & Categories"},
     *     summary="Get category list for home_page_top_menu.",
     *     description="By default this end-point will provide category list where 'home_page_top_menu' = 1. This response used in just beneath the main slide  Used in: index",
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

    function categoriesTopMenu(){
        return response()->json([
            'data' => $this->productCategoryRepository->categoriesTopMenu()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-category-slide",
     *     tags={"Products & Categories"},
     *     summary="Get category list for the menu just left side in the main slider.",
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

    function categoriesSlide(){
        return response()->json([
            'data' => $this->productCategoryRepository->categoriesSlide()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-product-new",
     *     tags={"Products & Categories"},
     *     summary="Get new product list with target audience 0.",
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

    function newProducts(){
        return response()->json([
            'data' => $this->productCategoryRepository->newProducts()
        ]);
    }

     /**
     * @OA\Get(
     *     path="/api/all-product-flash-sale",
     *     tags={"Products & Categories"},
     *     summary="Get Flash Sale product list.",
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

    function flashSaleProducts(){
        return response()->json([
            'data' => $this->productCategoryRepository->flashSaleProducts()
        ]);
    }

     /**
     * @OA\Get(
     *     path="/api/all-product-flashsale-info",
     *     tags={"Products & Categories"},
     *     summary="Get Flash Sale info.",
     *     description="Get Flash Sale info like, Flash Sale active or not, Flash Sale start and end date.",
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

    function flashSaleInfo(){
        return response()->json([
            'data' => $this->productCategoryRepository->flashSaleInfo()
        ]);
    }

    function flashSaleStatus(){
        return response()->json([
            'data' => $this->productCategoryRepository->flashSaleStatus()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/all-product-by-category-id",
     *     tags={"Products & Categories"},
     *     summary="Get product list according to category ID.",
     *     description="By default this end-point will provide 10 record according to given category ID. Giving an integer category ID with key 'category_id' is must. Then you can get custom number of record by providing a body param with the key 'number_of_records' & an integer as value. You may also get random record back by providing a body param with the key 'random' = true. Used in: index",
     *     @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"list_type"},
     *              @OA\Property(property="list_type", type="string", example="short_list"),
     *          ),
     *     ),
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

    function productByCategoryID(Request $request){
        return response()->json([
            'data' => $this->productCategoryRepository->productByCategoryID($request)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/all-category-main",
     *     tags={"Products & Categories"},
     *     summary="Get only mother category from categories table.",
     *     description="By default this end-point will provide 10 mother category from categories table. For custom number of records you have to provide a body param with the key 'limit' and value must be an integer.  Used in: index",
     *     @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *          @OA\JsonContent(
     *              required={"limit"},
     *              @OA\Property(property="limit", type="integer", example=5),
     *          ),
     *     ),
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
    function mainCategories(Request $req){
        return response()->json([
            'data' => $this->productCategoryRepository->mainCategories($req)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-brand",
     *     tags={"Products & Categories"},
     *     summary="Get brand list with it's all data.",
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

    function allBrands(Request $req){
        return response()->json([
            'data' => $this->productCategoryRepository->allBrands($req)
        ]);
    }

    function productPriceByProductId(Request $request){
        $productId = $request->route('productId');

        return response()->json([
            'data' => $this->productCategoryRepository->productPriceByProductId($productId)
        ]);
    }
}
