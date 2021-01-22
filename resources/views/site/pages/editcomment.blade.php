@foreach ($comments as $comment)

<div class="container">
      <!-- Trigger the modal with a button -->
      <!--button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal1">TEST</button>-->

      <!-- Modal -->
      
            
           
      <div class="modal fade" id="{{$comment->id}}" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <div class="comment-edit">
                    <div class="comment-edit-block">
                        

                        <form action="{{ route('comment.update', $comment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                          <div class="form-group">
                            <textarea style="position:relative;left:10%;width:375px;" name="description" class="form-control" value="{{ $comment->description }}">{{ $comment->description }}</textarea>
                        
                          </div>
                          <div>
                            <button type="submit" class="btn btn-primary">Update Comment</button>
                          </div>


                        </form>

             
                    </div> 
                    </div>
                    </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      
      </div>          
    
                    @endforeach
     

         