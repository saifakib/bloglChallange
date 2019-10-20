<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscriber;
use Brian2694\Toastr\Facades\Toastr;
class SubscriberController extends Controller
{
    public function store(Request $request){
        $this->validate($request,[
            'subscribeEmail' => 'required|email|unique:subscribers', //working unique by using this subscribeEmail name from
                                                                        // subscribers table. if replace this name , dont work
        ]);

        $subscriber = new Subscriber();
        $subscriber->subscribeEmail = $request->subscribeEmail;
        $subscriber->save();

        Toastr::success('You are Successfully added to our Subscriber list','Subscribe');
        return redirect()->back();
    }
}
