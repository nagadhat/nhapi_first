<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends BaseController
{
    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    protected $productCategoryRepository;
    public function __construct(ProductCategoryRepository $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/all-category",
     *     tags={"Category"},
     *     summary="Get category full list or short list as your requirement.",
     *     description="By default this end-point will provide a short list of category with three column 'id', 'title', 'slug'). For full list you have to provide a body param with the key 'list_type' and value = full_list. Pass body param with the key 'slider_menu' and value = 'menu_view' to get all the categories for Slider-Menu.  Used in: index",
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
     *         response=201,
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

    function categories(Request $request)
    {
        return response()->json([
            'data' => $this->productCategoryRepository->categories($request)
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/create-category",
     *      operationId="create-category",
     *      tags={"Category"},
     *      summary="Create new category",
     *      security={{"passport": {}}},
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title","slug"},
     *              @OA\Property(property="title", type="string", example="Category Title Here"),
     *              @OA\Property(property="slug", type="string", example="Category Slug Here"),
     *              @OA\Property(property="logo", type="file", example="Logo.jpg"),
     *              @OA\Property(property="author_id", type="integer", example="3121"),
     *              @OA\Property(property="pos_cat_id", type="integer", example="52"),
     *
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example="44"),
     *              @OA\Property(property="title", type="string", example="Brand Title Here"),
     *              @OA\Property(property="slug", type="string", example="Brand Slug Here"),
     *              @OA\Property(property="author_id", type="integer", example="3121"),
     *              @OA\Property(property="logo", type="file", example="Logo.jpg"),
     *              @OA\Property(property="updated_at", type="timestamps", example="2022-03-15T07:09:21.000000Z"),
     *              @OA\Property(property="created_at", type="timestamps", example="2022-03-15T07:09:21.000000Z"),
     *          )
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
    function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required|unique:categories,slug',
            'author_id' => 'required',
            // 'logo'  => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return response()->json([
            'data' => $this->productCategoryRepository->createCategory($request)
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/edit-category",
     *      operationId="edit-category",
     *      tags={"Category"},
     *      summary="Edit a category",
     *      security={{"passport": {}}},
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"category_id", "pos_cat_id", "outlet_id"},
     *              @OA\Property(property="title", type="string", example="Category Title Here"),
     *              @OA\Property(property="logo", type="file", example="Logo.jpg"),
     *              @OA\Property(property="outlet_id", type="integer", example="1"),
     *              @OA\Property(property="category_id", type="integer", example="2302"),
     *              @OA\Property(property="pos_cat_id", type="integer", example="52"),
     *          ),
     *      ),
     *      @OA\Response(
     *         response=201,
     *         description="Success",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *      ),
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
    function editCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            // 'pos_cat_id'  => 'required',
            'outlet_id'  => 'required',
        ]);

        return response()->json([
            'data' => $this->productCategoryRepository->editRequestCategory($request)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-category-top-menu",
     *     tags={"Category"},
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

    function categoriesTopMenu()
    {
        return response()->json([
            'data' => $this->productCategoryRepository->categoriesTopMenu()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-category-slide",
     *     tags={"Category"},
     *     summary="Get category list for the menu just left side in the main slider.",
     *     description="Get category list for the menu just left side in the main slider.",
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

    function categoriesSlide()
    {
        return response()->json([
            'data' => $this->productCategoryRepository->categoriesSlide()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-local-product/{outletId}",
     *     tags={"Products"},
     *     summary="Get local product list by outlet with target audience 0.",
     *     description="Get local product list by outlet_id where global audience is 0. use parameter page_size=5 to set pagination '/api/all-local-product/{outletId}?per_page=2' value, by default per_page=15",
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

    function localProducts(Request $request, $outlet_id)
    {
        $page_size = $request->page_size ?? 15;

        return response()->json([
            'data' => $this->productCategoryRepository->getLocalProducts($page_size, $outlet_id)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-product-flash-sale/{outletId}",
     *     tags={"Flash Sale & Products"},
     *     summary="Get Flash Sale product list in random order.",
     *     description="Get Flash Sale product list in random order with detailed product flash discount and price.use parameter page_size=5 to set pagination '/api/all-product-flash-sale/{outletId}?per_page=2' value, by default per_page=15",
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

    function flashSaleProducts(Request $request, $outlet_id)
    {
        $page_size = $request->page_size ?? 15;

        return response()->json([
            'data' => $this->productCategoryRepository->getFlashSaleProducts($page_size, $outlet_id)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/all-product-flash-sale-info",
     *     tags={"Flash Sale & Products"},
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

    function flashSaleInfo()
    {
        return response()->json([
            'data' => $this->productCategoryRepository->flashSaleInfo()
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/get-flash-sale-status",
     *     tags={"Flash Sale & Products"},
     *     summary="Get Flash active status.",
     *     description="Get Flash Sale active status.",
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

    function flashSaleStatus()
    {
        return response()->json([
            'data' => $this->productCategoryRepository->flashSaleStatus()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/all-product-by-category-id",
     *     tags={"Products"},
     *     summary="Get product list according to category ID.",
     *     description="By default this end-point will provide all records according to given category_id and outlet_id. Required parameter 'category_id' and 'outlet_id', optional parameters 'random', 'page_size;. Then you can get custom number of records per page by providing a body param with the key 'page_size'. Also get random records by 'random' = true.",
     *     @OA\RequestBody(
     *     required=true,
     *          @OA\JsonContent(
     *              required={"category_id", "outlet_id"},
     *              @OA\Property(property="category_id", type="integer", example="114"),
     *              @OA\Property(property="outlet_id", type="integer", example="1"),
     *              @OA\Property(property="random", type="boolean", example="true"),
     *              @OA\Property(property="page_size", type="integer", example="10"),
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

    function productByCategoryID(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'outlet_id' => 'required',
            'random' => 'nullable',
            'limit' => 'nullable',
            'page_size' => 'nullable',
        ]);

        return response()->json([
            'data' => $this->productCategoryRepository->productByCategoryID($request)
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/get-product-price/{productId}",
     *     tags={"Products"},
     *     summary="Get product price by product_id.",
     *     security={{"passport": {}}},
     *     description="Returns product price by product_id as parameter",
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

    function productPriceByProductId(Request $request)
    {
        $productId = $request->route('productId');

        return response()->json([
            'data' => $this->productCategoryRepository->productPriceByProductId($productId)
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/get-product-details",
     *     tags={"Products"},
     *     summary="Get product details by outlet_id and product_id/product_sku/slug.",
     *     security={{"passport": {}}},
     *     description="Returns product details by outlet_id and product_id/product_sku/slug, pass outlet_id and one of three product_id, product_sku or slug",
     *     @OA\RequestBody(
     *     required=true,
     *          @OA\JsonContent(
     *              required={"outlet_id"},
     *              @OA\Property(property="outlet_id", type="integer", example="1"),
     *              @OA\Property(property="product_id", type="integer", example="2699"),
     *              @OA\Property(property="product_sku", type="integer", example="61e5432891f01"),
     *              @OA\Property(property="slug", type="integer", example="Barclay-Johnston-629895043b12a"),
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

    function productDetailsByIDSlugSku(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required',
            'product_id' => 'nullable',
            'product_sku' => 'nullable',
            'slug' => 'nullable',
        ]);
        if (count($request->all()) > 2) {
            return
                [
                    "meg" => 'invalid parameter',
                    "data" => 'product_id/product_sku/slug use only one',
                ];
        }

        return response()->json([
            'data' => $this->productCategoryRepository->getProductDetails($request)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/all-category-main",
     *     tags={"Category"},
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
    function mainCategories(Request $req)
    {
        return response()->json([
            'data' => $this->productCategoryRepository->mainCategories($req)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/get-brand/{limit}",     *
     *     tags={"Brand"},
     *     summary="Get brand list with it's all data.",
     *     description="By default the limit parameter is /all. use neumaric value to set limit",
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

    function allBrands(Request $request)
    {
        $limit = $request->route('limit');
        return response()->json([
            'data' => $this->productCategoryRepository->allBrands($limit)
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/create-brand",
     *      operationId="create-brand",
     *      tags={"Brand"},
     *      summary="Store new brand",
     *      security={{"passport": {}}},
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title"},
     *              @OA\Property(property="title", type="string", example="Brand Title Here"),
     *              @OA\Property(property="logo", type="file", example="Logo.jpg"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", example="Brand Title Here"),
     *              @OA\Property(property="slug", type="string", example="Brand Slug Here"),
     *              @OA\Property(property="logo", type="file", example="Logo.jpg"),
     *              @OA\Property(property="updated_at", type="timestamps", example="2022-03-15T07:09:21.000000Z"),
     *              @OA\Property(property="created_at", type="timestamps", example="2022-03-15T07:09:21.000000Z"),
     *              @OA\Property(property="id", type="integer", example="44"),
     *          )
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
    function newBrand(Request $request)
    {
        $request->validate([
            'title' => 'required',
            // 'logo'  => 'required',
        ]);

        return response()->json([
            'data' => $this->productCategoryRepository->newBrand($request)
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/edit-brand",
     *      operationId="edit-brand",
     *      tags={"Brand"},
     *      summary="Edit a brand",
     *      security={{"passport": {}}},
     *      description="Returns project data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"brand_id","pos_brand_id"},
     *              @OA\Property(property="brand_id", type="string", example="652"),
     *              @OA\Property(property="pos_brand_id", type="integer", example="12"),
     *              @OA\Property(property="title", type="string", example="Brand Title Here"),
     *              @OA\Property(property="logo", type="file", example="Logo.jpg"),
     *          ),
     *      ),
     *      @OA\Response(
     *         response=201,
     *         description="Success",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          )
     *      ),
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
    function editBrand(Request $request)
    {
        $request->validate([
            'brand_id' => 'required',
            'pos_brand_id'  => 'required',
        ]);

        return response()->json([
            'data' => $this->productCategoryRepository->editABrand($request)
        ]);
    }



    /**
     * @OA\Post(
     *      path="/api/add-master-product",
     *      operationId="outlet-product",
     *      tags={"Outlet Products"},
     *      summary="Add master products from outlet with product description",
     *      security={{"passport": {}}},
     *      description="Add master product from outlet. Enter product details as required parameters",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"product_title", "product_sku", "outlet_id", "full_description", "outlet_id", "payer_phone"},
     *              @OA\Property(property="product_title", type="string", example="Test Title"),
     *              @OA\Property(property="product_sku", type="string", example="sku123332"),
     *              @OA\Property(property="short_description", type="string", example="Some description"),
     *              @OA\Property(property="full_description", type="string", example="Some more description"),
     *              @OA\Property(property="brand_id", type="integer", example="192"),
     *              @OA\Property(property="category_id[0]", type="integer", example="1589"),
     *              @OA\Property(property="category_id[1]", type="integer", example="1598"),
     *              @OA\Property(property="model", type="string", example="rs-20"),
     *              @OA\Property(property="product_price", type="double", example="19500.00"),
     *              @OA\Property(property="product_quantity", type="integer", example="50"),
     *              @OA\Property(property="outlet_id", type="integer", example="2"),
     *              @OA\Property(property="discount_type", type="string", example="percentage/flat"),
     *              @OA\Property(property="discount_amount", type="double", example="20.00"),
     *              @OA\Property(property="cover_image", type="file", example="image file"),
     *              @OA\Property(property="image_1", type="file", example="another image file"),
     *              @OA\Property(property="image_2", type="file", example="again image file"),
     *              @OA\Property(property="image_3", type="file", example="also image file"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *              @OA\Property(property="status", type="string", example="true"),
     *              @OA\Property(property="msg", type="string", example="Added successfully."),
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

    public function addMasterProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_id'             => 'required',
            'product_title'         => 'required',
            'product_sku'           => 'required',
            // 'short_description'     => 'required',
            'full_description'      => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return response()->json([
            'data' => $this->productCategoryRepository->addMasterProduct($request)
        ]);
    }


    public function editMasterProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'outlet_id' => 'required',
            'product_sku' => 'nullable|unique:products,product_sku,' . $request->product_id,
            // 'short_description' => 'required',
            // 'full_description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return response()->json([
            'data' => $this->productCategoryRepository->editAMasterProduct($request)
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/get-products/{outletId}/{limit}",
     *     tags={"Products"},
     *     summary="Get product list by outlet_id with limit in random order where target audience is 0.",
     *     security={{"passport": {}}},
     *     description="Returns project data in random order by outlet_id with limit, pass parameter 'all' to get all products in random order",
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

    public function productsByLimit($outlet_id, $limit)
    {
        return response()->json([
            'data' => $this->productCategoryRepository->getProductsByLimit($outlet_id, $limit)
        ]);
    }
}
