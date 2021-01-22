@foreach ($reviews as $review)

<div class="container">
      <!-- Trigger the modal with a button -->
      <!--button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal1">TEST</button>-->

      <!-- Modal -->
      <div class="modal fade" id="myModal1{{$review->id}}" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <div class="review-edit">
                    <div class="review-edit-block">
                     

                        <form  action="{{ route('review.update',['review' => $review->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                          <div class="form-group">

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
                            <textarea style="position:relative;left:10%;width:375px;" name="description" class="form-control" required>{{ $review->description }}</textarea>
                            
                          </div>
                          <div>
                            <button type="submit" class="btn btn-primary">Update Review</button>
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

 
     

     