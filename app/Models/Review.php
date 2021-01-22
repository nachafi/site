<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'product_id', 'rating', 'description'];
   
    /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
      return $this->belongsTo(Product::class, 'product_id');
    }

      // Attribute presenters
      public function getTimeagoAttribute()
      {
        $date = \Carbon\Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
        return $date;
      }
  
      // this function takes in product ID, comment and the rating and attaches the review to the product by its ID, then the average rating for the product is recalculated
      public function storeReviewForProduct($productID, $description, $rating)
      {
          $product = Product::find($productID);
  
          $this->user_id = Auth::user()->id;
          $this->description = $description;
          $this->rating = $rating;
          $product->reviews()->save($this);
  
          // recalculate ratings for the specified product
          $product->recalculateRating($rating);
      }
}
