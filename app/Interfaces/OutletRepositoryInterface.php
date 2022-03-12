<?php
namespace App\Interfaces;

interface OutletRepositoryInterface 
{
    public function getOutlet();
    public function getOutletById($outletId);
}