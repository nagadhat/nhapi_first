<?php

namespace App\Repositories;

use App\Interfaces\HomePageRepositoryInterface;
use App\Models\product;
use App\Models\categorie;

class HomePageRepository implements HomePageRepositoryInterface 
{
    protected $product;
    protected $categorie;
    public function __construct(Product $product, Categorie $categorie){
        $this->product = $product;
        $this->categorie = $categorie;
    }

    public function homePageView() 
    {
        $newArrival = $this->product::where('target_audience', 0)->orderBy('id', 'desc')->take(10)->get();
        $slideCategories = $this->categorie::where("on_slider_menu_view", 1)->where('status', 1)->limit(10)->get();
        $horizontalCategories = $this->categorie::where("home_page_top_menu", 1)->where('status', 1)->get();
        return (['newArrival' => $newArrival, 'slideCategories' => $slideCategories, 'horizontalCategories' => $horizontalCategories]);
    }
}