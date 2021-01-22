@extends('site.app')
@section('title', 'wishlist')
@section('content')

       
<section class="section-content bg padding-y">
    <div class="container">
    <div class="row">
    <main class="col-sm-9">
        <div id="code_prod_complex">
    <div class="container">
   
    @if( session('message'))
            <div class="alert alert-success">
                {{ session('message')}}
            </div>
        @endif
            <div class="row">
@if (auth()->user()->wishlist->count() )
@foreach ($wishlists as $wishlist)

<div class="col-md-3">
                        <figure class="card card-product">
                            @if ($wishlist->product->images->count() > 0)
                                <div class="img-wrap padding-y"><img src="{{ asset('storage/'.$wishlist->product->images->first()->full) }}" alt=""></div>
                            @else
                                <div class="img-wrap padding-y"><img src="https://via.placeholder.com/176" alt=""></div>
                            @endif
                            <figcaption class="info-wrap">
                                <h4 class="title"><a href="{{ route('product.show', $wishlist->product->slug) }}">{{ $wishlist->product->name }}</a></h4>
                            </figcaption>
                           
                            <div class="bottom-wrap">
                                <a href="{{ route('product.show', $wishlist->product->slug) }}" class="btn btn-sm btn-success float-right">View Details</a>
                               
                                
                                @if ($wishlist->product->sale_price != 0)
                                    <div class="price-wrap h5">
                                        <span class="price"> {{ config('settings.currency_symbol').$wishlist->product->sale_price }} </span>
                                        <del class="price-old"> {{ config('settings.currency_symbol').$wishlist->product->price }}</del>
                                    </div>
                                @else
                                    <div class="price-wrap h5">
                                        <span class="price"> {{ config('settings.currency_symbol').$wishlist->product->price }} </span>
                                    </div>
                                @endif
                  
                            </div>
                         
                        </figure>
                        <div>
                        <form action="{{ route('wishlist.delete', ['wishlist' => $wishlist->id]) }}" method="POST">
                       @csrf

                       @method('DELETE')

                       <button type="submit">Delete</button>
  
            </form> 
            </div>
                    </div>
                     
                           
          
@endforeach
@endif
          
</div>

            
           
            </div>
            </main>                                 
</div>
    
    </div>
    <div class="col-md-9">
    {{$wishlists->links()}}
    <div>
</section>
@stop