<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\Category;
use App\Tag;
use App\User;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::approved()->published()->get();
        $pending_posts = Post::notapproved()->count();
        $total_views = Post::sum('view_count');
        $total_categories = Category::all()->count();
        $total_tags = Tag::all()->count();
        $total_authors = User::where('role_id',2)->count();
        $today_author = User::authors()
                            ->where('created_at', Carbon::today())->count();
        //Popular Post
        $popular_posts = Post::approved()->published()
                                ->withCount('favourite_to_users')
                                ->withCount('comments')
                                ->orderBy('view_count','desc')
                                ->orderBy('favourite_to_users_count')
                                ->orderBy('comments_count','desc')
                                ->take(5)->get();
        $favourite = 0;
        //active authors
        $active_authors = User::where('role_id',2)
                                ->withCount('posts')
                                ->withCount('comments')
                                ->withCount('favourite_posts')
                                ->orderBy('posts_count')
                                ->orderBy('comments_count')
                                ->orderBy('favourite_posts_count')
                                ->take(5)->get();
        return view('admin.dashboard',compact('posts','pending_posts','total_views','total_categories',
                        'total_tags','total_authors','today_author','popular_posts','favourite','active_authors'));

    }
}
