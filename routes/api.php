<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\UserLoginController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OutletController;
use App\Http\Controllers\API\RequisitionIssueController;
use App\Http\Controllers\API\UserCustomerController;
use App\Http\Controllers\API\OutletOrderController;
use App\Http\Controllers\API\PaymentController;

Route::get('copy-customer', [UserLoginController::class, 'copyCustomersToUsers']);
// 'Customer Authentication & Authorization' section in API documentation
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
// ==========================================================

Route::post('nh-registration', [RegisterController::class, 'registration']);
Route::post('registration-otp-verify', [RegisterController::class, 'regOtpVerification']);
Route::post('nh-login', [UserLoginController::class, 'userLogin']);
Route::post('nh-forget-password-otp', [UserLoginController::class, 'forgetPasswordOTP']);
Route::post('forget-password-otp-verify', [UserLoginController::class, 'forgetPasswordOtpVerification']);
Route::post('password-reset', [UserLoginController::class, 'passwordReset']);

Route::middleware('auth:api')->group(function () {
    // 'Orders' section in API documentation
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::put('orders/{id}', [OrderController::class, 'update']);
    Route::delete('orders/{id}', [OrderController::class, 'destroy']);
    Route::post('logout', [RegisterController::class, 'logout']);
    // =============================================================

    Route::post('nh-logout', [UserLoginController::class, 'userLogout']);

    // user info
    Route::get('logged-in-user-info', [UserLoginController::class, 'userInfo']);
    Route::get('logged-in-user-address-codes', [UserLoginController::class, 'userAddressCodes']);
    Route::get('logged-in-user-address', [UserLoginController::class, 'userAddress']);

    // other user info
    Route::get('nh-user-info/{userId}', [UserLoginController::class, 'userInfoById']);
    Route::get('nh-user-details/{userName}', [UserCustomerController::class, 'userDetailsByUserName']);
    Route::get('nh-user-address-codes/{userId}', [UserLoginController::class, 'userAddressCodesById']);
    Route::get('nh-user-address/{addressId}', [UserLoginController::class, 'userAddressByAddressId']);


    // outlet order management
    Route::get('orders-list/{outlet_id}', [OutletOrderController::class, 'orderList']);
    Route::get('orders-list/{outlet_id}/{status}', [OutletOrderController::class, 'orderListByStatus']);
    Route::get('orders-details/{outlet_id}/{order_id}', [OutletOrderController::class, 'orderDetailsById']);
    Route::post('order-process', [OutletOrderController::class, 'updateOrderByStatus']);

    // Categories
    Route::post('all-category', [ProductCategoryController::class, 'categories']);
    Route::post('create-category', [ProductCategoryController::class, 'createCategory']);
    Route::post('all-category-main', [ProductCategoryController::class, 'mainCategories']);
    Route::get('all-category-slide', [ProductCategoryController::class, 'categoriesSlide']);
    Route::get('all-category-top-menu', [ProductCategoryController::class, 'categoriesTopMenu']);

    // Products
    Route::get('all-local-product/{outletId}', [ProductCategoryController::class, 'localProducts']);
    Route::get('get-products/{outletId}/{limit}', [ProductCategoryController::class, 'productsByLimit']);
    Route::post('all-product-by-category-id', [ProductCategoryController::class, 'productByCategoryID']);
    Route::get('get-product-price/{productId}', [ProductCategoryController::class, 'productPriceByProductId']);

    // Flash Sales Product Info
    Route::get('get-flash-sale-info', [ProductCategoryController::class, 'flashSaleInfo']);
    Route::get('get-flash-sale-status', [ProductCategoryController::class, 'flashSaleStatus']);
    Route::get('get-flash-sale-products/{outletId}', [ProductCategoryController::class, 'flashSaleProducts']);

    // outlet product
    Route::post('add-master-product', [ProductCategoryController::class, 'addMasterProduct']);
    Route::post('store-pos-sale', [OrderController::class, 'storePOSsale']);

    // Brands
    Route::get('get-brand/{limit}', [ProductCategoryController::class, 'allBrands']);
    Route::post('create-brand', [ProductCategoryController::class, 'newBrand']);

    //Get cart products and prices
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::get('get-cart-product/{userId}', [CartController::class, 'allCartProductById']);

    // Get Outlets
    Route::get('get-all-outlet', [OutletController::class, 'getOutlet']);
    Route::get('get-outlet/{outletId}', [OutletController::class, 'getOutletById']);
    Route::get('outlet-delivery-location', [OutletController::class, 'outletDeliveryLocation']);
    Route::get('outlet-delivery-location/{outletId}', [OutletController::class, 'outletDeliveryLocationByOutlet']);
    Route::get('outlet/{locationId}', [OutletController::class, 'outletByDeliveryLocation']);

    // Product Requisitions Issues
    Route::post('outlet-product-requisition', [RequisitionIssueController::class, 'newRequisition']);
    Route::post('read-outlet-issue', [RequisitionIssueController::class, 'readOutletIssues']);
    Route::get('outlet-issue-details/{outletID}', [RequisitionIssueController::class, 'outletIssues']);
    Route::get('new-outlet-issue-details/{outletID}', [RequisitionIssueController::class, 'newOutletIssues']);
    Route::get('outlet-issue-details/{outletID}/{reqID}', [RequisitionIssueController::class, 'outletIssuesByRequisition']);
    Route::get('outlet-requisition-status/{outletID}', [RequisitionIssueController::class, 'outletRequisitionsStatus']);

    // order payment
    Route::post('online-order/payment', [PaymentController::class, 'receiveOnlinePayment']);
});
