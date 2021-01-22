@extends('site.app')
@section('title', 'Homepage')

@section('content')
<section class="section-main bg padding-top-sm">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <!-- ================= main slide ================= -->
                    <div class="owl-init slider-main owl-carousel" data-items="1" data-dots="false" data-nav="true">
                        <div class="item-slide">
                            <img src="{{asset('frontend/images/banners/slide1.jpg')}}">
                        </div>
                        <div class="item-slide rounded">
                            <img src="{{asset('frontend/images/banners/slide2.jpg')}}">
                        </div>
                        <div class="item-slide rounded">
                            <img src="{{asset('frontend/images/banners/slide3.jpg')}}">
                        </div>
                    </div>
                    <!-- ============== main slidesow .end // ============= -->
                </div>
                <!-- col.// -->
           
                   
                    <!-- card.// -->
                   
                    <!-- card.// -->
                </div>
                <!-- col.// -->
            </div>
        </div>
        <!-- container .//  -->
    </section>
    <!-- ========================= SECTION MAIN END// ========================= -->
    <!-- ========================= Blog ========================= -->
    <section class="section-content padding-y-sm bg">
        <div class="container">
            <header class="section-heading heading-line">
                <h4 class="title-section bg">Featured Categories</h4>
            </header>
            <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4">
                
                    <div class="card-banner" style="height:250px; background-image: url('{{ asset('storage/'.$category->image) }}');">
                        <article class="overlay overlay-cover d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <a href="{{ route('category.show', $category->slug) }}" class="btn btn-warning btn-sm"> View All </a>
                            </div>
                        </article>
                    </div>
                   
                    <!-- card.// -->
                </div>
                @endforeach
            </div> 
        </div>
    </section>
    <!-- ========================= Blog .END// ========================= -->

    <!-- ========================= SECTION CONTENT ========================= -->
    <section class="section-content padding-y-sm bg">
        <div class="container">

            <header class="section-heading heading-line">
                <h4 class="title-section bg">FEATURED PRODUCTS</h4>
            </header>
            
           
            
            <div class="row">
            <div class="owl-init slider-main owl-carousel" data-items="5" data-dots="false" data-nav="true">          
            @foreach($products as $product)
            
                    <div class="col-md-9">
                    
                        <figure class="">
                        
                            @if ($product->images->count() > 0)
                            
                        
                            <div class="item-slide">
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
                     
                         
                   
              
                @endforeach 
            </div>      
            <!-- row.// -->

        </div>
        <!-- container .//  -->
    </section>

    <!-- ========================= SECTION ITEMS ========================= -->
    <section class="section-request bg padding-y-sm">
        <div class="container">
            <header class="section-heading heading-line">
                <h4 class="title-section bg text-uppercase">Recently Added</h4>
            </header>
            <div class="row">
            
                    @foreach($products as $product)
                    <div class="col-md-2">
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
                     
                         
                 
                @endforeach 
            </div> 
              
            </div>
            <!-- row.// -->

        </div>
        <!-- container // -->
    </section>

    <!-- ========================= Subscribe ========================= -->
    <section class="section-subscribe bg-primary padding-y-lg">
        <div class="container">

            <p class="pb-2 text-center white">Delivering the latest product trends and industry news straight to your inbox</p>

            <div class="row justify-content-md-center">
                <div class="col-lg-4 col-sm-6">
                    <form class="row-sm form-noborder">
                        <div class="col-8">
                            <input class="form-control" placeholder="Your Email" type="email">
                        </div>
                        <!-- col.// -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-block btn-warning"> <i class="fa fa-envelope"></i> Subscribe </button>
                        </div>
                        <!-- col.// -->
                    </form>
                    <small class="form-text text-white-50">We’ll never share your email address with a third-party. </small>
                </div>
                <!-- col-md-6.// -->
            </div>

        </div>
        <!-- container // -->
    </section>

@stop
