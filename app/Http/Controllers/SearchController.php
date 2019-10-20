<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('search');
        $posts = Post::where('title','LIKE',"%$search%")->approved()->published()->get();
        return view('search',compact('posts','search'));
 
    }
}
