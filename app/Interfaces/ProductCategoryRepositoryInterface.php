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
    public function flashSaleStatus();
    public function productByCategoryID(Request $req);
    public function productPriceByProductId(Request $request);
    public function addMasterProduct(Request $request);
    public function getProductsByLimit(Request $request);
}