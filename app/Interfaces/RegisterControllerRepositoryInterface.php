<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface RegisterControllerRepositoryInterface 
{
    public function registration(Request $req);
}