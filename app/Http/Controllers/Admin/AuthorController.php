<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authors = User::authors()
                        ->withCount('posts')
                        ->withCount('comments')
                        ->withCount('favourite_posts')
                        ->get();
        $view_count = 0;
        return view('admin.author',compact('authors','view_count'));
    }

    public function destroy($author)
    {
        $targetAuthor = User::findOrFail($author);
        if(Storage::disk('public')->exists('profile/author/'.$targetAuthor->image))
        {
            Storage::disk('public')->delete('profile/author/'.$targetAuthor->image);
        }
        $targetAuthor->delete();
        Toastr::success('Author is Removed','Author Removed');
        return redirect()->back();
    }
}
