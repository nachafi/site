<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    
    

   

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var array
     */
    protected $fillable = [
        'parent_id','brand_id', 'sku', 'name', 'slug', 'description', 'quantity',
        'weight', 'price', 'sale_price', 'status', 'featured',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'quantity'  =>  'integer',
        'brand_id'  =>  'integer',
        'status'    =>  'boolean',
        'featured'  =>  'boolean'
    ];

    /**
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function productInventory()
	{
		return $this->hasOne('App\Models\ProductInventory');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    	/**
	 * Define relationship with the Variants
	 *
	 * @return void
	 */
	public function variants()
	{
		return $this->hasMany(Product::class, 'parent_id')->orderBy('price', 'ASC');
    }
    /**
	 * Define relationship with the Parent
	 *
	 * @return void
	 */
	public function parent()
	{
		return $this->belongsTo(Product::class, 'parent_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'parent_product_id');
    }
     /**
     * The has Many Relationship
     *
     * @var array
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function reviews()
    {
      return $this->hasMany(Review::class);
    }

    // The way average rating is calculated (and stored) is by getting an average of all ratings, 
	// storing the calculated value in the rating_cache column (so that we don't have to do calculations later)
	// and incrementing the rating_count column by 1

    public function recalculateRating()
    {
    	$reviews = $this->reviews();
	    $avgRating = $reviews->avg('rating');
		$this->avg_rating = round($avgRating,2);
		$this->reviews_count = $reviews->count();
    	$this->save();
    }
     
    
     public function likes()
    {
      return $this->hasMany(LikeDislike::class, 'product_id')->sum('like');;
    }

    public function dislikes()
    {
      return $this->hasMany(LikeDislike::class, 'product_id')->sum('dislike');;
    }

    public function wishlist(){
        return $this->hasMany(Wishlist::class);
     }

     public function scopeActive($query)
     {
         return $query->where('status', 1)
           
                 ->where('parent_id', NULL);
     }
     	/**
	 * Get price label
	 *
	 * @return string
	 */
	public function priceLabel()
	{
		return ($this->variants->count() > 0) ? $this->variants->first()->price : $this->price;
	}
	/**
	 * Define relationship with the OrderItem
	 *
	 * @return void
	 */
	public function orderItems()
	{
		return $this->hasMany('App\Models\OrderItem');
	}
    	/**
	 * Scope popular products
	 *
	 * @param Eloquent $query query builder
	 * @param int      $limit limit
	 *
	 * @return Eloquent
	 */
	public function scopePopular($query, $limit = 10)
	{
		$month = now()->format('m');

		return $query->selectRaw('products.*, COUNT(order_items.id) as total_sold')
			->join('order_items', 'order_items.product_id', '=', 'products.id')
			->join('orders', 'order_items.order_id', '=', 'orders.id')
			->whereRaw(
				'orders.status = :order_satus AND MONTH(orders.order_date) = :month',
				[
					'order_status' => Order::COMPLETED,
					'month' => $month
				]
			)
			->groupBy('products.id')
			->orderByRaw('total_sold DESC')
			->limit($limit);
	}
   
}

