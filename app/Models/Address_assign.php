<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address_assign extends Model
{
    use HasFactory;
    protected $table = "address_assign";
    protected $fillable = ["username","user_id","address_id"];
}
