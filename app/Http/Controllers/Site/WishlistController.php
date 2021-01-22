<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\User;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
     
      $wishlists = Wishlist::where("user_id", "=", auth()->id())->orderby('id', 'desc')->paginate(5);
      return view('site.pages.wishlist', compact( 'wishlists'));
    }

    public function store(Request $request)
    {


$wishlist=Wishlist::where('user_id',auth()->id())
->where('product_id',$request->product_id)
->first();

if(auth()->user()->id ==$wishlist->user_id)
   {
       return redirect()->back()->with(['status' => 'success', 'This item is already in your
       wishlist!']);
   }
   else
   {
       $wishlist = new Wishlist;

       $wishlist->user()->associate($request->user());

       $wishlist->product_id = $request->product_id;
        $wishlist->save();

       return redirect()->back()->with(['status' => 'success', 'message' => 'product added successfully.']);;
   }

}

public function delete(Request $request, Wishlist $wishlist)
    {
      
      $wishlist->delete();

       return back()->with(['status' => 'success', 'message' => 'product deleted successfully.']);
    }
}
