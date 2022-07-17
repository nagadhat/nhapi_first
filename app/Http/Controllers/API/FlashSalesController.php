<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\Order;

class FlashSalesController extends Controller
{
    // This function will return flash-sale details
    function getFlashSaleDetails($id = 3)
    {
        $flashSale = FlashSale::where("id", $id)->get()->first();
        return $flashSale;
    }
    // This function will return flash-sale is currently active or not
    function getFlashSaleStatus($id = 3)
    {
        $flash = FlashSale::where("id", $id)->get()->first();
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

    // This function will show flash-sale page
    function showFlashSale()
    {
        // Get flash sale information and configuration
        $flashSaleDetails = FlashSale::where("id", 3)->get();
        return view("public.flash-sale", ["flashSaleSetting" => $flashSaleDetails[0]]);
    }

    function flashSalePageLoadMore(Request $request)
    {
        // $productsDetailsList = flash_sales_product::with('product:id,price,title,slug')->where('status', 1)->inRandomOrder()->paginate($request["showPerPage"]);
        $productsDetailsList = FlashSaleProduct::select("products.title", "products.slug", "products.price", "products.thumbnail_1", "flash_sales_products.discount_amount")
            ->join("products", "products.id", "=", "flash_sales_products.product_id")
            ->where("status", 1)
            ->inRandomOrder()
            ->paginate($request["showPerPage"]);

        return $productsDetailsList;
    }
    // This function will return product details of flash-sale
    function getFlashSaleProductDetails($productId)
    {
        $productDetails = FlashSaleProduct::where("product_id", $productId)->get()->first();
        return $productDetails;
    }
    // This function will return product flash-sale discount Type
    function getFlashSaleProductDiscountType($id)
    {
        $flashProduct = FlashSaleProduct::where("product_id", $id)->where('status', 1)->get()->first();
        if ($flashProduct == null) {
            return null;
        }
        return $flashProduct["discount_type"];
    }
    // This function will return product flash-sale discount Price
    function getFlashSaleProductDiscountPrice($id)
    {
        $flashProduct = FlashSaleProduct::where("product_id", $id)->where('status', 1)->get()->first();
        if ($flashProduct == null) {
            return null;
        }
        return $flashProduct["discount_amount"];
    }
    // This function will return flash-sale order quantity of a specific product by user (In current flash-sale)
    function getFlashProductOrderQntById($productId)
    {
        $flashSaleDetails = $this->getFlashSaleDetails();
        $startTime = $flashSaleDetails["start_time"];
        $endTime = $flashSaleDetails["end_time"];
        $products = Order::join("orders_products", "orders.id", "=", "orders_products.order_id")->where("orders.order_time", ">=", $startTime)->where("orders.order_time", "<=", $endTime)->where("product_id", $productId)->get();
        $total = 0;
        foreach ($products as $product) {
            $total += $product["product_quantity"];
        }
        return $total;
    }

    // This function will return flash-sale cart quantity of a specific product by user (In current flash-sale)
    function getFlashProductCartQntById($productId)
    {
        $flashSaleDetails = $this->getFlashSaleDetails();
        $products = Cart::where("user_id", Session("userId"))->where("product_id", $productId)->get();
        $total = 0;
        foreach ($products as $product) {
            $total += $product["quantity"];
        }
        return $total;
    }
}
