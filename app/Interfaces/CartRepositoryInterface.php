<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface CartRepositoryInterface
{
    public function allCartProductById($locationId, $userId);
    public function getCartItemByUId($locationId, $uId);
    public function addToCart(Request $request);
    public function getCartProductsFromPos($cartProducts, $userId, $orderId);
}
