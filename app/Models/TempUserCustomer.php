<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempUserCustomer extends Model
{
    use HasFactory;
    protected $table = 'temp_user_customers';
    protected $fillable = [
        "username",
        "user_name",
        "user_email",
        "user_password",
        "user_otp_code",
        "gender",
        "user_type"
    ];
    public $timestamps = true;
}
