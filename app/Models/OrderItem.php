<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'price',
		'base_total',
		'tax_amount',
		'tax_percent',
		'discount_amount',
		'discount_percent',
		'sub_total',
		'sku',
		'type',
		'name',
		'weight',
		'attributes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
