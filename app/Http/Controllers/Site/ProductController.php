<?php

namespace App\Http\Controllers\Site;


use Cart;
use DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Models\Review;
use App\Models\LikeDislike;
use App\Models\Wishlist;
use App\Http\Controllers\BaseController;
use App\Filters\FiltersAttributeValues;
use App\Filters\FiltersCategories;
use Illuminate\Http\Request;
use App\Contracts\ProductContract;
use App\Http\Controllers\Controller;
use App\Contracts\AttributeContract;
use App\Contracts\OrderContract;
use App\Contracts\CategoryContract;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;




class ProductController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;
    protected $attributeRepository;
    protected $orderRepository;

    public function __construct(ProductContract $productRepository, CategoryContract $categoryRepository, AttributeContract $attributeRepository, OrderContract $orderRepository)
    {
        
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeRepository = $attributeRepository;
    
        
        $this->data['categories'] = $this->productRepository->getParentCategories();
        $minPrice = $this->productRepository->getMinPrice();
        $maxPrice = $this->productRepository->getMaxPrice();
        $colors = $this->productRepository->getAttributeFilters('color');
        $sizes = $this->productRepository->getAttributeFilters('size');
                                
        $this->data['sorts'] = [
            url('products') => 'Default',
            url('products?sort=price-asc') => 'Price - Low to High',
            url('products?sort=price-desc') => 'Price - High to Low',
            url('products?sort=created_at-desc') => 'Newest to Oldest',
            url('products?sort=created_at-asc') => 'Oldest to Newest',
        ];

        $this->data['selectedSort'] = url('products');

    }
    public function index( Request $request)
    {   
    
  
        
        if (request()->get('sortBy') =='All'){
            $products = Product::orderBy('id', 'desc')->paginate(20);
        }elseif (request()->get('sortBy') =='Price - Low to High'){
            $products = Product::orderBy('price', 'asc')->paginate(20);
        }elseif (request()->get('sortBy') == 'Price - High to Low'){
            $products = Product::orderBy('price', 'desc')->paginate(20);
            }elseif (request()->get('sortBy') == 'Newest to Oldest'){
                $products = Product::orderBy('created_at', 'desc')->paginate(20);
                }elseif (request()->get('sortBy') == ' Oldest to Newest'){
$products = Product::orderBy('created_at', 'asc')->paginate(20);
}elseif (request()->get('sortBy') == ' A to Z'){
    $products = Product::orderBy('name', 'asc')->paginate(20);
    }elseif (request()->get('sortBy') == ' Z to A'){
        $products = Product::orderBy('name', 'desc')->paginate(20);
        }else
        $products = $this->productRepository->paginate(20, $request);
        $minPrice = $this->productRepository->getMinPrice();

    $maxPrice = $this->productRepository->getMaxPrice();
    
    $attributes=Attribute::get();
    $attributeValues=AttributeValue::get();
   
        return view('site.pages.products', compact('products', 'minPrice', 'maxPrice','attributes','attributeValues'));
       
}
public function search( Request $request) {
    $request->validate([
        'q' => 'required'
    ]);
    $q = $request->q;
   
    $products = Product::where('name', 'like', '%' . $q . '%')->where('status', '1')->paginate(15);
    if ($products->count()) {
        return view('site.pages.products')->with(
            'products' ,  $products
        );
    } else {
        
        return redirect('/products')->with(
            'status' , 'search failed ,, please try again'
  );
    }
    
}
    public function show($slug, $id='')
    {
        $product = $this->productRepository->findProductBySlug($slug);
        
        
        $attributes = $this->attributeRepository->listAttributes();
       
    
        $products = Product::whereHas('categories', function ($query) use ($product){
            $query->whereIn('category_id', $product->categories->pluck('id'));
        })->where('id', '!=' ,$product->id)->where('status','1')->orderby('id','desc')->get();
        return view('site.pages.product', compact('product', 'attributes',  'products'));
    }


    public function addToCart(Request $request)
    {
        $product = $this->productRepository->findProductById($request->input('productId'));
        $options = $request->except('_token', 'productId', 'price', 'qty');
        
        if($product->quantity<1){
            
            return redirect()->back()->with('error', 'Cant add Item to cart.');
        }

        Cart::add(uniqid(), $product->name, $request->input('price'), $request->input('qty'), $options);
        
       
        return redirect()->back()->with('message', 'Item added to cart successfully.');
       
    }
    

    // Save Like Or dislike
    function save(Request $request){
        $data=new LikeDislike;
        $data->product_id=$request->product;
        $data->user()->associate($request->user());
        if($request->type=='like'){
            $data->like=1;
        }else{
            $data->dislike=1;
        }
        $data->save();
        
        return response()->json([
            'bool'=>true
        ]);
    }
  
}
