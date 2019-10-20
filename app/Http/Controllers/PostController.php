<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Session;
use App\Category;
use App\Tag;
use Brian2694\Toastr\Facades\Toastr;

class PostController extends Controller
{
    public function index()     //route name post.all
    {
        $posts = Post::latest()->approved()->published()->paginate(9);
        return view('posts',compact('posts'));
    }
    public function details($slug)      //route name post.index
    {
        if($post = Post::where('slug',$slug)->approved()->published()->first())
        {
            $randomPost = Post::approved()->published()->take(3)->inRandomOrder()->get();

            $blogKey = 'blog_'.$post->id;
    
            if(!Session::has($blogKey))
            {
                $post->increment('view_count');
                Session::put($blogKey,1);
            }
            return view('post',compact('post','randomPost'));
        }
        else{
            Toastr::warning('Does not accept user illigel Query','Stop');
            return redirect()->back();
        }

    }

    public function categoryByPosts($slug)
    {
        $posts = Category::where('slug',$slug)->first()->posts;
        return view('categories',compact('posts','slug'));

    }
    public function tagByPosts($slug)
    {
        $posts = Tag::where('slug',$slug)->first()->posts;
        return view('tags',compact('posts','slug'));
    }
}
