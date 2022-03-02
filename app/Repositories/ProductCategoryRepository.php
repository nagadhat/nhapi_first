<?php

namespace App\Repositories;

use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Http\Controllers\API\FlashSalesController;
use App\Models\product;
use App\Models\Brand;
use App\Models\categorie;
use App\Models\FlashSaleProduct;
use App\Models\ProductsCategorie;
use App\Models\FlashSale;
use Illuminate\Http\Request;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface 
{
    protected $product;
    protected $brand;
    protected $categorie;
    protected $flash_sale;
    protected $flash_sale_product;
    protected $products_categorie;
    public function __construct(Brand $brand, Product $product, Categorie $categorie, FlashSaleProduct $flashSaleProduct, FlashSale $flashSale, ProductsCategorie $productsCategorie){
        $this->product = $product;
        $this->brand = $brand;
        $this->categorie = $categorie;
        $this->flash_sale = $flashSale;
        $this->flash_sale_product = $flashSaleProduct;
        $this->products_categorie = $productsCategorie;
    }

    public function categories(Request $req)
    {
        if(!empty($req->list_type) && $req->list_type == 'full_list'){
            return $this->categorie::all();
        }
        if(!empty($req->slider_menu) && $req->slider_menu == 'menu_view'){
            return $this->categorie::where('on_slider_menu_view', 1)->where('status', 1)->get();
        }
        return $this->categorie::select('id', 'title', 'slug')->get();        
    }

    public function categoriesTopMenu()
    {
        return $this->categorie::where("home_page_top_menu", 1)->where('status', 1)->get();
    }

    public function categoriesSlide() 
    {
        return $this->categorie::where("on_slider_menu_view", 1)->where('status', 1)->limit(10)->get();
    }

    public function newProducts() 
    {
        return $this->product::where('target_audience', 0)->orderBy('id', 'desc')->take(10)->get();
    }
    
    public function flashSaleProducts() 
    {
        return $this->flash_sale_product::
        leftjoin('products', 'flash_sales_products.product_id', '=', 'products.id')
        ->select('products.*', 'flash_sales_products.status AS d_status', 'flash_sales_products.discount_type AS d_type', 'flash_sales_products.discount_amount AS d_amount')
        ->where("flash_sales_products.status",1)->limit(10)->inRandomOrder()->get();
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
            if($ts1 <= $currentTime && $currentTime <= $ts2 && $status){
                return 1;
            }else{
                return 0;
            }
        }
    }

    public function mainCategories(Request $req) 
    {
        $limit = 10;
        if(!empty($req->limit) && $req->limit > 0){
            $limit = $req->limit;
        }        
        return $this->categorie::where('parent_id', 0)->where('status', 1)->limit($limit)->get();
    }

    public function allBrands(Request $req) 
    {
        $limit = 10;
        if(!empty($req->limit) && $req->limit > 0){
            $limit = $req->limit;
        }        
        return $this->brand::where('status', 1)->limit($limit)->get();
    }

    public function productByCategoryID(Request $req) 
    {        
        // return $req->category_id;
        $req->validate(['category_id' => 'required']);
        $max = 10;
        $random = false;
        $catId = $req->category_id;

        if($req->number_of_records > 0){
            $max = $req->number_of_records;
        }
        if(!empty($req->random) && $req->random == true){
            return ProductsCategorie::join("products", "products_categories.product_id", "=", "products.id")->where("category_id", $catId)->where("products.target_audience", 0)->limit($max)->inRandomOrder()->get();
        }
        return ProductsCategorie::join("products", "products_categories.product_id", "=", "products.id")->where("category_id", $catId)->where("products.target_audience", 0)->limit($max)->get();
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
}