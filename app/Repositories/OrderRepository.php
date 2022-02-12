<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface 
{
    protected $model;
    public function __construct(Order $order){
        $this->model = $order;
    }

    public function getAllOrders() 
    {
        return $this->model::all();
    }

    public function getOrderById($orderId) 
    {
        return $this->model::findOrFail($orderId);
    }

    public function deleteOrder($orderId) 
    {
        $this->model::destroy($orderId);
    }

    public function createOrder(array $orderDetails) 
    {
        return $this->model::create($orderDetails);
    }

    public function updateOrder($orderId, array $newDetails) 
    {
        return $this->model::whereId($orderId)->update($newDetails);
    }

    public function getFulfilledOrders() 
    {
        return $this->model::where('is_fulfilled', true);
    }
    public function getOrders() 
    {
        return $this->model::all();
    }
}