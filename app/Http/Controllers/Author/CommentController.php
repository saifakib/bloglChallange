<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use Brian2694\Toastr\Facades\Toastr;

class CommentController extends Controller
{
    public function index()
    {
        $posts = Auth::user()->posts;
        $totalComment = 0;
        foreach($posts as $post)
        {
            $totalComment += $post->comments->count();
        }
        return view('author.comment',compact('posts','totalComment'));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if($comment->post->user->id == Auth::id())
        {
            $comment->delete();
            Toastr::success('Comment Deleted','Comment');
                
        }
        else{
            Toastr::success('You are not authorized to delete this comment','Unauthorize');
        }
        return redirect()->back();
    }
}
