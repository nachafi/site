<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewController extends Controller
{
       /**
     * Store a newly created Review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        $review = new Review;

        $review->rating = $request->rating;

        $review->description = $request->description;

        $review->user()->associate($request->user());

        $product = Product::find($request->product_id);

        

        $product->reviews()->save($review);

        $product->recalculateRating($review);

        

        return back()->with(
            'message' , 'review added ,, thank you'
  );
    }


    public function edit($id)
    {
       
       
        $review=Review::find($id);
   
        return view('review.edit', compact('review'));
        
    }
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review, Product $product)
    {

        //$review=Review::where('user_id',$request->user()->id)->find($request->id);
        request()->validate([
            'rating' => 'required|numeric|min:0|max:5',
            'description' => 'required|string'
            
        ]);

        $review->rating = $request->rating;
        $review->description= $request->description;
        $product = $review->product;
        $review->save();
        $product->recalculateRating();

        return back()->with(['message' => 'Review Updated', 'review' => $review]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function delete( Request $request, Review $review)
    {   
        
        if (auth()->user()->id !== $review->user_id) {
            return back()->with(['message' => 'cant delete this review']);
        }
        //$review=Review::where('user_id',$request->user()->id)->findOrFail($request->id);
        $product = $review->product;
        $review->delete();

        $product->recalculateRating();
        return back()->with(['status' => 'success', 'message' => 'Review deleted successfully.']);
        
    }
}
