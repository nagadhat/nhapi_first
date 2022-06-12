<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\HomePageController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\UserLoginController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\FlashSalesController;
use App\Http\Controllers\API\OutletController;
use App\Http\Controllers\API\RequisitionIssueController;
use App\Http\Controllers\API\UserCustomerController;
use App\Http\Controllers\API\OutletOrderController;

Route::get('/copy-customer', [UserLoginController::class, 'copyCustomersToUsers']);
// 'Customer Authentication & Authorization' section in API documentation
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::post('nh-registration', [RegisterController::class, 'registration']);
Route::post('registration-otp-verify', [RegisterController::class, 'regOtpVerification']);
Route::post('nh-login', [UserLoginController::class, 'userLogin']);
Route::post('nh-forget-password-otp', [UserLoginController::class, 'forgetPasswordOTP']);
Route::post('forget-password-otp-verify', [UserLoginController::class, 'forgetPasswordOtpVerification']);
Route::post('password-reset', [UserLoginController::class, 'passwordReset']);

Route::middleware('auth:api')->group( function () {
    Route::post('logout', [RegisterController::class, 'logout']);
    Route::post('nh-logout', [UserLoginController::class, 'userLogout']);

    Route::get('logged-in-user-info', [UserLoginController::class, 'userInfo']);
    Route::get('logged-in-user-address-codes', [UserLoginController::class, 'userAddressCodes']);
    Route::get('logged-in-user-address', [UserLoginController::class, 'userAddress']);

    Route::get('nh-user-info/{userId}', [UserLoginController::class, 'userInfoById']);
    Route::get('nh-user-details/{userName}', [UserCustomerController::class, 'userDetailsByUserName']);
    Route::get('nh-user-address-codes/{userId}', [UserLoginController::class, 'userAddressCodesById']);
    Route::get('nh-user-address/{addressId}', [UserLoginController::class, 'userAddressByAddressId']);

    // 'Orders' section in API documentation
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::post('store-pos-sale', [OrderController::class, 'storePOSsale']);
    // Route::put('orders/{id}', [OrderController::class, 'update']);
    Route::delete('orders/{id}', [OrderController::class, 'destroy']);

    // outlet order management
    Route::get('orders-list/{outlet_id}', [OutletOrderController::class, 'orderList']);
    Route::get('orders-list/{outlet_id}/{status}', [OutletOrderController::class, 'orderListByStatus']);


    //Get cart products and prices
    Route::get('get-cart-product/{userId}', [CartController::class, 'allCartProductById']);
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::get('get-product-price/{productId}', [ProductCategoryController::class, 'productPriceByProductId']);

    // 'Products & Categories' section in API documentation
    Route::post('add-master-product', [ProductCategoryController::class, 'addMasterProduct']);
    Route::post('all-category', [ProductCategoryController::class, 'categories']);
    Route::post('create-category', [ProductCategoryController::class, 'createCategory']);
    Route::post('all-category-main', [ProductCategoryController::class, 'mainCategories']);
    Route::get('all-category-slide', [ProductCategoryController::class, 'categoriesSlide']);
    Route::get('all-category-top-menu', [ProductCategoryController::class, 'categoriesTopMenu']);
    Route::get('all-product-new', [ProductCategoryController::class, 'newProducts']);
    Route::get('get-product/{limit}', [ProductCategoryController::class, 'getProductsByLimit']);
    Route::post('all-product-by-category-id', [ProductCategoryController::class, 'productByCategoryID']);

    // Brands
    Route::get('get-brand/{limit}', [ProductCategoryController::class, 'allBrands']);
    Route::post('create-brand', [ProductCategoryController::class, 'newBrand']);

    // Flash Sales Product Info
    Route::get('get-flashsale-info', [ProductCategoryController::class, 'flashSaleInfo']);
    Route::get('get-flashsale-status', [ProductCategoryController::class, 'flashSaleStatus']);
    Route::get('get-flashsale-products', [ProductCategoryController::class, 'flashSaleProducts']);

    // Get Outlets
    Route::get('get-all-outlet', [OutletController::class, 'getOutlet']);
    Route::get('get-outlet/{outletId}', [OutletController::class, 'getOutletById']);

    // Product Requisitions Issues
    Route::post('outlet-product-requisition', [RequisitionIssueController::class, 'newRequisition']);
    Route::post('read-outlet-issue', [RequisitionIssueController::class, 'readOutletIssues']);
    Route::get('outlet-issue-details/{outletID}', [RequisitionIssueController::class, 'outletIssues']);
    Route::get('new-outlet-issue-details/{outletID}', [RequisitionIssueController::class, 'newOutletIssues']);
    Route::get('outlet-issue-details/{outletID}/{reqID}', [RequisitionIssueController::class, 'outletIssuesByRequisition']);
    Route::get('outlet-requisition-status/{outletID}', [RequisitionIssueController::class, 'outletRequisitionsStatus']);
});





