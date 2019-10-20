<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class FavouriteController extends Controller
{
    public function index()
    {
        $posts = Auth::User()->favourite_posts;
        return view('admin.favourite',compact('posts'));
    }
    public function destroy($post)
    {
        $user = Auth::User();

            $user->favourite_posts()->detach($post);
            Toastr::success('Post succesfully removed your favourite lists','Removed');
            return redirect()->back();
    }
}
