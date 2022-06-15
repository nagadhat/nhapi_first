<?php
namespace App\Interfaces;

interface PaymentRepositoryInterface
{
    public function createPayment(array $paymentdetails);
}
