<?php

namespace App\Traits;

use App\Models\AffiliateBuybackPackage;
use App\Models\AffiliatePayout;
use App\Models\AffiliateUser;
use App\Models\OrdersProduct;
use App\Models\OutletProduct;
use App\Models\UserCustomer;

trait AffiliateTraits
{
    // function to create buyback product orpion when order is processing
    public function createBuybackPolicy($order)
    {
        $findOrderproducts = OrdersProduct::where('order_id', $order->id)->get();
        $findUserDetails = AffiliateUser::where('user_id', $order->user_id)->first();
        foreach ($findOrderproducts as $product) {
            if ($product->ordersProductToProduct->target_audience == 1) {
                $createBuybackPackage = AffiliateBuybackPackage::create([
                    "user_id" => $order->user_id,
                    "order_id" => $order->id,
                    "product_id" => $product->product_id,
                    "status" => 0,
                    "product_quantity" => $product->product_quantity,
                    "price" => $product->product_unit_price * $product->product_quantity,
                    "money_back" => $product->ordersProductToProduct->money_back * $product->product_quantity,
                    // duration and condition changed on 12.04.22
                    "duration" => 24,
                    "monthly_bonus" => round(((($product->ordersProductToProduct->money_back - $product->product_unit_price) / 24) * $product->product_quantity), 2),
                ]);

                // make user global
                if (isset($findUserDetails) && $findUserDetails->global_status == 0) {
                    $findUserDetails->update([
                        'global_status' => 1
                    ]);
                }
            }
            // check if user exists
            if (isset($findUserDetails)) {
                // limit will increase either way for all products
                if ($findUserDetails->earning_limit < 0) {
                    $findUserDetails->update([
                        "earning_limit" => $product->product_unit_price * $product->product_quantity * 5,
                    ]);
                } else {
                    $findUserDetails->update([
                        "earning_limit" => $findUserDetails->earning_limit + ($product->product_unit_price * $product->product_quantity * 5),
                    ]);
                }
            }
        }

        return true;
    }

    // function to restore product if canceled
    public function restoreProductStock($order)
    {
        $orderProducts = OrdersProduct::where('order_id', $order->id)->get();
        foreach ($orderProducts as $products) {
            $findOutletProduct = OutletProduct::where('outlet_id', $order->outlet_id)
                ->where('product_id', $products->product_id)
                ->first();
            if (isset($findOutletProduct)) {
                $findOutletProduct->update([
                    'quantity' => $findOutletProduct->quantity + $products->product_quantity
                ]);
            }
        }
        return $orderProducts;
    }

    // function to cancel affiliate pending bonus if prder canceled
    public function cancelAffiliateBonusRecord($order)
    {
        $findOrderProducts = OrdersProduct::where('order_id', $order->id)->get();
        $findUserWhereBonusComingFrom = UserCustomer::find($order->user_id);

        foreach ($findOrderProducts as $orderProduct) {

            // commission to 1st lvl
            $find1stlvlAffiliateUser = AffiliateUser::where('user_id', $findUserWhereBonusComingFrom->referrer_id)->first();
            $find1stAffiliatePayout = AffiliatePayout::where('user_id', $find1stlvlAffiliateUser->user_id)
                ->where('bonus_from', $findUserWhereBonusComingFrom->id)
                ->where('order_product_id', $orderProduct->id)
                ->where('payout_type', 'Affiliate Bonus')
                ->where('purpose', 'Affiliate Bonus(Order Unpaid)')
                ->first();

            if (isset($find1stAffiliatePayout)) {
                $find1stlvlAffiliateUser->update([
                    'total_earning' => $find1stlvlAffiliateUser->total_earning - $find1stAffiliatePayout->earning,
                    'cash_balance' => $find1stlvlAffiliateUser->cash_balance - $find1stAffiliatePayout->earning,
                    // 'earning_limit' => round($find1stlvlAffiliateUser->earning_limit + $find1stAffiliatePayout->earning, 2)
                ]);
                $find1stAffiliatePayout->update([
                    'purpose' => 'Affiliate Bonus(Order Canceled)',
                    'balance' => 0
                ]);
            }
            // commission to 1st lvl end

            // commission to 2nd lvl
            if ($find1stlvlAffiliateUser->referrer_id != 0) {
                $find2ndlvlAffiliateUser = AffiliateUser::where('user_id', $find1stlvlAffiliateUser->referrer_id)->first();
                $find2ndAffiliatePayout = AffiliatePayout::where('user_id', $find2ndlvlAffiliateUser->user_id)
                    ->where('bonus_from', $findUserWhereBonusComingFrom->id)
                    ->where('order_product_id', $orderProduct->id)
                    ->where('payout_type', 'Affiliate Bonus')
                    ->where('purpose', 'Affiliate Bonus(Order Unpaid)')
                    ->first();

                if (isset($find2ndAffiliatePayout)) {
                    $find2ndlvlAffiliateUser->update([
                        'total_earning' => $find2ndlvlAffiliateUser->total_earning - $find2ndAffiliatePayout->earning,
                        'cash_balance' => $find2ndlvlAffiliateUser->cash_balance - $find2ndAffiliatePayout->earning,
                        // 'earning_limit' => round($find2ndlvlAffiliateUser->earning_limit + $find2ndAffiliatePayout->earning, 2)
                    ]);
                    $find2ndAffiliatePayout->update([
                        'purpose' => 'Affiliate Bonus(Order Canceled)',
                        'balance' => 0
                    ]);
                }

                // commission to 3rd lvl
                if ($find2ndlvlAffiliateUser->referrer_id != 0) {
                    $find3rdlvlAffiliateUser = AffiliateUser::where('user_id', $find2ndlvlAffiliateUser->referrer_id)->first();
                    $find3rdAffiliatePayout = AffiliatePayout::where('user_id', $find3rdlvlAffiliateUser->user_id)
                        ->where('bonus_from', $findUserWhereBonusComingFrom->id)
                        ->where('order_product_id', $orderProduct->id)
                        ->where('payout_type', 'Affiliate Bonus')
                        ->where('purpose', 'Affiliate Bonus(Order Unpaid)')
                        ->first();

                    if (isset($find3rdAffiliatePayout)) {
                        $find3rdlvlAffiliateUser->update([
                            'total_earning' => $find3rdlvlAffiliateUser->total_earning - $find3rdAffiliatePayout->earning,
                            'cash_balance' => $find3rdlvlAffiliateUser->cash_balance - $find3rdAffiliatePayout->earning,
                            // 'earning_limit' => round($find3rdlvlAffiliateUser->earning_limit + $find3rdAffiliatePayout->earning, 2)
                        ]);
                        $find3rdAffiliatePayout->update([
                            'purpose' => 'Affiliate Bonus(Order Canceled)',
                            'balance' => 0
                        ]);
                    }

                    // commission to 4th lvl
                    if ($find3rdlvlAffiliateUser->referrer_id != 0) {
                        $find4thlvlAffiliateUser = AffiliateUser::where('user_id', $find3rdlvlAffiliateUser->referrer_id)->first();
                        $find4thAffiliatePayout = AffiliatePayout::where('user_id', $find4thlvlAffiliateUser->user_id)
                            ->where('bonus_from', $findUserWhereBonusComingFrom->id)
                            ->where('order_product_id', $orderProduct->id)
                            ->where('payout_type', 'Affiliate Bonus')
                            ->where('purpose', 'Affiliate Bonus(Order Unpaid)')
                            ->first();

                        if (isset($find4thAffiliatePayout)) {
                            $find4thlvlAffiliateUser->update([
                                'total_earning' => $find4thlvlAffiliateUser->total_earning - $find4thAffiliatePayout->earning,
                                'cash_balance' => $find4thlvlAffiliateUser->cash_balance - $find4thAffiliatePayout->earning,
                                // 'earning_limit' => round($find4thlvlAffiliateUser->earning_limit + $find4thAffiliatePayout->earning, 2)
                            ]);
                            $find4thAffiliatePayout->update([
                                'purpose' => 'Affiliate Bonus(Order Canceled)',
                                'balance' => 0
                            ]);
                        }

                        // commission to 5th lvl
                        if ($find4thlvlAffiliateUser->referrer_id != 0) {
                            $find5thlvlAffiliateUser = AffiliateUser::where('user_id', $find4thlvlAffiliateUser->referrer_id)->first();
                            $find5thAffiliatePayout = AffiliatePayout::where('user_id', $find5thlvlAffiliateUser->user_id)
                                ->where('bonus_from', $findUserWhereBonusComingFrom->id)
                                ->where('order_product_id', $orderProduct->id)
                                ->where('payout_type', 'Affiliate Bonus')
                                ->where('purpose', 'Affiliate Bonus(Order Unpaid)')
                                ->first();

                            if (isset($find5thAffiliatePayout)) {
                                $find5thlvlAffiliateUser->update([
                                    'total_earning' => $find5thlvlAffiliateUser->total_earning - $find5thAffiliatePayout->earning,
                                    'cash_balance' => $find5thlvlAffiliateUser->cash_balance - $find5thAffiliatePayout->earning,
                                    // 'earning_limit' => round($find5thlvlAffiliateUser->earning_limit + $find5thAffiliatePayout->earning, 2)
                                ]);
                                $find5thAffiliatePayout->update([
                                    'purpose' => 'Affiliate Bonus(Order Canceled)',
                                    'balance' => 0
                                ]);
                            }
                        }
                        // commission to 5th lvl end
                    }
                    // commission to 4th lvl end
                }
                // commission to 3rd lvl end
            }
            // commission to 2nd lvl end
        }
        return true;
    }
}
