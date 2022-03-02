<?php
namespace App\Interfaces;

interface CartRepositoryInterface 
{
    public function allCartProductById($userId);
}