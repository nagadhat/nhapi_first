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
    public function getLocalProducts($page_size, $outlet_id);
    public function getFlashSaleProducts($page_size, $outlet_id);
    public function flashSaleInfo();
    public function flashSaleStatus();
    public function productByCategoryID(Request $req);
    public function productPriceByProductId(Request $request);
    public function addMasterProduct(Request $request);
    public function getProductsByLimit($outlet_id, $limit);
    public function newBrand(Request $request);
    public function createCategory(Request $request);
}
