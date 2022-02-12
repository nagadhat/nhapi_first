<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      title="Order Model",
 *      description="Store order request body data",
 *      type="object"
 * )
 */

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

      /**
     * @OA\Property(
     *      title="client",
     *      description="Name of the new client",
     *      example="Dr solayman"
     * )
     *
     * @var string
     */
    protected $client;

    /**
     * @OA\Property(
     *      title="details",
     *      description="details of the order",
     *      example="Dr solayman"
     * )
     *
     * @var string
     */
    protected $details;

}
