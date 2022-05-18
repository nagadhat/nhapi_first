<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface CartRepositoryInterface 
{
    public function allCartProductById($userId);
    public function addToCart(Request $request);
    public function getCartProductsFromPos($cartProducts, $userId, $orderId);
}