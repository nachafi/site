
<div class="col-md-3">
                    <figure class="card card-product">
                            @if ($product->images->count() > 0)
                                <div class="img-wrap padding-y"><img src="{{ asset('storage/'.$product->images->first()->full) }}" alt=""></div>
                            @else
                                <div class="img-wrap padding-y"><img src="https://via.placeholder.com/176" alt=""></div>
                            @endif
                            <span>hot</span>
			<div class="product-action">
				<a class="animate-left add-to-fav" title="Favorite"  product-slug="{{ $product->slug }}" href="">
					<i class="pe-7s-like"></i>
				</a>
				<a class="animate-top add-to-card" title="Add To Cart" href="" product-id="{{ $product->id }}"  product-slug="{{ $product->slug }}">
					<i class="pe-7s-cart"></i>
				</a>
				<a class="animate-right quick-view" title="Quick View" product-slug="{{ $product->slug }}" href="">
					<i class="pe-7s-look"></i>
				</a>
			</div>

                            <figcaption class="info-wrap">
                                <h4 class="title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h4>
                            </figcaption>
                           
                            <div class="bottom-wrap">
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-success float-right">View Details</a>
                               
                                
                                @if ($product->sale_price != 0)
                                    <div class="price-wrap h5">
                                        <span class="price"> {{ config('settings.currency_symbol').$product->sale_price }} </span>
                                        <del class="price-old"> {{ config('settings.currency_symbol').$product->price }}</del>
                                    </div>
                                @else
                                    <div class="price-wrap h5">
                                        <span class="price"> {{ config('settings.currency_symbol').$product->price }} </span>
                                    </div>
                                @endif
                  
                            </div>
                         
                        </figure>
                    </div>
    