
<div class="shop-sidebar mr-50">
    <form method="GET" action="{{ url('products')}}">
		<div class="sidebar-widget mb-40">
			<h3 class="sidebar-title">Filter by Price</h3>
			<div class="price_filter">
				<div id="slider-range"></div>
				<div class="price_slider_amount">
					<div class="label-input">
						<label>price : </label>
						<input type="text" id="amount" name="price"  placeholder="Add Your Price" style="width:170px" />
						<input type="hidden" id="productMinPrice" value="{{$minPrice}}"/>
						<input type="hidden" id="productMaxPrice" value="{{$maxPrice}}"/>
					</div>
					<button type="submit">Filter</button> 
				</div>
			</div>
		</div>
    </form>
	<article class="card-group-item">
                        <header class="card-header">
						
                            <a href="#" data-toggle="collapse" data-target="#collapse33">
							<form method="GET" action="{{ url('products')}}">
                                <i class="icon-action fa fa-chevron-down"></i>
                                <h6 class="title">By Price  </h6>
                            </a>
                        </header>
                        <div class="filter-content collapse show" id="collapse33">
                            <div class="card-body">
                                <input type="range" class="custom-range" min="0" max="100" name="" >
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Min</label>
                                        <input class="form-control" placeholder="$0" type="number"  >
										
                                
									</div>
                                    <div class="form-group text-right col-md-6">
                                        <label>Max</label>
                                        <input class="form-control" placeholder="$1,0000" type="number"  >
										
                                    </div>
                                </div>
                                <!-- form-row.// -->
                                <button class="btn btn-block btn-outline-primary" type="submit">APPLY</button> 
                            </div>
                            <!-- card-body.// -->
                        </div>
                        <!-- collapse .// -->
                    </article>
					
    @if ($categories)
		<div class="sidebar-widget mb-45">
			<h3 class="sidebar-title">Categories</h3>
			<div class="sidebar-categories">
				<ul>
					@foreach ($categories as $category)
					@if ($category->id!=1)
							<li><a href="{{ url('products?category='. $category->slug) }}">{{ $category->name }}</a></li>
					@endif
					@endforeach
				</ul>
			</div>
		</div>
	@endif
    
    @if ($colors)
		<div class="sidebar-widget sidebar-overflow mb-45">
			<h3 class="sidebar-title">color</h3>
			<div class="sidebar-categories">
				<ul>
					@foreach ($colors as $color)
						<li><a href="{{ url('products?value='. $color->value) }}">{{ $color->value }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
    @endif

    @if ($sizes)
		<div class="sidebar-widget mb-40">
			<h3 class="sidebar-title">size</h3>
			<div class="product-size">
				<ul>
					@foreach ($sizes as $size)
						<li><a href="{{ url('products?value='. $size->value) }}">{{ $size->value }}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	@endif
	
					@foreach ($attributes as $attribute)
						<h3>{{ $attribute->name }}</h3>
						<ul>
				@foreach($attributeValues as $attributeValue)
					@if ($attributeValue->attribute_id == $attribute->id)

							
					<li><a href="{{ url('products?value='. $attributeValue->value) }}"> {{ $attributeValue->value  }}</a></li>
						
					@endif
					@endforeach

</ul>
				@endforeach

</div>
