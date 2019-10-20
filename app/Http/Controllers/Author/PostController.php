<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use App\Tag;
use App\User;
use App\Notifications\NewAuthorPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->posts()->latest()->get();
        return view('author.post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        return view('author.post.create',compact('tags','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'image' => 'required|mimes:jpeg,jpg,JPG,bmp,png,wepp',
            'body' => 'required',
            'categories' => 'required',
            'tags' => 'required'
        ]);

        $image = $request->file('image');
        $slug = preg_replace('/\s+/u', '-', trim($request->title));

        if(isset($image)){
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension(); //laravel-2019-10-01-45n44.png

            //check posts dir is exists
            if(!Storage::disk('public')->exists('posts'))
            {
                Storage::disk('public')->makeDirectory('posts');
            }
            //resize image for post and upload
            $postImage = Image::make($image)->resize(1600,1066)->save();
            Storage::disk('public')->put('posts/'.$imageName,$postImage);
        }
        else{
            $imageName = "default.png";
        }

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        if(isset($request->status))
        {
            $post->status = true;
        }else{
            $post->status = false;
        }
        //author need to approval from admin 
        $post->is_approved = false;
        $post->save();
        //many to many relation
        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        //all admin user whose role is admin
        $users = User::where('role_id','1')->get();
        Notification::send($users, new NewAuthorPost($post));

        Toastr::success('Post Successfully Saved', 'Posted');
        return redirect()->route('author.post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if($post->user_id != Auth::id())
        {
            Toastr::error('You are not authorized to access this post :)','Error');
            return redirect()->back();
        }
        return view('author.post.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if($post->user_id != Auth::id())
        {
            Toastr::error('You are not authorized to access this post :)','Error');
            return redirect()->back();
        }
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit',compact('post','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request,[
            'title' => 'required',
            'image' => 'image',
            'body' => 'required',
            'categories' => 'required',
            'tags' => 'required'
        ]);

        $image = $request->file('image');
        $slug = preg_replace('/\s+/u', '-', trim($request->title));

        if(isset($image)){
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension(); //laravel-2019-10-01-45n44.png

            //check posts dir is exists
            if(!Storage::disk('public')->exists('posts'))
            {
                Storage::disk('public')->makeDirectory('posts');
            }
            //delete old image
            if(Storage::disk('public')->exists('posts/'.$post->image))
            {
                Storage::disk('public')->delete('posts/'.$post->image);
            }
            //resize image for post and upload
            $postImage = Image::make($image)->resize(1600,1066)->save();
            Storage::disk('public')->put('posts/'.$imageName,$postImage);
        }
        else{
            $imageName = $post->image;
        }

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug = $slug;
        $post->image = $imageName;
        $post->body = $request->body;
        if(isset($request->status))
        {
            $post->status = true;
        }else{
            $post->status = false;
        }
        //admin dont need to approval 
        $post->is_approved = false;
        $post->save();
        //many to many relation
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post Successfully Updaed', 'Update');
        return redirect()->route('author.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != Auth::id())
        {
            Toastr::error('You are not authorized to access this post :)','Error');
            return redirect()->back();
        }
        if(Storage::disk('public')->exists('posts/'.$post->image))
        {
            Storage::disk('public')->delete('posts/'.$post->image);
        }
        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Toastr::success('Post Successfully Deleted','DeletePost');
        return redirect()->route('author.post.index');
    }
}
