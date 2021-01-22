@extends('site.app')
@section('title', $category->name)
@section('content')
<section class="section-pagetop bg-dark">
    <div class="container clearfix">
        <h2 class="title-page">{{ $category->name }} </h2>
    </div>
</section>

<section class="section-content bg padding-y">
    <div class="container">
    <div class="row">
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
   
            <main class="col-sm-9">
        <div id="code_prod_complex">
        
            <div class="row">
            
                @forelse($category->products as $product)
                    <div class="col-md-3">
                        <figure class="card card-product">
                            @if ($product->images->count() > 0)
                                <div class="img-wrap padding-y"><img src="{{ asset('storage/'.$product->images->first()->full) }}" alt=""></div>
                            @else
                                <div class="img-wrap padding-y"><img src="https://via.placeholder.com/176" alt=""></div>
                            @endif
                            <figcaption class="info-wrap">
                                <h4 class="title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h4>
                             <div>  
                            
                  <p>
                    @for ($i=0; $i < 5 ; $i++)
                   
                    @if($product->avg_rating - $i>= 1)
    
                      <span class="fas fa-star " style="color:orange;" ></span>
                    @elseif($product->avg_rating - $i>0)
                      <span class="fas fa-star-half" style="color:orange;" ></span>
                    @else
                    <span class="far fa-star " ></span>
                    @endif
                    @endfor
                    <span class="label-rating">{{$product->reviews_count}} {{ Str::plural('review', $product->reviews_count)}}</span>
                    
                  </p>
                  </div>
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
                    <p>No Products found in {{ $category->name }}.</p>
                    
                @endforelse
                
            </div>
            
           
        </div>
        </main>
        
       
                                    
    </div>
    
    </div>
</section>
@stop

@endpush
