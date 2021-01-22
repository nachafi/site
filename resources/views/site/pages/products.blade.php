@extends('site.app')
@section('title', 'products')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  
@section('content')
<section class="section-content bg padding-y">
    <div class="container">
    <div class="row">
    <aside class="col-sm-3">
    <div class="card card-filter">
   
                    
                    <div class="container">
        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
   
        <div>
            <div class="products-header">
                <h1 class="stylish-heading"></h1>
                <div>
                    <strong>Price: </strong>
                    <a href="{{ route('products.index', [ 'sort' => 'low_high']) }}">Low to High</a> |
                    <a href="{{ route('products.index', [ 'sort' => 'high_low']) }}">High to Low</a>

                </div>
                <div class="col-lg">
					@include('site.pages.productssidebar')
				</div>
            </div>
                    <!-- card-group-item.// -->
                  
                  
                </div>
               
           </aside>
                <!-- card.// -->
                    
                    <main class="col-sm-9">
        <div id="code_prod_complex">
        @if( session('status'))
                        <div class="alert alert-info">
                            {{ session('status')}}
                        </div>
                    @endif
                    <div class="container">
                    <div class="shop-found-selector">
									<div class="shop-found">
										<p><span></span> Product Found of <span></span></p>
									</div>
									<div class="shop-selector">
                                    
										<label>Sort By : </label>
                                       <form action="{{route('products.index')}}" method="get">
                                       @csrf
										<select name="sortBy" onchange="this.form.submit()">
											<option value="Default">Default</option>
                                            <option value="All">All</option>
											<option value="Price - Low to High">Price - Low to High</option>
											<option value="Price - High to Low"> Price - High to Low</option>
											<option value="Newest to Oldest">Newest to Oldest</option>
                                            <option value="Oldest to Newest">Oldest to Newest</option>
                                            <option value="A to Z">A to Z</option>
                                            <option value="Z to A">Z to A</option>
										</select>
                                        <button type="submit">Filter</button>
                                </form>
                                </div>
                                <div class="shop-selector">
                                    
										<label>Sort By : </label>
                                       <form action="{{route('products.index')}}" method="get">
                                       @csrf
										<select name="sort" onchange="this.form.submit()">
											<option value="Default">Default</option>
                                            <option value="All">All</option>
											<option value="Price - Low to High">Price - Low to High</option>
											<option value="Price - High to Low"> Price - High to Low</option>
											<option value="Newest to Oldest">Newest to Oldest</option>
                                            <option value="Oldest to Newest">Oldest to Newest</option>
										</select>
                                        <button type="submit">Filter</button>
                                </form>
                                </div>
								</div>
                     <div class="row">               
            @forelse($products as $product)
                    <div class="col-md-3">
                 
                    <figure class="card card-product">
                    
                            @if ($product->images->count() > 0)
                                <div class="img-wrap padding-y"><img src="{{ asset('storage/'.$product->images->first()->full) }}" alt=""></div>
                            @else
                                <div class="img-wrap padding-y"><img src="https://via.placeholder.com/176" alt=""></div>
                            @endif
                            
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
                     
                  
                   
                    @empty
                    <p>No Products found </p>
                @endforelse
                
            </div>
            
        </div>
        </div>
        </div>
        </div>
        
        </main>
        
    </div>
    
   
</section>
@stop
@push('scripts')
<script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
	 var sliderrange = $('#slider-range');
    var amountprice = $('#amount');
    var minPrice = parseFloat($('#productMinPrice').val());
    var maxPrice  = parseFloat($('#productMaxPrice').val());

    $(function() {
        sliderrange.slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [minPrice, maxPrice],
            slide: function(event, ui) {
                amountprice.val(ui.values[0] + "-" + ui.values[1]);
            }
        });
        amountprice.val(sliderrange.slider("values", 0) +
            "-" + sliderrange.slider("values", 1));
    });
	</script>
@endpush