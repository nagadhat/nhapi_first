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
    public function productPriceByProductId($productId);
    public function addMasterProduct(Request $request);
    public function editAMasterProduct($request);
    public function getProductsByLimit($outlet_id, $limit);
    public function getBrandCatProductStatusById($request);
    public function getFilterProductByDateTime($dateTime);
    public function getFilterCategoryByDateTime($dateTime);
    public function getFilterBrandByDateTime($dateTime);
    public function getCategoryListByProductId($product_id);
    public function newBrand(Request $request);
    public function editABrand($request);
    public function createCategory(Request $request);
    public function editRequestCategory($request);
    public function getProductDetails($request);
}
