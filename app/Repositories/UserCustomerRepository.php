<?php

namespace App\Repositories;

use App\Interfaces\UserCustomerRepositoryInterface;
use App\Models\User;
use App\Models\Address_assign;

class UserCustomerRepository implements UserCustomerRepositoryInterface 
{
    protected $user;
    protected $address_assign;
    public function __construct(User $user, Address_assign $address_assign){
        $this->user = $user;
        $this->address_assign = $address_assign;
    }

    public function userDetailsByUserName($userName) 
    {
        return $this->user::leftjoin('address_assign', 'users.username', '=', 'address_assign.username')
        ->leftjoin('address', 'address_assign.address_id', '=', 'address.id')
        ->where('users.username', $userName)->select('users.*', 'address.address_1', 'address.address_2')->first();
    }
}