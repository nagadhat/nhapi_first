<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Repositories\CartRepository;
use App\Traits\SmsTraits;
use App\Models\OrderTimeline;
use App\Models\OrdersProduct;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use App\Models\AffiliateUser;
use App\Models\AffiliatePayout;
use App\Models\UserCustomer;
use Illuminate\Support\Carbon;

class OrderRepository implements OrderRepositoryInterface 
{
    use SmsTraits;
    protected $user;
    protected $order;
    protected $cartRepository;    
    protected $cart;
    protected $orderTimeline;
    protected $affiliateUser;
    protected $ordersProduct;
    protected $product;
    protected $userCustomer;
    protected $affiliatePayout;
    public function __construct(Order $order, CartRepository $cartRepository, User $user, Cart $cart, OrderTimeline $orderTimeline, AffiliateUser $affiliateUser, OrdersProduct $ordersProduct, Product $product, UserCustomer $userCustomer, AffiliatePayout $affiliatePayout){
        $this->order = $order;
        $this->cartRepository = $cartRepository;
        $this->user = $user;
        $this->cart = $cart;
        $this->orderTimeline = $orderTimeline;
        $this->affiliateUser = $affiliateUser;
        $this->ordersProduct = $ordersProduct;
        $this->product = $product;
        $this->userCustomer = $userCustomer;
        $this->affiliatePayout = $affiliatePayout;
    }

    public function getAllOrders() 
    {
        return $this->order::orderBy('id', 'desc')->get();
    }

    public function getOrderById($orderId) 
    {
        return $this->order::findOrFail($orderId);
    }

    public function deleteOrder($orderId) 
    {
        $this->order::destroy($orderId);
    }

    public function createOrder(array $orderDetails) 
    {
        $user = $this->user::where('id', $orderDetails['user_id'])->first();

        $orderDetails['user_id'] = $user->id;
        $orderDetails['username'] = $user->username;
        $orderDetails['customer_name'] = $user->username;
        $orderDetails['customer_email_1'] = $user->email;
        $orderDetails['customer_phone_1'] = $user->username;
        $orderDetails['rand_code'] = rand(100, 999);
        $orderDetails['order_status'] = 1;
        $orderDetails['customer_email_2'] = '';
        $orderDetails['customer_phone_2'] = '';

        if ($orderDetails['shipping_type'] == 'inside_dhaka') {
            // temproraly set delivery charge to 0( 60/100 )
            $orderDetails['deliveryCrgPerShop'] = 0;
        } else {
            $orderDetails['deliveryCrgPerShop'] = 0;
        }

        $carts = $this->cartRepository->getCartProducts($orderDetails['user_id']);
        if($carts['status'] == false){
            return ['status'=>false, 'msg'=>'Cart is empty.'];
        }

        $totalPrice = 0;
        $totalQuantity = 0;
        foreach ($carts['chartProducts'] as $cart) {
            $totalQuantity += $cart['cartProductQuantity'];
            $totalPrice += ($cart['cartProductUnitPrice'] * $cart['cartProductQuantity']);
        }

        $orderDetails['order_code'] = $carts['orderCode'];
        $orderDetails['order_type'] = $carts['orderType'];
        $orderDetails['total_quantity'] = $totalQuantity;
        $orderDetails['total_products_price'] = $totalPrice;
        $orderDetails['total_delivery_charge'] = $carts['totalVendors'] * $orderDetails['deliveryCrgPerShop'];        

        $orderPlaced = $this->order::create($orderDetails);

        // create an order timeline for the user to see
        $newOrderTimeline = $this->orderTimeline::create([
            "order_id" => $orderPlaced['id'],
            "user_id" => $orderDetails['user_id'],
            "placed_on" => Carbon::now()
        ]);

        $orderDetails['order_id'] = $orderPlaced['id'];
        $invoice = $orderDetails['order_code'].$orderDetails['order_id'].$orderDetails['rand_code'];

        $createOrdersProducts = $this->cartRepository->getCartProducts($orderDetails['user_id'], $orderDetails['order_id']);

        $smsContent = "Your order for " . $carts['totalQuantity']." product has been placed successfully on 'Nagadhat Bangladesh Ltd'."."\nInvoice: ".$invoice.".\nFor any query, Please call to 09602444444";
        $smsSend = $this->sendSingleSms($orderDetails['username'], $smsContent);

        $apot = $this->afifiliatePostOrderTask($orderDetails['user_id'], $orderDetails['order_id']);
        // return $apot;

        // $this->cart::whereIn('product_id', $carts['productIds'])->delete();
        return ['status'=>true, 'msg'=>'Order has been placed successfully.'];
    }
    
    public function createPosOrder(array $orderDetails, $cartProducts, $sales_data) 
    {
        // If the POS order exist or not
        $orderExist = $this->order::where('pos_sale_id', $sales_data['pos_sale_id'])->first();
        if(!empty($orderExist)){
            return ['status'=>false, 'msg'=>'The POS order already hs been placed.'];
        }
        
        // return $sales_data['outlet_id'];
        $user = $this->user::where('id', $orderDetails['user_id'])->first();

        $orderDetails['user_id'] = $user->id;
        $orderDetails['username'] = $user->username;
        $orderDetails['customer_name'] = $user->username;
        $orderDetails['customer_email_1'] = $user->email;
        $orderDetails['customer_phone_1'] = $user->username;
        $orderDetails['rand_code'] = rand(100, 999);
        $orderDetails['order_status'] = 1;
        $orderDetails['order_from'] = $sales_data['outlet_id'];
        $orderDetails['pos_sale_id'] = $sales_data['pos_sale_id'];
        $orderDetails['customer_email_2'] = '';
        $orderDetails['customer_phone_2'] = '';

        if ($orderDetails['shipping_type'] == 'inside_dhaka') {
            // temproraly set delivery charge to 0( 60/100 )
            $orderDetails['deliveryCrgPerShop'] = 0;
        } else {
            $orderDetails['deliveryCrgPerShop'] = 0;
        }

        $carts = $this->cartRepository->getCartProductsFromPos($sales_data, $orderDetails['user_id'], $cartProducts);
        if($carts['status'] == false){
            return ['status'=>false, 'msg'=>$carts['msg']];
        }

        $totalPrice = 0;
        $totalQuantity = 0;
        foreach ($carts['chartProducts'] as $cart) {
            $totalQuantity += $cart['cartProductQuantity'];
            $totalPrice += ($cart['cartProductUnitPrice'] * $cart['cartProductQuantity']);
        }

        $orderDetails['order_code'] = $carts['orderCode'];
        $orderDetails['order_type'] = $carts['orderType'];
        $orderDetails['total_quantity'] = $totalQuantity;
        $orderDetails['total_products_price'] = $totalPrice;
        $orderDetails['total_delivery_charge'] = $carts['totalVendors'] * $orderDetails['deliveryCrgPerShop'];        

        // Place new order
        $orderPlaced = $this->order::create($orderDetails);

        // create an order timeline for the user to see
        $newOrderTimeline = $this->orderTimeline::create([
            "order_id" => $orderPlaced['id'],
            "user_id" => $orderDetails['user_id'],
            "placed_on" => Carbon::now()
        ]);

        $orderDetails['order_id'] = $orderPlaced['id'];
        $invoice = $orderDetails['order_code'].$orderDetails['order_id'].$orderDetails['rand_code'];
        $createOrdersProducts = $this->cartRepository->getCartProductsFromPos($sales_data, $orderDetails['user_id'], $cartProducts, $orderDetails['order_id']);
        $smsContent = "Your order for " . $carts['totalQuantity']." product has been placed successfully on 'Nagadhat Bangladesh Ltd'."."\nInvoice: ".$invoice.".\nFor any query, Please call to 09602444444";
        $smsSend = $this->sendSingleSms($orderDetails['username'], $smsContent);

        // Afifiliate Post Order Task (Commission Distribute)
        $apot = $this->afifiliatePostOrderTask($orderDetails['user_id'], $orderDetails['order_id']);
        return ['status'=>true, 'msg'=>'Order has been placed successfully.', 'data'=>$orderPlaced];
    }

    public function updateOrder($orderId, array $newDetails) 
    {
        return $this->order::whereId($orderId)->update($newDetails);
    }

    public function getFulfilledOrders() 
    {
        return $this->order::where('is_fulfilled', true);
    }
    public function getOrders() 
    {
        return $this->order::all();
    }
    public function createNewOrder($data) 
    {
        return $this->order::create($data);
    }

    public function afifiliatePostOrderTask($userId, $orderId){
        $userData = $this->userCustomer::where('user_id', $userId)->first();
        $payoutDetails["withdrawable"] = 0;
        $payoutDetails["bonus_from"] = $userId;


        $findAffiliateUser = $this->affiliateUser::where('user_id', $userData->referrer_id)->first();
        // return $userData;
        $findOrderProducts = $this->ordersProduct::where('order_id',  $orderId)->get();

        foreach ($findOrderProducts as $orderProduct) {
            $product = $this->product::find($orderProduct->product_id);
            $L5CommissionNet = $orderProduct->product_quantity * round($orderProduct->product_unit_price * ($product->L5_commission / 100), 2);
            $L4CommissionNet = $orderProduct->product_quantity * round($orderProduct->product_unit_price * ($product->L4_commission / 100), 2);
            $L3CommissionNet = $orderProduct->product_quantity * round($orderProduct->product_unit_price * ($product->L3_commission / 100), 2);
            $L2CommissionNet = $orderProduct->product_quantity * round($orderProduct->product_unit_price * ($product->L2_commission / 100), 2);
            $L1CommissionNet = $orderProduct->product_quantity * round($orderProduct->product_unit_price * ($product->L1_commission / 100), 2);

            // order and its product details
            $payoutDetails["order_product_id"] = $orderProduct->id;

            if ($userData->referrer_id != 0) {
                if ($findAffiliateUser->global_status == 1 && $findAffiliateUser->status == 1) {
                    // if ($findAffiliateUser->earning_limit <= 0) {
                    //     $findAffiliateUser->update([
                    //         'earning_limit' => round($findAffiliateUser->earning_limit - $L5CommissionNet, 2),
                    //     ]);
                    // } else {
                    // condition to stop adding earning balance to account
                    // $L5CommissionNet = $this->calculateCommissionOnEarningLimit($findAffiliateUser->earning_limit, $L5CommissionNet);
                    $findAffiliateUser->update([
                        'total_earning' => $findAffiliateUser->total_earning + $L5CommissionNet,
                        'cash_balance' => $findAffiliateUser->cash_balance + $L5CommissionNet,
                        // 'earning_limit' => isset($findAffiliateUser->earning_limit) ? round($findAffiliateUser->earning_limit - $L5CommissionNet, 2) : null,
                    ]);
                    $payoutDetails["payout_type"] = "Affiliate Bonus";
                    $payoutDetails["purpose"] = "Affiliate Bonus(Order Unpaid)";
                    $payoutDetails["user_id"] = $findAffiliateUser->user_id;
                    $payoutDetails["affiliate_user_id"] = $findAffiliateUser->id;
                    $payoutDetails["earning"] = $L5CommissionNet;
                    $payoutDetails["balance"] = $L5CommissionNet;
                    // return $this->createPayoutRecord($payoutDetails);

                    $newPayout = $this->createPayoutRecord($payoutDetails);
                    
                    // Create a new payout record  and save to database
                    // $affiliateController = new AffiliateController();
                    // $newPayout = $affiliateController->createPayoutRecord($payoutDetails);
                    // }
                }

                // condition to destribute commission to 5 generation of referers if the product is global affiliate product
                if ($orderProduct->orderProductDetailsToProduct->target_audience == 1) {

                    $secondLevAffiliate = $this->userCustomer::find($findAffiliateUser->user_id);
                    $findSecondLevAffiliate = $this->affiliateUser::where('user_id', $secondLevAffiliate->referrer_id)->first();

                    if ($secondLevAffiliate->referrer_id != 0) {
                        if ($findSecondLevAffiliate->global_status == 1 && $findSecondLevAffiliate->status == 1) {

                            // if ($findSecondLevAffiliate->earning_limit <= 0) {
                            //     $findSecondLevAffiliate->update([
                            //         'earning_limit' => round($findSecondLevAffiliate->earning_limit - $L4CommissionNet, 2),
                            //     ]);
                            // } else {
                            // condition to stop adding earning balance to account
                            // $L4CommissionNet = $this->calculateCommissionOnEarningLimit($findSecondLevAffiliate->earning_limit, $L4CommissionNet);
                            $findSecondLevAffiliate->update([
                                'total_earning' => $findSecondLevAffiliate->total_earning + $L4CommissionNet,
                                'cash_balance' => $findSecondLevAffiliate->cash_balance + $L4CommissionNet,
                                // 'earning_limit' => isset($findSecondLevAffiliate->earning_limit) ? round($findSecondLevAffiliate->earning_limit, 2) - $L4CommissionNet : null,
                            ]);

                            $payoutDetails["payout_type"] = "Affiliate Bonus";
                            $payoutDetails["purpose"] = "Affiliate Bonus(Order Unpaid)";
                            $payoutDetails["user_id"] = $findSecondLevAffiliate->user_id;
                            $payoutDetails["affiliate_user_id"] = $findSecondLevAffiliate->id;
                            $payoutDetails["earning"] = $L4CommissionNet;
                            $payoutDetails["balance"] = $L4CommissionNet;

                            $newPayout = $this->createPayoutRecord($payoutDetails);
                            // Create a new payout record  and save to database
                            // $affiliateController = new AffiliateController();
                            // $newPayout = $affiliateController->createPayoutRecord($payoutDetails);
                            // }
                        }

                        $thirdLevAffiliate = $this->userCustomer::find($findSecondLevAffiliate->user_id);
                        $findThirdLevAffiliate = $this->affiliateUser::where('user_id', $thirdLevAffiliate->referrer_id)->first();

                        if ($thirdLevAffiliate->referrer_id != 0) {
                            if ($findThirdLevAffiliate->global_status == 1 && $findThirdLevAffiliate->status == 1) {

                                // if ($findThirdLevAffiliate->earning_limit <= 0) {
                                //     $findThirdLevAffiliate->update([
                                //         'earning_limit' => round($findThirdLevAffiliate->earning_limit - $L3CommissionNet, 2),
                                //     ]);
                                // } else {
                                // condition to stop adding earning balance to account
                                // $L3CommissionNet = $this->calculateCommissionOnEarningLimit($findThirdLevAffiliate->earning_limit, $L3CommissionNet);
                                $findThirdLevAffiliate->update([
                                    'total_earning' => $findThirdLevAffiliate->total_earning + $L3CommissionNet,
                                    'cash_balance' => $findThirdLevAffiliate->cash_balance + $L3CommissionNet,
                                    // 'earning_limit' => isset($findThirdLevAffiliate->earning_limit) ? round($findThirdLevAffiliate->earning_limit, 2) - $L3CommissionNet : null,
                                ]);

                                $payoutDetails["payout_type"] = "Affiliate Bonus";
                                $payoutDetails["purpose"] = "Affiliate Bonus(Order Unpaid)";
                                $payoutDetails["user_id"] = $findThirdLevAffiliate->user_id;
                                $payoutDetails["affiliate_user_id"] = $findThirdLevAffiliate->id;
                                $payoutDetails["earning"] = $L3CommissionNet;
                                $payoutDetails["balance"] = $L3CommissionNet;

                                $newPayout = $this->createPayoutRecord($payoutDetails);
                                // Create a new payout record  and save to database
                                // $affiliateController = new AffiliateController();
                                // $newPayout = $affiliateController->createPayoutRecord($payoutDetails);
                                // }
                            }

                            $fourthLevAffiliate = $this->userCustomer::find($findThirdLevAffiliate->user_id);
                            $findFourthLevAffiliate = $this->affiliateUser::where('user_id', $fourthLevAffiliate->referrer_id)->first();

                            if ($fourthLevAffiliate->referrer_id != 0) {
                                if ($findFourthLevAffiliate->status == 1 && $findFourthLevAffiliate->global_status == 1) {
                                    // if ($findFourthLevAffiliate->earning_limit <= 0) {
                                    //     $findFourthLevAffiliate->update([
                                    //         'earning_limit' => round($findFourthLevAffiliate->earning_limit, 2) - $L2CommissionNet,
                                    //     ]);
                                    // } else {
                                    // condition to stop adding earning balance to account
                                    // $L2CommissionNet = $this->calculateCommissionOnEarningLimit($findFourthLevAffiliate->earning_limit, $L2CommissionNet);
                                    $findFourthLevAffiliate->update([
                                        'total_earning' => $findFourthLevAffiliate->total_earning + $L2CommissionNet,
                                        'cash_balance' => $findFourthLevAffiliate->cash_balance + $L2CommissionNet,
                                        // 'earning_limit' => isset($findFourthLevAffiliate->earning_limit) ? round($findFourthLevAffiliate->earning_limit, 2) - $L2CommissionNet : null,
                                    ]);

                                    $payoutDetails["payout_type"] = "Affiliate Bonus";
                                    $payoutDetails["purpose"] = "Affiliate Bonus(Order Unpaid)";
                                    $payoutDetails["user_id"] = $findFourthLevAffiliate->user_id;
                                    $payoutDetails["affiliate_user_id"] = $findFourthLevAffiliate->id;
                                    $payoutDetails["earning"] = $L2CommissionNet;
                                    $payoutDetails["balance"] = $L2CommissionNet;

                                    $newPayout = $this->createPayoutRecord($payoutDetails);
                                    // Create a new payout record  and save to database
                                    // $affiliateController = new AffiliateController();
                                    // $newPayout = $affiliateController->createPayoutRecord($payoutDetails);
                                    // }
                                }


                                $fifthLevAffiliate = $this->userCustomer::find($findFourthLevAffiliate->user_id);
                                $findFifthLevAffiliate = $this->affiliateUser::where('user_id', $fifthLevAffiliate->referrer_id)->first();

                                if ($fifthLevAffiliate->referrer_id != 0) {
                                    if ($findFifthLevAffiliate->status == 1 && $findFifthLevAffiliate->global_status == 1) {
                                        // if ($findFifthLevAffiliate->earning_limit <= 0) {
                                        //     $findFifthLevAffiliate->update([
                                        //         'earning_limit' => round($findFifthLevAffiliate->earning_limit, 2) - $L1CommissionNet,
                                        //     ]);
                                        // } else {
                                        // condition to stop adding earning balance to account
                                        // $L1CommissionNet = $this->calculateCommissionOnEarningLimit($findFifthLevAffiliate->earning_limit, $L1CommissionNet);
                                        $findFifthLevAffiliate->update([
                                            'total_earning' => $findFifthLevAffiliate->total_earning + $L1CommissionNet,
                                            'cash_balance' => $findFifthLevAffiliate->cash_balance + $L1CommissionNet,
                                            // 'earning_limit' => isset($findFifthLevAffiliate->earning_limit) ? round($findFifthLevAffiliate->earning_limit, 2) - $L1CommissionNet : null,
                                        ]);

                                        $payoutDetails["payout_type"] = "Affiliate Bonus";
                                        $payoutDetails["purpose"] = "Affiliate Bonus(Order Unpaid)";
                                        $payoutDetails["user_id"] = $findFifthLevAffiliate->user_id;
                                        $payoutDetails["affiliate_user_id"] = $findFifthLevAffiliate->id;
                                        $payoutDetails["earning"] = $L1CommissionNet;
                                        $payoutDetails["balance"] = $L1CommissionNet;

                                        $newPayout = $this->createPayoutRecord($payoutDetails);
                                        // Create a new payout record  and save to database
                                        // $affiliateController = new AffiliateController();
                                        // $newPayout = $affiliateController->createPayoutRecord($payoutDetails);
                                        // }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    // End of afifiliatePostOrderTask

    public function createPayoutRecord($payoutDetails)
    {
        $createNewRecord = $this->affiliatePayout::create([
            "user_id" => $payoutDetails["user_id"],
            "affiliate_user_id" => $payoutDetails["affiliate_user_id"],
            "date_time" => Carbon::now(),
            "status" => 1,
            "payout_type" => $payoutDetails["payout_type"],
            "purpose" => $payoutDetails["purpose"],
            "earning" => round($payoutDetails["earning"], 2),
            "balance" => round($payoutDetails["balance"], 2),
            "withdrawable" => round($payoutDetails["withdrawable"], 2),
            "bonus_from" => $payoutDetails["bonus_from"],
        ]);
        // if its an affiliate order bonus
        if (isset($payoutDetails["order_product_id"])) {
            $createNewRecord->update([
                "order_product_id" => $payoutDetails["order_product_id"]
            ]);
        }
        return $createNewRecord;
    }
}