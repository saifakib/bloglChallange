<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorys = Category::latest()->get();
        return view('admin.category.index',compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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
            'name' => 'required|unique:categories',
            'image' => 'required|mimes:jpeg,bmp,png,jpg,JPG',
        ]);

        $image = $request->file('image');
        $slug = preg_replace('/\s+/u', '-', trim($request->name));

        if(isset($image)){
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension(); //laravel-2019-10-01-45n44.png

            //check category dir is exists
            if(!Storage::disk('public')->exists('category'))
            {
                Storage::disk('public')->makeDirectory('category');
            }
            //resize image for category and upload
            $category = Image::make($image)->resize(1600,479)->save();
            Storage::disk('public')->put('category/'.$imageName,$category);


            //check category dir is exists
            if(!Storage::disk('public')->exists('category/slider'))
            {
                Storage::disk('public')->makeDirectory('category/slider');
            }
            //resize image for category slider and upload
            $categorySlider = Image::make($image)->resize(500,333)->save();
            Storage::disk('public')->put('category/slider/'.$imageName,$categorySlider);
        }
        else{
            $imageName = "default.png";
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();

        Toastr::success('Category Added Successfully','Add');
        return redirect()->route('admin.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'image' => 'mimes:jpeg,bmp,png,jpg,JPG',
        ]);

        $image = $request->file('image');
        $slug = preg_replace('/\s+/u', '-', trim($request->name));
        $category = Category::find($id);
        
        if($image)
        {
            //make unique name for image
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();
            //check category dir is exists
            if(!Storage::disk('public')->exists('category'))
            {
                Storage::disk('public')->makeDirectory('category');
            }
            //delete old image
            if(Storage::disk('public')->exists('category/'.$category->image))
            {
                Storage::disk('public')->delete('category/'.$category->image);
            }
            //resize image for category and upload
            $categoryImage = Image::make($image)->resize(1600,479)->save();
            Storage::disk('public')->put('category/'.$imageName,$categoryImage);



            //check category dir is exists
            if(!Storage::disk('public')->exists('category/slider'))
            {
                Storage::disk('public')->makeDirectory('category/slider');
            }
            //delete old image
            if(Storage::disk('public')->exists('category/slider/'.$category->image))
            {
                Storage::disk('public')->delete('category/slider/'.$category->image);
            }
            //resize image for category slider and upload
            $categoryImage = Image::make($image)->resize(500,333)->save();
            Storage::disk('public')->put('category/slider/'.$imageName,$categoryImage);


        }
        else{
            $imageName = $category->image;
        }

        $category->name = $request->name;
        $category->slug = $slug;
        $category->image = $imageName;
        $category->save();

        Toastr::success('Category Update Successfully','Update');
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        //delete image from category
        if(Storage::disk('public')->exitst('category/'.$category->image))
        {
            Storage::disk('public')->delete('category/'.$category->image);
        }
        //delete image from category slider
        if(Storage::disk('public')->exitst('category/slider/'.$category->image))
        {
            Storage::disk('public')->delete('category/slider'.$category->image);
        }
        $category->delete();
        Toastr::success('Category Delete Successfully:)','Delete');
        return redirect()->route('admin.category.index');
    }
}
