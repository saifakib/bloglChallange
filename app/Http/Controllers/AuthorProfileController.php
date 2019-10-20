<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;

class AuthorProfileController extends Controller
{
    public function index($author)
    {
        $targetAuthor = User::where('username',$author)->first();
        $posts = $targetAuthor->posts()->approved()->published()->get();
        return view('authorProfile',compact('posts','targetAuthor'));
    }
}
