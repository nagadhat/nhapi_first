<?php

namespace App\Repositories;

use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Http\Controllers\API\FlashSalesController;
use App\Traits\NhTraits;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashSaleProduct;
use App\Models\ProductsCategory;
use App\Models\ProductsVariations;
use App\Models\ProductsVariationColor;
use App\Models\ProductsVariationSize;
use App\Models\FlashSale;
use App\Models\Outlet;
use Illuminate\Http\Request;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    use NhTraits;

    protected $product;
    protected $brand;
    protected $category;
    protected $flash_sale;
    protected $flash_sale_product;
    protected $products_category;
    protected $products_variation;
    protected $products_variation_color;
    protected $products_variation_size;
    public function __construct(Brand $brand, Product $product, Category $category, FlashSaleProduct $flashSaleProduct, FlashSale $flashSale, ProductsCategory $productsCategory, ProductsVariations $productsVariations, ProductsVariationColor $productsVariationColor, ProductsVariationSize $productsVariationSize, Outlet $outlet)
    {
        $this->product = $product;
        $this->brand = $brand;
        $this->category = $category;
        $this->flash_sale = $flashSale;
        $this->flash_sale_product = $flashSaleProduct;
        $this->products_category = $productsCategory;
        $this->products_variation = $productsVariations;
        $this->products_variation_color = $productsVariationColor;
        $this->products_variation_size = $productsVariationSize;
        $this->outlet = $outlet;
    }

    public function categories(Request $req)
    {
        if (!empty($req->list_type) && $req->list_type == 'full_list') {
            if (!empty($req->limit) && $req->limit > 0) {
                return $this->category::where('status', 1)->limit($req->limit)->get();
            }
            return $this->category::where('status', 1)->get();
        }

        if (!empty($req->slider_menu) && $req->slider_menu == 'menu_view') {
            if (!empty($req->limit) && $req->limit > 0) {
                return $this->category::where('on_slider_menu_view', 1)->where('status', 1)->limit($req->limit)->get();
            }
            return $this->category::where('on_slider_menu_view', 1)->where('status', 1)->get();
        }

        if (empty($req->list_type) && !empty($req->limit) && $req->limit > 0) {
            return $this->category::select('id', 'title', 'slug')->where('status', 1)->limit($req->limit)->get();
        }
        return $this->category::select('id', 'title', 'slug', 'pos_cat_id', 'description', 'logo', 'banner_image')->where('status', 1)->get();
    }

    public function createCategory(Request $request)
    {
        $category_info['title'] = $request->title;
        $category_info['slug'] = $request->slug;
        $category_info['author_id'] = $request->author_id;
        $category_info['pos_cat_id'] = $request->pos_cat_id;

        if ($request->hasFile('logo')) {
            $path = 'public/media/categories';
            $return_path = 'media/categories/';
            $category_info['logo'] = $this->uploadFile($request->file('logo'), $path, $return_path);
        } else {
            $category_info['logo'] = '';
        }
        return $this->category::create($category_info);
    }

    public function categoriesTopMenu()
    {
        return $this->category::where("home_page_top_menu", 1)->where('status', 1)->get();
    }

    public function categoriesSlide()
    {
        return $this->category::where("on_slider_menu_view", 1)->where('status', 1)->limit(10)->get();
    }

    public function getLocalProducts($page_size, $outlet_id)
    {
        $outlet = $this->outlet::find($outlet_id);
        if ($outlet) {
            $products = $this->product::join('outlet_products', 'outlet_products.product_id', 'products.id')
                ->select('products.*', 'outlet_products.outlet_id', 'outlet_products.quantity as outlet_stock_quantity')
                ->where('outlet_products.outlet_id', $outlet_id)
                ->where('products.target_audience', 0)
                ->where('products.live_status', 1)
                ->orderBy('id', 'desc')
                ->paginate($page_size);

            return $products;
        } else {
            return 'invalid outlet_id';
        }
    }

    public function getProductsByLimit($outlet_id, $limit)
    {
        $outlet = $this->outlet::find($outlet_id);
        if ($outlet) {
            if ($limit > 0 && $limit != 'all') {
                return $this->product::join('outlet_products', 'outlet_products.product_id', 'products.id')
                    ->select('products.*', 'outlet_products.outlet_id', 'outlet_products.quantity as outlet_stock_quantity')
                    ->where('outlet_products.outlet_id', $outlet_id)
                    ->where('products.target_audience', 0)
                    ->where('products.live_status', 1)
                    ->limit($limit)
                    ->inRandomOrder()
                    ->get();
            }
            if ($limit == 'all') {
                return $this->product::join('outlet_products', 'outlet_products.product_id', 'products.id')
                    ->select('products.*', 'outlet_products.outlet_id', 'outlet_products.quantity as outlet_stock_quantity')
                    ->where('outlet_products.outlet_id', $outlet_id)
                    ->where('products.target_audience', 0)
                    ->where('products.live_status', 1)
                    ->inRandomOrder()
                    ->get();
            }
            return 'Invalid input!!';
        } else {
            return 'invalid outlet_id';
        }
    }

    public function getFlashSaleProducts($page_size, $outlet_id)
    {
        $outlet = $this->outlet::find($outlet_id);
        if ($outlet) {
            return $this->flash_sale_product::join('products', 'flash_sales_products.product_id', 'products.id')
                ->join('outlet_products', 'outlet_products.product_id', 'products.id')
                ->select('products.*', 'outlet_products.outlet_id', 'outlet_products.quantity as outlet_stock_quantity', 'flash_sales_products.status AS flash_discount_status', 'flash_sales_products.discount_type AS flash_discount_type', 'flash_sales_products.discount_amount AS flash_discount_amount')
                ->where("flash_sales_products.status", 1)
                ->where('outlet_products.outlet_id', $outlet_id)
                ->inRandomOrder()
                ->paginate($page_size);
        } else {
            return 'invalid outlet_id';
        }
    }

    public function flashSaleInfo()
    {
        return $this->flash_sale::first();
    }

    public function flashSaleStatus()
    {
        // $flash = flash_sale::where("id", $id)->get()->first();
        $flash = $this->flash_sale::first();
        if ($flash == null) {
            return 0;
        } else {
            $status = $flash["status"];
            $startTime = $flash["start_time"];
            $endTime = $flash["end_time"];

            $ts1 = strtotime($startTime);
            $ts2 = strtotime($endTime);
            $currentTime = strtotime(date("Y-m-d H:i:s"));
            if ($ts1 <= $currentTime && $currentTime <= $ts2 && $status) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function mainCategories(Request $req)
    {
        $limit = 10;
        if (!empty($req->limit) && $req->limit > 0) {
            $limit = $req->limit;
        }
        return $this->category::where('parent_id', 0)->where('status', 1)->limit($limit)->get();
    }

    public function allBrands($limit)
    {
        if ($limit > 0 && $limit != 'all') {
            return $this->brand::where('status', 1)->limit($limit)->get();
        }
        if ($limit == 'all') {
            return $this->brand::where('status', 1)->get();
        }
        return 'Invalid input!!';
    }

    public function newBrand(Request $request)
    {
        $brand_info['title'] = $request->title;
        $brand_info['slug'] = uniqid();
        $brand_info['pos_brand_id'] = $request->pos_brand_id;


        if ($request->hasFile('logo')) {
            $path = 'public/media/brands';
            $return_path = 'media/brands/';
            $brand_info['logo'] = $this->uploadFile($request->file('logo'), $path, $return_path);
        } else {
            $brand_info['logo'] = '';
        }
        return $this->brand::create($brand_info);
    }

    public function productByCategoryID(Request $request)
    {
        $page_size = $request->page_size ?? 15;

        $outlet_id = $request->outlet_id;
        $outlet = $this->outlet::find($outlet_id);
        if ($outlet) {
            $catId = $request->category_id;
            if (isset($request->random) && $request->random == true) {
                return ProductsCategory::join("products", "products_categories.product_id", "products.id")
                    ->join('outlet_products', 'outlet_products.product_id', 'products.id')
                    ->select('products.*', 'outlet_products.outlet_id', 'outlet_products.quantity as outlet_stock_quantity')
                    ->where('outlet_products.outlet_id', $outlet_id)
                    ->where("products_categories.category_id", $catId)
                    ->where("products.target_audience", 0)
                    ->where('products.live_status', 1)
                    ->inRandomOrder()
                    ->paginate($page_size);
            }

            return ProductsCategory::join("products", "products_categories.product_id", "products.id")
                ->join('outlet_products', 'outlet_products.product_id', 'products.id')
                ->select('products.*', 'outlet_products.outlet_id', 'outlet_products.quantity as outlet_stock_quantity')
                ->where('outlet_products.outlet_id', $outlet_id)
                ->where("products_categories.category_id", $catId)
                ->where("products.target_audience", 0)
                ->where('products.live_status', 1)
                ->paginate($page_size);
        } else {
            return 'invalid outlet_id';
        }
    }

    public function productPriceByProductId($productId)
    {
        $productDetails = $this->product::where("id", $productId)->get()->first();

        $flashSaleController = new FlashSalesController();
        // Calculate the product price
        $productSellPrice = $productDetails["price"];
        // Discount For flash-sale (Override Existing Discount)
        if ($flashSaleController->getFlashSaleStatus()) {
            $productDetails["discount_type"] = ($flashSaleController->getFlashSaleProductDiscountType($productDetails["id"]) != null) ? $flashSaleController->getFlashSaleProductDiscountType($productDetails["id"]) : $productDetails["discount_type"];
            $productDetails["discount_amount"] = ($flashSaleController->getFlashSaleProductDiscountPrice($productDetails["id"]) > 0) ? $flashSaleController->getFlashSaleProductDiscountPrice($productDetails["id"]) : $productDetails["discount_amount"];
        }
        // Discount Calculation
        if ($productDetails["discount_type"] == "percentage") {
            $productSellPrice = round($productDetails["price"] - (($productDetails["price"] * $productDetails["discount_amount"]) / 100));
        } else if ($productDetails["discount_type"] == "flat") {
            $productSellPrice = round($productDetails["price"] - $productDetails["discount_amount"]);
        }
        return $productSellPrice;
    }

    public function addMasterProduct(Request $request)
    {
        $newProduct['title'] = $request['product_title'];
        $newProduct['product_sku'] = $request['product_sku'];
        $newProduct['short_description'] = $request['short_description'];
        $newProduct['long_description'] = $request['full_description'];
        $newProduct['brand'] = $request['brand_id'];
        $newProduct['category'] = $request['category_id'];
        $newProduct['model'] = $request['model'];
        $newProduct['price'] = $request['product_price'];
        $newProduct['quantity'] = $request['product_quantity'];
        $newProduct['author_id'] = $request['outlet_id'];
        $newProduct['discount_type'] = $request["discount_type"]; // discount_type = percentage/flat
        $newProduct['discount_amount'] = $request["discount_amount"];

        if ($request->hasFile('cover_image')) {
            // uploadAndGetPath from NhTraits
            $newProduct['thumbnail_1'] = $this->uploadAndGetPath($request->file('cover_image'));
        } else {
            $newProduct['thumbnail_1'] = "";
        }

        $newProduct['img-1'] = ($request->hasFile('images_1')) ? $this->uploadAndGetPath($request->file('images_1')) : "";
        $newProduct['img-2'] = ($request->hasFile('images_2')) ? $this->uploadAndGetPath($request->file('images_2')) : "";
        $newProduct['img-3'] = ($request->hasFile('images_3')) ? $this->uploadAndGetPath($request->file('images_3')) : "";
        $newProduct['img-4'] = ($request->hasFile('images_4')) ? $this->uploadAndGetPath($request->file('images_4')) : "";
        $newProduct['img-5'] = ($request->hasFile('images_5')) ? $this->uploadAndGetPath($request->file('images_5')) : "";
        $newProduct['img-6'] = ($request->hasFile('images_6')) ? $this->uploadAndGetPath($request->file('images_6')) : "";
        $newProduct['img-7'] = ($request->hasFile('images_7')) ? $this->uploadAndGetPath($request->file('images_7')) : "";
        $newProduct['img-8'] = ($request->hasFile('images_8')) ? $this->uploadAndGetPath($request->file('images_8')) : "";
        $newProduct['img-9'] = ($request->hasFile('images_9')) ? $this->uploadAndGetPath($request->file('images_9')) : "";

        $newProduct['slug'] = substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(" ", "-", $request['product_title'] . "-" . uniqid())), 0, 100);
        $newProduct['live_status'] = 0;
        if ($request['target_audience'] == 1) {
            $newProduct['live_status'] = 1;
        }
        // $newProduct['freeshipping'] = $request["freeShipping"];
        // $newProduct['flat_delivery_crg'] = $request["flatDeliveryCrg"];
        // $newProduct['cod_status'] = $request["cashOnDelivery"];
        // $newProduct['video_platform'] = $request["videoPlatForm"];
        // $newProduct['video_link'] = $request["videoLink"];
        // $newProduct['product_type'] = $request["product_type"];
        // $newProduct['L1_commission'] = $request->L1_commission;
        // $newProduct['L2_commission'] = $request->L2_commission;
        // $newProduct['L3_commission'] = $request->L3_commission;
        // $newProduct['L4_commission'] = $request->L4_commission;
        // $newProduct['L5_commission'] = $request->L5_commission;
        // $newProduct['total_commission'] = $request->L1_commission + $request->L2_commission + $request->L3_commission + $request->L4_commission + $request->L5_commission;
        // $newProduct['target_audience'] = $request->target_audience;
        // $newProduct['money_back'] = $request->money_back;

        $addNewProduct = $this->product::create($newProduct);

        if (is_array($request['category_id'])) {
            foreach ($request["category_id"] as $category) {
                $addCategories = $this->products_category::create([
                    'product_id' => $addNewProduct->id,
                    'category_id' => $category,
                    'author_id' => $request['author_id'],
                ]);
            }
        }
        $request['product_id'] = $addNewProduct->id;
        $this->variationProduct($request);

        return $addNewProduct;
    }

    function variationProduct(Request $request)
    {
        for ($i = 0; $i < 10; $i++) {

            if ($request["variation_" . $i . "_size"] != null && $request["variation_" . $i . "_color"] != null && $request["variation_" . $i . "_price"] >= 1) {
                if ($request["variation_" . $i . "_color"] != "Select") {
                    $color = $this->colorDetailsById($request["variation_" . $i . "_color"]);
                }

                if ($request["variation_" . $i . "_size"] != "Select") {
                    $size = $this->sizeDetailsById($request["variation_" . $i . "_size"]);
                }
                // Set everythis in a array
                $productVariationData["product_id"] = $request['product_id'];
                $productVariationData["size_id"] = ($request["variation_" . $i . "_size"] == "Select") ? "0" : $request["variation_" . $i . "_size"];
                $productVariationData["color_id"] = ($request["variation_" . $i . "_color"] == "Select") ? "0" : $request["variation_" . $i . "_color"];
                $productVariationData["quantity"] = $request["variation_" . $i . "_quantity"];
                $productVariationData["price"] = $request["variation_" . $i . "_price"];
                $productVariationData["size_name"] = ($request["variation_" . $i . "_size"] == "Select") ? "not_selected" : $size["size_name"];
                $productVariationData["color_name"] = ($request["variation_" . $i . "_color"] == "Select") ? "not_selected" : $color["color_name"];
                $productVariationData["color_code"] = ($request["variation_" . $i . "_color"] == "Select") ? "" : $color["color_code"];
                $variationId = $this->insertToProductVariationAndReturnId($productVariationData);
            }
        }
    }

    function colorDetailsById($id)
    {
        // This function will return color inforamation with a array limit 1
        $color = $this->products_variation_color::where("id", $id)->get();
        return $color[0];
    }

    function sizeDetailsById($id)
    {
        // This function will return size inforamation with a array limit 1
        $size = $this->products_variation_size::where("id", $id)->get();
        return $size[0];
    }

    function insertToProductVariationAndReturnId($data)
    {
        $id = $this->products_variation::create([
            "product_id" => $data["product_id"],
            "size_id" => $data["size_id"],
            "color_id" => $data["color_id"],
            "quantity" => $data["quantity"],
            "price" => $data["price"],
            "size_name" => $data["size_name"],
            "color_name" => $data["color_name"],
            "color_code" => $data["color_code"],
        ]);
        return $id;
    }
}
