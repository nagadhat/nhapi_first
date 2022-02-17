<?php
namespace App\Interfaces;
use Illuminate\Http\Request;

interface ProductCategoryRepositoryInterface 
{
    public function categories(Request $req);
    public function mainCategories(Request $req);
    public function allBrands(Request $req);
    public function categoriesTopMenu();
    public function categoriesSlide();
    public function newProducts();
    public function flashSaleProducts();
    public function flashSaleInfo();
    public function productByCategoryID(Request $req);
}