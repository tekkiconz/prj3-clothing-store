<?php

namespace App\Http\Controllers\Front;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Http\Resources\Offer\CampaignResource;
use App\Http\Resources\Product\BrandResource;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\SubCategoryResource;
use App\Http\Resources\Product\SubSubCategoryResource;
use App\Model\Brand;
use App\Model\Campaign;
use App\Model\Category;
use App\Model\Product;
use App\Model\Setting\PageSetting;
use App\Model\SubCategory;
use App\Model\SubSubCategory;
use App\Model\SubCategoryBrand;
use App\Model\Subscribe;
use Illuminate\Http\Request;

class WebController extends Controller
{

    public function index()
    {
        //    return frontCategory();
        return view('front.index');
    }
}
