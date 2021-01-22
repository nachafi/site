@extends('site.app')
@section('title', $product->name)

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

<style>

    .display-comment .display-comment {
        margin-left: 40px
    }
    
      /* Enhance the look of the textarea expanding animation */
      .animated {
        -webkit-transition: height 0.2s;
        -moz-transition: height 0.2s;
        transition: height 0.2s;
      }
      .stars {
        margin: 20px 0;
        font-size: 24px;
        color: #d17581;
      }
    div.stars {
  width: 270px;
  display: inline-block;
}

input.star { display: none; }

label.star {
  float: right;
  padding: 10px;
  font-size: 36px;
  color: #444;
  transition: all .2s;
}

input.star:checked ~ label.star:before {
  content: '\f005';
  color: #FD4;
  transition: all .25s;
}

input.star-5:checked ~ label.star:before {
  color: #FE7;
  text-shadow: 0 0 20px #952;
}

input.star-1:checked ~ label.star:before { color: #FD4; }

label.star:hover { transform: rotate(-15deg) scale(1.3); }

label.star:before {
  content: '\f006';
  font-family: FontAwesome;
}
</style>
@section('content')
    <section class="section-pagetop bg-dark">
        <div class="container clearfix">
            <h2 class="title-page">{{ $product->name }}</h2>
        </div>
    </section>
    <section class="section-content bg padding-y border-top" id="site">
        <div class="container">
        @if( session('message'))
            <div class="alert alert-success">
                {{ session('message')}}
            </div>
        @endif
        @if( session('error'))
            <div class="alert alert-danger">
                {{ session('error')}}
            </div>
        @endif
      
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="row no-gutters">
                            <aside class="col-sm-5 border-right">
                                <article class="gallery-wrap">
                                
                                    @if ($product->images->count() > 0)
                                        <div class="img-big-wrap">
                                        
                                            <div class="padding-y">
                                               
                                                <a href="{{ asset('storage/'.$product->images->first()->full) }}" data-fancybox="">
                                                    <img src="{{ asset('storage/'.$product->images->first()->full) }}" alt="">
                                                </a>
                                               
                                            </div>
                                        </div>
                                    @else
                                        <div class="img-big-wrap">
                                            <div>
                                                <a href="https://via.placeholder.com/176" data-fancybox=""><img src="https://via.placeholder.com/176"></a>
                                            </div>
                                        </div>
                                    @endif
                                     @if ($product->images->count() > 0)
                                        <div class="img-small-wrap">
                                            @foreach($product->images as $image)
                                                <div class="item-gallery">
                                                    <img src="{{ asset('storage/'.$image->full) }}" alt="">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </article>
                            </aside>
                            <aside class="col-sm-7">
                                <article class="p-5">
                                    <h3 class="title mb-3">{{ $product->name }}</h3>
                                    <form action="{{route('wishlist.add')}}"  method="post">
  {{csrf_field()}}
  
  <input name="product_id" type="hidden" value="{{$product->id}}" />
  <input type="submit" value="Add to wishlist" />
</form>
<h4 class="title"><a href="{{ route('wishlist.show') }}">wishlists</a></h4>
                                    <dl class="row">
                                        <dt class="col-sm-3">SKU</dt>
                                        <dd class="col-sm-9">{{ $product->sku }}</dd>
                                        <dt class="col-sm-3">Weight</dt>
                                        <dd class="col-sm-9">{{ $product->weight }}</dd>
                                    </dl>
                                    <div class="mb-3">
                                        @if ($product->sale_price > 0)
                                            <var class="price h3 text-danger">
                                                <span class="currency">{{ config('settings.currency_symbol') }}</span><span class="num" id="productPrice">{{ $product->sale_price }}</span>
                                                <del class="price-old"> {{ config('settings.currency_symbol') }}{{ $product->price }}</del>
                                            </var>
                                        @else
                                            <var class="price h3 text-success">
                                                <span class="currency">{{ config('settings.currency_symbol') }}</span><span class="num" id="productPrice">{{ $product->price }}</span>
                                            </var>
                                        @endif
                                    </div>
                                    <hr>
                
                   
                                    <form action="{{ route('product.add.cart') }}" method="POST" role="form" id="addToCart">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <dl class="dlist-inline">
                                                @foreach($attributes as $attribute)
                                                        @php
                                                            if ($product->attributes->count() > 0) {
                                                                $attributeCheck = in_array($attribute->id, $product->attributes->pluck('attribute_id')->toArray());
                                                            } else {
                                                                $attributeCheck = [];
                                                            }
                                                        @endphp
                                                        @if ($attributeCheck)
                                                            <dt>{{ $attribute->name }}: </dt>
                                                            <dd>
                                                                <select class="form-control form-control-sm option" style="width:180px;" name="{{ strtolower($attribute->name ) }}">
                                                                    <option data-price="0" value="0"> Select a {{ $attribute->name }}</option>
                                                                    @foreach($product->attributes as $attributeValue)
                                                                        @if ($attributeValue->attribute_id == $attribute->id)
                                                                            <option
                                                                                data-price="{{ $attributeValue->price }}"
                                                                                value="{{ $attributeValue->value }}"> {{ ucwords($attributeValue->value . ' +'. $attributeValue->price) }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </dd>
                                                        @endif
                                                    @endforeach
                                                </dl>
                                            </div>
                                        </div>
                                        <hr>
                                      
            

                  <div>
                  <p class="pull-right">{{$product->reviews_count}} {{ Str::plural('review', $product->reviews_count)}}</p>
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
                    {{ number_format($product->avg_rating, 2)}} stars
                  </p>
                  @for ($i=1; $i <= 5 ; $i++)
                      <span class="fas fa-star{{ ($i <= $product->avg_rating) ? '' : '-empty'}}" style="color:orange;"></span>
                    @endfor
                    
              </div>
          
                                      
           
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <dl class="dlist-inline">
                                                    <dt>Availability: </dt>
                                                    <dd>
                                                   
                                                       @if($product->quantity==0)
                                                       <span class="badge badge-danger"> Not In stock</span>
                                                       @else
                                                       <span class="badge badge-success"> In stock</span>
                                                       @endif
                                                    
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <dl class="dlist-inline">
                                                    <dt>Availability: </dt>
                                                    <dd>
                                        {{ $product->quantity<1 ? 'No Item is available' : $product->quantity.' item in stock' }} 
                                        </dd>
                                                </dl>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <dl class="dlist-inline">
                                                    <dt>Quantity: </dt>
                                                    <dd>
                                                        <input class="form-control" type="number" min="0" value="0" max="{{ $product->quantity }}" name="qty" style="width:70px;">
                                                        <input type="hidden" name="productId" value="{{ $product->id }}">
                                                        <input type="hidden" name="price" id="finalPrice" value="{{ $product->sale_price != '' ? $product->sale_price : $product->price }}">
                                                    </dd>
                                                </dl>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-success"><i class="fas fa-shopping-cart"></i> Add To Cart</button>
                                    </form>
            
                                  

              
                                    <div>
                                    <small class="float-right">
                <span title="Likes" id="save" data-type="like" data-post="{{ $product->id}}" class="mr-2 btn btn-sm btn-outline-primary d-inline font-weight-bold">
                    Like
                    <span class="like-count">{{ $product->likes() }}</span>
                </span>
                <span title="Dislikes" id="save" data-type="dislike"  data-post="{{ $product->id}}" class="mr-2 btn btn-sm btn-outline-danger d-inline font-weight-bold">
                    Dislike
                    <span class="dislike-count">{{ $product->dislikes() }}</span>
                </span>
            </small>
                   </div>
                     
                     
                                </article>
                            </aside>
                        </div>
                    </div>
                </div>
              
                <div class="product-description-review-area pb-90">
		<div class="container">
			<div class="product-description-review text-center">
				<div class="description-review-title nav" role=tablist>
					<a class="active" href="#pro-dec" data-toggle="tab" role="tab" aria-selected="true">
						Description
					</a>
					<a href="#pro-review" data-toggle="tab" role="tab" aria-selected="false">
						Reviews (0)
					</a>
				</div>
				<div class="description-review-text tab-content">
					<div class="tab-pane active show fade" id="pro-dec" role="tabpanel">
                    <div class="col-md-12">
                    <article class="card mt-4">
                        <div class="card-body">
                            {!! $product->description !!}
                        </div>
                    </article>
                </div>
					</div>
					<div class="tab-pane fade" id="pro-review" role="tabpanel">
						<a href="#">Be the first to write your review!</a>
                        <div>
                        <h3>Leave a review</h3>
                        @if (!$product->reviews()->where('user_id', auth()->id())->count() && $product->user_id != auth()->id())
    

    <form action="{{ route('review.add') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}" />
        Your rating:
        <br />
        <select name="rating">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option >4</option>
            <option>5</option>
        </select>
        <br /><br />
        Comment (optional):
        <br />
        <textarea name="description"></textarea>
        <br /><br />
        <input type="submit" value="Save rating" />
        
    </form>
    </div>
    <div class="post_ratings">
                  
    <form action="{{ route('review.add') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}" />
        Your rating:
                  <div class="rating_submit_inner">
                      <input id="radio1" type="radio" name="rating" value="5" class="star"/>5
                      <label for="radio1">&#9733;</label>
                      <input id="radio2" type="radio" name="rating" value="4" class="star"/>4
                      <label for="radio2">&#9733;</label>
                      <input id="radio3" type="radio" name="rating" value="3" class="star"/>3
                      <label for="radio3">&#9733;</label>
                      <input id="radio4" type="radio" name="rating" value="2" class="star"/>2
                      <label for="radio4">&#9733;</label>
                      <input id="radio5" type="radio" name="rating" value="1" class="star"/>1
                      <label for="radio5">&#9733;</label>
                  </div>
                  <input type="submit" value="Save rating" />
    </form>
    </div>
    @endif
    <div class="stars">
    <form action="{{ route('review.add') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}" />
        Your rating:
    <input class="star star-5" id="star-5" type="radio" name="rating" value="5"/>
    <label class="star star-5" for="star-5"></label>
    <input class="star star-4" id="star-4" type="radio" name="rating" value="4"/>
    <label class="star star-4" for="star-4"></label>
    <input class="star star-3" id="star-3" type="radio" name="rating" value="3"/>
    <label class="star star-3" for="star-3"></label>
    <input class="star star-2" id="star-2" type="radio" name="rating" value="2"/>
    <label class="star star-2" for="star-2"></label>
    <input class="star star-1" id="star-1" type="radio" name="rating" value="1"/>
    <label class="star star-1" for="star-1"></label>
    <input type="submit" value="Save rating" />
    </form>
  
    </div>
    <div>
    <h5>reviews:</h5>
                    @include('site.pages.reviews', ['reviews' => $product->reviews, 'product_id' => $product->id])
                                    @include('site.pages.modal', ['reviews' => $product->reviews, 'product_id' => $product->id])
                   
                                    </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
             
               
        
            </div>
        </div>
    </section>
   
    <section class="section-content padding-y-sm bg">
        <div class="container">
                <div class="card-body">
               
                <div class="card-header"><h5>Display Comments</h5></div>
                
              
                


                    @include('site.pages.replies', ['comments' => $product->comments, 'product_id' => $product->id])
                 
                   
                    
                
                
                 </div>
                 </div>
                 
   
               
               <div class="card-body">
              
                <h5>Leave a comment</h5>
                <form method="post" action="{{ route('comment.add') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="description" class="form-control" />
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" value="Add Comment" />
                    </div>
                </form>
       
               </div>
               
               </div>
        <!-- container .//  -->
    </section>
    <section class="section-content padding-y-sm bg">
        <div class="container">

            <header class="section-heading heading-line">
                <h4 class="title-section bg">RELATED PRODUCTS</h4>
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
@stop
@push('scripts')
<script>
        $(document).ready(function () {
            $('#addToCart').submit(function (e) {
                if ($('.option').val() == 0) {
                    e.preventDefault();
                    alert('Please select an option');
                }
            });
    
            $('.option').change(function () {
                $('#productPrice').html("{{ $product->sale_price != '' ? $product->sale_price : $product->price }}");
                let extraPrice = $(this).find(':selected').data('price');
                let price = parseFloat($('#productPrice').html());
                let finalPrice = (Number(extraPrice) + price).toFixed(2);
                $('#finalPrice').val(finalPrice);
                $('#productPrice').html(finalPrice);
            });
        });

        $(document).on('click','#save',function(){
    var _post=$(this).data('post');
    var _type=$(this).data('type');
    var vm=$(this);
    // Run Ajax
    $.ajax({
        url:"{{ route('product.save',['product'=>$product->id] )}}",
        type:"post",
        dataType:'json',
        data:{
            post:_post,
            type:_type,
            _token:"{{ csrf_token() }}"
        },
        beforeSend:function(){
            vm.addClass('disabled');
        },
        success:function(res){
            if(res.bool==true){
                vm.removeClass('disabled').addClass('active');
                vm.removeAttr('id');
                var _prevCount=$("."+_type+"-count").text();
                _prevCount++;
                $("."+_type+"-count").text(_prevCount);
            }
        }   
    });
});
    </script>
@endpush