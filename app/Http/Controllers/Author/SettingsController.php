<?php

namespace App\Http\Controllers\Author;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index(Request $request){
        $user = Auth::User();
        return view('author.setting',compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'name' => 'required',
        ]);

        $image = $request->file('image');
        $username = $request->username;
        $user = User::findOrFail(Auth::id());

        if(isset($image)){
            //make unique name for profile image
            $currentDate = Carbon::now()->toDateString();
            $imageName = $username.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension(); //saif-2019-10-01-45n44.png

            //check profile dir is exists
            if(!Storage::disk('public')->exists('profile/author'))
            {
                Storage::disk('public')->makeDirectory('profile/author');
            }
            //delete old image
            if(Storage::disk('public')->exists('profile/author/'.$user->image))
            {
                Storage::disk('public')->delete('profile/author/'.$user->image);
            }
            //resize image for category and upload
            $profileAuthor = Image::make($image)->resize(500,480)->save();
            Storage::disk('public')->put('profile/author/'.$imageName,$profileAuthor);
        }
        else{
            $imageName = $user->image;
        }

        $user->name = $request->name;
        $user->username = $username;
        $user->email = $request->email;
        $user->about = $request->about;
        $user->image = $imageName;

        $user->save();
        Toastr::success('Profile Successfully Updated','Profile Update');
        return redirect()->back();
    }

    public function updatePassword(Request $request){
        $this->validate($request,[
            'OldPassword' => 'required',
            'password' => 'required|confirmed'
        ]);
        $hashPassword = Auth::user()->password;
        if(Hash::check($request->OldPassword,$hashPassword))
        {
            if(Hash::check($request->password,$hashPassword))
            {
                Toastr::error('New password can not be the same as old password','Error');
                return redirect()->back();
            }
            else{
                $user = User::find(Auth::id());
                $user->password = Hash::make($request->password);
                $user->save();
                Toastr::error('Password Successfully Changed','Password Updated');
                Auth::logout();
                return redirect()->back();
            }
        }
        else{
            Toastr::error('Current password not match','Incorrect Password');
            return redirect()->back();
        }
        
    }
}
