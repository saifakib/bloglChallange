<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use App\Tag;
use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AuthorPostApproved;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriberInform;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.post.index',compact('posts'));
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
        return view('admin.post.create',compact('tags','categories'));
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
        //admin dont need to approval 
        $post->is_approved = true;
        $post->save();
        //many to many relation
        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        //Inform all subsciber
        $subscribers = Subscriber::all();
        foreach($subscribers as $subscriber)
        {
            Notification::route('mail',$subscriber->subscribeEmail)
                        ->notify(new SubscriberInform($post));
        }

        Toastr::success('Post Successfully Saved', 'Posted');
        return redirect()->route('admin.post.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.post.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit',compact('post','categories','tags'));
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
        $post->is_approved = true;
        $post->save();
        //many to many relation
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post Successfully Updaed', 'Update');
        return redirect()->route('admin.post.index');

    }

    //pending post route.
    public function pending()
    {
        $posts = Post::where('is_approved',false)->get();
        return view('admin.post.pending',compact('posts'));
    }
    //pending post update
    public function approval($id)
    {
        $post = Post::find($id);
        if($post->is_approved == false)
        {
            $post->is_approved = true;
            $post->save();
            //send notification that post user post is approved 
            // when send single user use notify
            $post->user->notify(new AuthorPostApproved($post));

            $subscribers = Subscriber::all();
            foreach ($subscribers as $subscriber)
            {
                Notification::route('mail',$subscriber->subscribeEmail)
                    ->notify(new SubscriberInform($post));
            }
            Toastr::success('Post SUccessfully Approved :)','Approved');
        }
        else{
            Toastr::success('This Post allready approved','Approved');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(Storage::disk('public')->exists('posts/'.$post->image))
        {
            Storage::disk('public')->delete('posts/'.$post->image);
        }
        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Toastr::success('Post Successfully Deleted','DeletePost');
        return redirect()->route('admin.post.index');
    }
}
