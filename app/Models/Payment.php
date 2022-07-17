<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      title="Payment Model",
 *      description="Store payment request body data",
 *      type="object"
 * )
 */

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];


    // function to sum total amount pending and approved paid
    public static function paidAmountByOrder($order_id)
    {
        return Payment::where('order_id', $order_id)->sum('transaction_amound');
    }

    // function to sum approved paid amount
    public static function approvedAmountByOrder($order_id)
    {
        return Payment::where('order_id', $order_id)->where('transaction_status', 1)->sum('transaction_amound');
    }
}
