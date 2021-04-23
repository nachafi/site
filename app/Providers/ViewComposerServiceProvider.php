<?php

namespace App\Providers;

use Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\User;
use App\Models\LikeDislike;
use App\Models\Review;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('site.partials.nav', function ($view) {
            $view->with('categories', Category::orderByRaw('-name ASC')->get()->nest());
        });
        View::composer('site.partials.header', function ($view) {
            $view->with('cartCount', Cart::getContent()->count());
        });
   
        View::composer('site.pages.homepage', function ($view) {
            $view->with('products', Product::where('featured', '1')->where('status', '1')->orderBy('id', 'desc')->get());
        });
        View::composer('site.pages.homepage', function ($view) {
            $view->with('categories', Category::where('featured', '1')->orderBy('created_at')->get());
        });
        View::composer('site.pages.products', function ($view) {
            $view->with('categories', Category::orderByRaw('-name ASC')->get());
        });
        View::composer('site.pages.products', function ($view) {
            $view->with('brands', Brand::orderByRaw('-name ASC')->get());
        });
        
        View::composer('site.pages.products', function ($view) {
            $view->with('sizes', AttributeValue::where('attribute_id', '1')->orderByRaw('-value ASC')->get());
        });
        View::composer('site.pages.products', function ($view) {
            $view->with('colors', AttributeValue::where('attribute_id', '2')->orderByRaw('-value ASC')->get());
        });
        View::composer('site.pages.category', function ($view) {
            $view->with('categories', Category::orderByRaw('-name ASC')->get());
        });
        View::composer('site.pages.category', function ($view) {
            $view->with('brands', Brand::orderByRaw('-name ASC')->get());
        });
        
        View::composer('site.pages.category', function ($view) {
            $view->with('sizes', AttributeValue::where('attribute_id', '1')->orderByRaw('-value ASC')->get());
        });
        View::composer('site.pages.category', function ($view) {
            $view->with('colors', AttributeValue::where('attribute_id', '2')->orderByRaw('-value ASC')->get());
        });
        View::composer('admin.dashboard.index', function ($view) {
            $view->with('userCount', User::count());
        });
        View::composer('admin.dashboard.index', function ($view) {
            $view->with('likeCount', LikeDislike::count());
        });
        View::composer('admin.dashboard.index', function ($view) {
            $view->with('reviewCount', Review::count());
        });
      
    

      
      
        
  
  
  
      
       
       
       
   
    }
}
