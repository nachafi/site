<?php

namespace App\Http\Controllers\Site;
use Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Contracts\OrderContract;
use App\Contracts\ProductContract;
use App\Http\Controllers\Controller;


class CheckoutController extends Controller
{
    protected $orderRepository;
    protected $ProductRepository;
    

    public function __construct(OrderContract $orderRepository, ProductContract $productRepository )
    {   
         
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function getCheckout()
    {
        return view('site.pages.checkout');
    }

    public function placeOrder(Request $request)
    {
        // Before storing the order we should implement the
        // request validation which I leave it to you
        $validatedData = $request->validate([
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'address' => 'required', 
            'city' => 'required', 
            'country' => 'required', 
            'post_code' => 'required', 
            'phone_number' => 'required', 
            
        ]);
        $order = $this->orderRepository->storeOrderDetails($request->all());
      
        
    // You can add more control here to handle if the order
    // is not stored properly
  
   
    $methods = [
        'handcash' => 'success',
        'visa' => 'visa',
        'master' => 'master',
        'debit' => 'debit',
        'bank' => 'bank'
    ];
    
    if(isset($request->payment_method) && isset($methods[$request->payment_method]))  {
    
        Cart::clear();
    
        return view('site.pages.' . $methods[$request->payment_method], compact('order'));
    }
    $order->save();
       
    
 
        return redirect()->back()->with('message','Order not placed');
    }
   
    public function complete(Request $request)
{
    

}
   
}