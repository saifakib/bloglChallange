<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = $user->posts()->approved()->get();
        $pending = $user->posts()->notapproved()->count();
        $total_views = $posts->sum('view_count');
        //top author popular post
        $popular_posts = $user->posts()->approved()
                                    ->withCount('comments')
                                    ->withCount('favourite_to_users')
                                    ->orderBy('view_count','desc')
                                    ->orderBy('comments_count','desc')
                                    ->orderBy('favourite_to_users_count','desc')
                                    ->take(5)->get();
        $favourite = 0;
        //return $user->posts()->approved()->withCount('favourite_to_users')->get();
        return view('author.dashboard',compact('posts','favourite','pending','total_views','popular_posts'));
    }
}
