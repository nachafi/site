@foreach($comments as $comment)

<div class="card-body row">
<div class="col-md-1">
<div class="mt-4 d-flex justify-content-center align-items-center flex-column">
<a href="{{ route('comment.addVote', ['comment' => $comment->id]) }}">
    <i class="fa fa-chevron-up bg-primary rounded p-1 text-white fa-x2"></i>
</a>
<p class="mt-3 lead">
    {{$comment->votes}}
</p>
<a href="{{ route('comment.removeVote', ['comment' => $comment->id]) }}">
    <i class="fa bg-primary text-white rounded p-1 fa-chevron-down fa-x2"></i>
</a>

</div>
</div>                      
<div class="display-comment">

    <strong>{{ $comment->user->fullName }}</strong>
    <p>{{ $comment->description }}</p>
    
    <a href="" id="reply"></a>
    <form method="post" action="{{ route('reply.add') }}">
        @csrf
        <div class="form-group">
            <input type="text" name="description" class="form-control" />
            <input type="hidden" name="product_id" value="{{ $product_id }}" />
            <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
        </div>
    
        <div class="form-group">
            <input type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" value="Reply" />
            
            </div>
           
            </form>
           
        
           <a class="btn btn-sm btn-primary" data-toggle="modal" href="#{{$comment->id}}" >edit</a>
           
            <form action="{{ route('comment.delete', $comment->id) }}" method="POST">
                       @csrf

                       @method('DELETE')

                       <button type="submit">Delete</button>
  
            </form>
           
            
           
           
    
    
    @include('site.pages.replies', ['comments' => $comment->replies])
    @include('site.pages.editcomment', ['comments' => $product->comments, 'product_id' => $product->id]) 
    @include('site.pages.editreply', ['comments' => $comment->replies]) 
    </div>
</div>
</div>
@endforeach 
