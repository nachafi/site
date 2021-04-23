<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\CategoryContract;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryContract $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {   
        $categories = Category::all();

        $brands = Brand::all();
    
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::exact('brand', 'brand_id'),
                AllowedFilter::exact('category', 'category_id'),
                ])
            ->get();
    
        return view('site.pages.category', compact('products','brands','categories'));
       
}

    public function show($slug)
    {    
       
        $category = $this->categoryRepository->findBySlug($slug);
        $childrens = Category::where('parent_id',0)->get();
        

      
        return view('site.pages.category', compact('category','childrens'));
    }
}

