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
use App\Http\Controllers\TempController;

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



// Products
Route::get('all-local-product/{outletId}', [ProductCategoryController::class, 'localProducts']);
Route::post('all-product-by-category-id', [ProductCategoryController::class, 'productByCategoryID']);
Route::get('get-product-price/{productId}', [ProductCategoryController::class, 'productPriceByProductId']);
Route::post('get-product-details', [ProductCategoryController::class, 'productDetailsByIDSlugSku']);
Route::get('get-category-by-product/{productId}', [ProductCategoryController::class, 'categoryListByProduct']);

// Categories
Route::post('all-category', [ProductCategoryController::class, 'categories']);
Route::post('all-category-main', [ProductCategoryController::class, 'mainCategories']);
Route::get('all-category-slide', [ProductCategoryController::class, 'categoriesSlide']);
Route::get('all-category-top-menu', [ProductCategoryController::class, 'categoriesTopMenu']);

// Flash Sales Product Info
Route::get('get-flash-sale-info', [ProductCategoryController::class, 'flashSaleInfo']);
Route::get('get-flash-sale-status', [ProductCategoryController::class, 'flashSaleStatus']);
Route::get('get-flash-sale-products/{outletId}', [ProductCategoryController::class, 'flashSaleProducts']);

// Brands
Route::get('get-brand/{limit}', [ProductCategoryController::class, 'allBrands']);

// Get Outlets
Route::get('get-all-outlet', [OutletController::class, 'getOutlet']);
Route::get('get-outlet/{outletId}', [OutletController::class, 'getOutletById']);
Route::get('outlet-delivery-location', [OutletController::class, 'outletDeliveryLocation']);
Route::get('outlet-delivery-location/{outletId}', [OutletController::class, 'outletDeliveryLocationByOutlet']);
Route::get('outlet/{locationId}', [OutletController::class, 'outletByDeliveryLocation']);

// public cart items and prices
Route::post('add-to-cart', [CartController::class, 'addToCart']);
Route::get('cart-item-by-public/{uId}/{locationId}', [CartController::class, 'cartItemByUId']);

Route::middleware('auth:api')->group(function () {
    // cart items and prices
    Route::get('cart-item-by-user/{userId}/{locationId}', [CartController::class, 'allCartProductByUserId']);

    // 'demo' section in API documentation
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

    // outlet order management pos
    Route::get('orders-list/{outlet_id}', [OutletOrderController::class, 'orderList']);
    Route::get('sync-orders/{outletId}/{dateTime}', [OutletOrderController::class, 'syncOrders']);
    Route::get('orders-list/{outlet_id}/{status}', [OutletOrderController::class, 'orderListByStatus']);
    Route::get('orders-details/{outlet_id}/{order_id}', [OutletOrderController::class, 'orderDetailsById']);
    Route::post('order-process', [OutletOrderController::class, 'updateOrderByStatus']);

    // Categories
    Route::post('create-category', [ProductCategoryController::class, 'createCategory']);
    Route::post('edit-category', [ProductCategoryController::class, 'editCategory']);

    //category pos
    Route::get('sync-category/{dateTime}', [ProductCategoryController::class, 'filterCategoryByDateTime']);

    // outlet customer info
    Route::get('get-customer-list', [OutletController::class, 'customerList']);

    // outlet product pos
    Route::get('get-products/{outletId}/{limit}', [ProductCategoryController::class, 'productsByLimit']);
    Route::get('sync-products/{dateTime}', [ProductCategoryController::class, 'filterProductByDateTime']);

    Route::post('add-master-product', [ProductCategoryController::class, 'addMasterProduct']);
    Route::post('edit-master-product', [ProductCategoryController::class, 'editMasterProduct']);

    // outlet sale pos
    Route::post('store-pos-sale', [OrderController::class, 'storePOSsale']);

    // pos helper
    Route::post('brand-cat-product-status-by-id', [ProductCategoryController::class, 'brandCatProductStatusById']);

    // customer order
    Route::post('place-order', [OrderController::class, 'placeOnlineOrder']);

    // Brands
    Route::post('create-brand', [ProductCategoryController::class, 'newBrand']);
    Route::post('edit-brand', [ProductCategoryController::class, 'editBrand']);

    //category pos
    Route::get('sync-brand/{dateTime}', [ProductCategoryController::class, 'filterBrandByDateTime']);

    // Product Requisitions Issues
    Route::post('outlet-product-requisition', [RequisitionIssueController::class, 'newRequisition']);
    Route::post('edit-outlet-product-requisition', [RequisitionIssueController::class, 'editRequisition']);
    Route::post('read-outlet-issue', [RequisitionIssueController::class, 'readOutletIssues']);
    Route::get('outlet-issue-details/{outletID}', [RequisitionIssueController::class, 'outletIssues']);
    Route::get('sync-issue/{outletID}/{dateTime}', [RequisitionIssueController::class, 'syncIssueByDateTime']);
    Route::get('new-outlet-issue-details/{outletID}', [RequisitionIssueController::class, 'newOutletIssues']);
    Route::get('outlet-issue-details/{outletID}/{reqID}', [RequisitionIssueController::class, 'outletIssuesByRequisition']);
    Route::get('outlet-requisition-status/{outletID}', [RequisitionIssueController::class, 'outletRequisitionsStatus']);

    // order payment
    Route::post('online-order/payment', [PaymentController::class, 'receiveOnlinePayment']);

    // temp fix
    // Route::post('temp-barcode-fix', [TempController::class, 'tempBarcodeFix']);
});
