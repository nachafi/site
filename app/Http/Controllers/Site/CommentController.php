<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;

class CommentController extends Controller
{
    
    public function show(Request $request)
    {

    }
    
    public function store(Request $request)
    {
        $comment = new Comment;

        $comment->description = $request->description;

        $comment->user()->associate($request->user());

        $product = Product::find($request->product_id);

        $product->comments()->save($comment);

        return back();
    }

    public function replyStore(Request $request)
    {
        $reply = new Comment();

        $reply->description = $request->get('description');

        $reply->user()->associate($request->user());

        $reply->parent_id = $request->get('comment_id');

        $product = Product::find($request->get('product_id'));

        $product->comments()->save($reply);

        return back();

    }


    public function edit( Comment $comment)
    {
       
       
        $comment=Comment::findOrFail($comment->id);
   
        return view('comment.edit', compact('comment'));
        
    }

    public function update( Request $request, Comment $comment)
    {   
        
       // if (auth()->user()->id !== $review->user_id) {
         //   return back()->with(['message' => 'cant delete this review']);
        //}
        $comment=Comment::findOrFail($comment->id);
        $comment->description = $request->description;

      

        $comment->save();

        
        return back()->with(['status' => 'success', 'message' => 'Comment updated successfully.']);
    
    }
    public function delete( Request $request, Comment $comment)
    {   
        
       // if (auth()->user()->id !== $review->user_id) {
         //   return back()->with(['message' => 'cant delete this review']);
        //}
        
        $comment->delete();
        return back()->with(['status' => 'success', 'message' => 'Comment deleted successfully.']);
    
    }


    public function addVote(Comment $comment){
        $comment->increment('votes');
        return redirect()->back();
    }

    public function removeVote(Comment $comment){
        $comment->decrement('votes');
        return redirect()->back();
    }
  
}
