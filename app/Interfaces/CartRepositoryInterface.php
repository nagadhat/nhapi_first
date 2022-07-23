<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface CartRepositoryInterface
{
    public function allCartProductById($userId, $locationId);
    public function getCartItemByUId($uId, $locationId);
    public function addToCart(Request $request);
    public function getCartProductsFromPos($cartProducts, $userId, $orderId);
}
