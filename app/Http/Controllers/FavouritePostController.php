<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class FavouritePostController extends Controller
{
    public function add($post)
    {
        $user = Auth::User();
        $isfavourite = $user->favourite_posts()->where('post_id',$post)->count();

        if($isfavourite == 0)
        {
            $user->favourite_posts()->attach($post);
            Toastr::success('Post succesfully added your favourite lists','Listed');
            return redirect()->back();
        }
        else{
            $user->favourite_posts()->detach($post);
            Toastr::warning('Post succesfully removed your favourite lists','Removed');
            return redirect()->back();
        }
    }
}
