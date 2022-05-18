<?php

namespace App\Repositories;

use App\Interfaces\UserCustomerRepositoryInterface;
use App\Models\User;
use App\Models\Address_assign;
use App\Models\Address;

class UserCustomerRepository implements UserCustomerRepositoryInterface 
{
    protected $user;
    protected $address_assign;
    protected $address;
    public function __construct(User $user, Address_assign $address_assign, Address $address){
        $this->user = $user;
        $this->address_assign = $address_assign;
        $this->address = $address;
    }

    public function userDetailsByUserName($userName) 
    {
        $user = $this->user::
        leftjoin('user_customers', 'users.username', '=', 'user_customers.username')
        ->where('users.username', $userName)
        ->select('users.*', 'user_customers.first_name')
        ->first();
        $newUser = $user->toArray();

        $addresses = $this->address_assign::
        leftjoin('address', 'address_assign.address_id', '=', 'address.id')
        ->where('username', $userName)->get();

        $items = array();
        foreach ($addresses as $address) {
            $items[] = array(
                'address_1' => $address->address_1,
                'area' => $address->area,
                'postal_code' => $address->postal_code,
                'city' => $address->city,
                'country' => $address->country,
            );
            $newUser['addresses'] = $items;
        }
        return $newUser;
        
        
        
        
        // for($i = 0; $i < 1; $i++) {
        //     $addresses = $this->address_assign::
        //     leftjoin('address', 'address_assign.address_id', '=', 'address.id')
        //     ->where('username', $userName)->get();

        //     $items = array();
        //     foreach ($addresses as $address) {
        //         $items[] = array(
        //             'address_1' => $address->address_1,
        //             'area' => $address->area,
        //             'postal_code' => $address->postal_code,
        //             'city' => $address->city,
        //             'country' => $address->country,
        //         );
        //     }
        //     // $newUser[$i]['addresses'] = $items;
        //     $newUser['addresses'] = $items;
        // }
        // return $newUser;
    }
}