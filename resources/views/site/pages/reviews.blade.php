
@foreach ($reviews as $review)


<hr>
                <div class="row">
                  <div class="col-md-12">
                    @for ($i=1; $i <= 5 ; $i++)
                      <span class="fas fa-star{{ ($i <= $review->rating) ? '' : '-empty'}}" style="color:orange;"></span>
                    @endfor
                    
                    <a href="{{ route('review.edit',['review' => $review->id]) }}" data-toggle="modal" data-target="#myModal1{{$review->id}}" class="btn btn-sm btn-primary"  ><i class="fa fa-edit"></i></a>
                   
                    <form action="{{ route('review.delete', ['review' => $review->id]) }}" method="POST">
                       @csrf

                       @method('DELETE')

                       <button type="submit">Delete</button>
                    </form>
                    
                               
                    {{ $review->user ? $review->user->fullName : 'Anonymous'}} <span class="pull-right">{{$review->timeago}}</span> 
                    
                    @for ($i=0; $i < 5 ; $i++)
                   
                   @if($review->rating - $i>= 1)
   
                     <span class="fas fa-star " style="color:orange;" ></span>
                   @elseif($review->rating - $i>0)
                     <span class="fas fa-star-half" style="color:orange;" ></span>
                   @else
                   <span class="far fa-star " ></span>
                   @endif
                   @endfor
                    <p>{{{$review->description}}}</p>
                  </div>
                </div>
            
            
        
       
     
                            
                            
                            <tr data-entry-id="{{ $review->id }}">
                           

                                <td >{{ $review->created_at }}</td>
                                <td >{{ $review->product->name }}</td>
                                <td >{{ $review->user->fullName }}</td>
                                <td >{{ $review->rating }}</td>
                               
                                <td >{{ $review->description}}</td>
                                </tr>
                                
                            

                               
                                @endforeach
              