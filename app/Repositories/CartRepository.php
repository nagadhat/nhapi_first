<?php

namespace App\Repositories;

use App\Repositories\ProductCategoryRepository;
use App\Interfaces\CartRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\ProductsVariations;
use App\Models\OrdersProduct;
use App\Models\Product;
use App\Models\Cart;
use App\Models\OutletProduct;
use Validator;
use Auth;
use DB;

class CartRepository implements CartRepositoryInterface
{
    protected $cart;
    protected $pcr;
    protected $product;
    protected $productsVariations;
    protected $ordersProduct;
    protected $outletProduct;
    public function __construct(Cart $cart, Product $product, ProductsVariations $productsVariations, ProductCategoryRepository $productCategoryRepository, OrdersProduct $ordersProduct, OutletProduct $outletProduct){
        $this->cart = $cart;
        $this->pcr = $productCategoryRepository;
        $this->product = $product;
        $this->productsVariations = $productsVariations;
        $this->ordersProduct = $ordersProduct;
        $this->outletProduct = $outletProduct;
    }

    public function addToCart(Request $request) {
        $productExist = $this->cart::where('user_id', $request->user_id)->where('product_id', $request->product_id)->first();
        
        if($productExist){
            $productExist->quantity = $productExist->quantity + $request->quantity;
            $productExist->save();
            $cartProduct = $productExist;

        } else $cartProduct = $this->cart::create($request->all());
        
        return ['status'=>true, 'msg'=>'Product added to cart successfully', 'data'=>$cartProduct];
    }

    public function allCartProductById($userId)
    {
        // $cartData = $this->cart::where("user_id", Auth::id())->get()->toArray();
        $cartData = $this->cart::where("user_id", $userId)->get()->toArray();
        if(empty($cartData)){
            return ['status'=>false, 'msg'=>'Cart is empty.'];
        }

        $cartProductsDetails = array();
        foreach ($cartData as $data) {
            // Get product information
            $productData = $this->product::where("id", $data["product_id"])->first();

            $firstArray = array(
                "cartId" => $data["id"],
                "productId" => $data["product_id"],
                "orderType" => $data["order_type"],
                "cartProductImage" => $productData["thumbnail_1"],
                "cartProductName" => $productData["title"],
                "cartProductQuantity" => $data["quantity"],
                "cartProductUnitPrice" => $this->pcr->productPriceByProductId($productData["id"]),
                "cartProductVendorId" => $productData["vendor"],
                "cartProductReturnPolicy" => $productData["return_policy"],
                "cartProductSlug" => $productData["slug"],
            );
            if ($data["product_variation_size"] != 0) {
                // Veriation Price here
                $variation = $this->productsVariations::where("id", $data["product_variation_size"])->get()->first();
                if ($variation["price"] != 0) {
                    $firstArray["cartProductUnitPrice"] = $variation["price"];
                }
            }
            $cartProductsDetails[] = $firstArray;
        }
        return $cartProductsDetails;

        $vendors = $this->getVendorsListOfCart($userId);
        $totalVendor = count($vendors);
        return ['chartProducts'=> $cartProductsDetails, 'vendors'=> $vendors, 'totalVendors'=> $totalVendor];
    }

    public function getCartProducts($userId, $orderId=0) 
    {
        $cartData = $this->cart::where("user_id", $userId)->get()->toArray();
        if(empty($cartData)){
            return ['status'=>false, 'msg'=>'Cart is empty.'];
        }
        // return $cartData[0]['order_type'];
        if ($cartData[0]['order_type'] == 'Flash' || $cartData[0]['order_type'] == 'flash') {
                $orderType = 'Flash';
                $orderCode = 'FLH';
            } elseif ($cartData[0]['order_type'] == 'Wholesale' || $cartData[0]['order_type'] == 'wholesale') {
                $orderType = 'Wholesale';
                $orderCode = 'WHS';
            } elseif ($cartData[0]['order_type'] == 'Express' || $cartData[0]['order_type'] == 'express') {
                $orderType = 'Express';
                $orderCode = 'EXP';
            } elseif ($cartData[0]['order_type'] == 'Regular') {
                $orderType = 'Regular';
                $orderCode = 'REG';
            } elseif ($cartData[0]['order_type'] == 'nh10') {
                $orderType = 'blast';
                $orderCode = 'NHL';
            } else {
                $orderType = 'Regular';
                $orderCode = 'REG';
            }

        $cartProductsDetails = array();
        $productIds = array();
        foreach ($cartData as $data) {
            // Get product information
            $productData = $this->product::where("id", $data["product_id"])->first();

            $firstArray = array(
                "cartId" => $data["id"],
                "productId" => $data["product_id"],
                "orderType" => $data["order_type"],                
                "cartProductImage" => $productData["thumbnail_1"],
                "cartProductName" => $productData["title"],
                "cartProductQuantity" => $data["quantity"],
                "cartProductUnitPrice" => $this->pcr->productPriceByProductId($productData["id"]),
                "cartProductVendorId" => $productData["vendor"],
                "cartProductReturnPolicy" => $productData["return_policy"],
                "cartProductSlug" => $productData["slug"],
            );
            if ($data["product_variation_size"] != 0) {
                // Veriation Price here
                $variation = $this->productsVariations::where("id", $data["product_variation_size"])->get()->first();
                if ($variation["price"] != 0) {
                    $firstArray["cartProductUnitPrice"] = $variation["price"];
                }
            }

            if($orderId != 0){
                $this->ordersProduct::create([
                    "order_id"              => $orderId,
                    "product_id"            => $firstArray['productId'],
                    "product_name"          => $firstArray['cartProductName'],
                    "product_quantity"      => $firstArray['cartProductQuantity'],
                    "vendor_id"             => $firstArray['cartProductVendorId'],
                    "return_policy"         => $firstArray['cartProductReturnPolicy'],
                    "thumbnail_1"           => $firstArray['cartProductImage'],
                    "product_unit_price"    => $firstArray['cartProductUnitPrice'],
                    "product_slug"          => $firstArray['cartProductSlug']
                ]);
            }

            $cartProductsDetails[] = $firstArray;
            $productIds[] = $firstArray['productId'];
        }

        $vendors = $this->getVendorsListOfCart($userId);
        $totalVendor = count($vendors);
        $totalQuantity = count($cartData);
        return ['status'=>true, 'chartProducts'=> $cartProductsDetails, 'vendors'=> $vendors, 'totalVendors'=> $totalVendor, 'orderCode'=> $orderCode, 'orderType'=>$orderType, 'totalQuantity'=>$totalQuantity, 'productIds'=>$productIds];
    }
    
    public function getCartProductsFromPos($sales_data, $userId, $cartData, $orderId=0) 
    {
        // return count($cartData);
        // return $orderId;        
        // $cartData = $this->cart::where("user_id", $userId)->get()->toArray();

        if(empty($cartData)){
            return ['status'=>false, 'msg'=>'Cart is empty.'];
        }
        // return $cartData[0]['order_type'];
        if ($cartData[0]['order_type'] == 'Flash' || $cartData[0]['order_type'] == 'flash') {
                $orderType = 'Flash';
                $orderCode = 'FLH';
            } elseif ($cartData[0]['order_type'] == 'Wholesale' || $cartData[0]['order_type'] == 'wholesale') {
                $orderType = 'Wholesale';
                $orderCode = 'WHS';
            } elseif ($cartData[0]['order_type'] == 'Express' || $cartData[0]['order_type'] == 'express') {
                $orderType = 'Express';
                $orderCode = 'EXP';
            } elseif ($cartData[0]['order_type'] == 'Regular') {
                $orderType = 'Regular';
                $orderCode = 'REG';
            } elseif ($cartData[0]['order_type'] == 'nh10') {
                $orderType = 'blast';
                $orderCode = 'NHL';
            } else {
                $orderType = 'Regular';
                $orderCode = 'REG';
            }

        $cartProductsDetails = array();
        $productIds = array();
        $lastStock = array();
        foreach ($cartData as $data) {
            // Get product information
            $productData = $this->product::where("id", $data["product_id"])->first();

            $firstArray = array(
                // "cartId" => $data["id"],
                "productId" => $data["product_id"],
                "orderType" => $data["order_type"],                
                "cartProductImage" => $productData["thumbnail_1"],
                "cartProductName" => $productData["title"],
                "cartProductQuantity" => $data["product_quantity"],
                "cartProductUnitPrice" => $this->pcr->productPriceByProductId($productData["id"]),
                "cartProductVendorId" => $productData["vendor"],
                "cartProductReturnPolicy" => $productData["return_policy"],
                "cartProductSlug" => $productData["slug"],
            );
            if ($data["product_variation_size"] != 0) {
                // Veriation Price here
                $variation = $this->productsVariations::where("id", $data["product_variation_size"])->get()->first();
                if ($variation["price"] != 0) {
                    $firstArray["cartProductUnitPrice"] = $variation["price"];
                }
            }

            if($orderId != 0){
                // Decrease outlet products in Outlet_Products table
                $outletProduct = $this->outletProduct::where('outlet_id', $sales_data['outlet_id'])->where('product_id', $data["product_id"])->first();
                if(!empty($outletProduct && $outletProduct->quantity >= $data["product_quantity"])){
                    $newProductQuantity = $outletProduct->quantity - $data['product_quantity'];
                } else {
                    return ['status'=>false, 'msg'=>'Insufficient one of product.'];
                }
                
                $outletProduct = $this->outletProduct::updateOrCreate(
                    ['outlet_id' => $sales_data['outlet_id'], 'product_id' => $data['product_id']],
                    ['quantity' => $newProductQuantity]
                );

                $lastStock = array(
                    "productId" => $data["product_id"],
                    "productQuantity" => $newProductQuantity
                );

                // Create record in Orders_Products table
                $this->ordersProduct::create([
                    "order_id"              => $orderId,
                    "product_id"            => $firstArray['productId'],
                    "product_name"          => $firstArray['cartProductName'],
                    "product_quantity"      => $firstArray['cartProductQuantity'],
                    "vendor_id"             => $firstArray['cartProductVendorId'],
                    "return_policy"         => $firstArray['cartProductReturnPolicy'],
                    "thumbnail_1"           => $firstArray['cartProductImage'],
                    "product_unit_price"    => $firstArray['cartProductUnitPrice'],
                    "product_slug"          => $firstArray['cartProductSlug']
                ]);
            }

            $cartProductsDetails[] = $firstArray;
            $lastOutletStock[] = $lastStock;
            $productIds[] = $firstArray['productId'];
        }

        $vendors = $this->getVendorsListOfCart($productIds);
        $totalVendor = count($vendors);
        $totalQuantity = count($cartData);
        return ['status'=>true, "outletId" => $sales_data['outlet_id'], 'lastOutletStock'=>$lastOutletStock, 'chartProducts'=> $cartProductsDetails, 'vendors'=> $vendors, 'totalVendors'=> $totalVendor, 'orderCode'=> $orderCode, 'orderType'=>$orderType, 'totalQuantity'=>$totalQuantity, 'productIds'=>$productIds, ];
    }

    function getVendorsListOfCart($productIds){
        return $this->product::leftjoin("vendors", "vendors.id", "=", "products.vendor")
        ->whereIn("products.id", $productIds)->select("vendors.id AS vendorId", "vendors.shop_name", DB::raw('count(vendors.id) as totalProduct'))
        ->groupBy("vendors.id", 'vendors.shop_name')->get();
    }
    // function getVendorsListOfCart($userId){
    //     return $this->cart::join("products", "carts.product_id", "=", "products.id")
    //     ->join("vendors", "vendors.id", "=", "products.vendor")
    //     ->where("carts.user_id", $userId)->select("vendors.id AS vendorId", "vendors.shop_name", DB::raw('count(vendors.id) as totalProduct'))
    //     ->groupBy("vendors.id", 'vendors.shop_name')->get();
    // }
}