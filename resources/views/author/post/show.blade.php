@extends('layouts.backend.app')

@section('title','Author Post')

@push('css')

@endpush()

@section('content')

<section class="content">
        <div class="container-fluid">
            <a href="{{route('author.post.index')}}" class="btn btn-danger waves-effect">BACK</a>
            <a href="{{route('author.post.edit', $post->id)}}" class="btn btn-info waves-effect">EDIT</a> 
            @if($post->is_approved == false)
                <button type="button" class="btn btn-success pull-right">
                    <i class="material-icons">done</i>
                    <span>Approve</span>
                </button>
            @else
                <button type="button" class="btn btn-success pull-right" disable>
                    <i class="material-icons">verified_user</i>
                    <span>Approved</span>
                </button>
            @endif
            <br>
            <br>
            <!-- Vertical Layout | With Floating Label -->
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    {{ $post->title}}
                                </h2>
                                <small>Posted By <strong><a href="">{{ $post->user->name }}</a></strong> on {{ $post->created_at->toFormattedDateString() }}</small>
                            </div>
                            <div class="body">
                                <!-- decode -->
                                {!! $post->body !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="card">
                            <div class="header bg-cyan">
                                <h2>
                                    Categories
                                </h2>
                            </div>
                            <div class="body">
                            @foreach($post->categories as $category)
                                <strong><span class="lavel bg-cyan">{{ $category->name }}</span></strong>
                            @endforeach
                            </div>
                        </div>
                        <div class="card">
                            <div class="header bg-green">
                                <h2>
                                    Tags
                                </h2>
                            </div>
                            <div class="body">
                            @foreach($post->tags as $tag)
                                <strong><span class="lavel bg-green">{{ $category->name }}</span></strong>
                            @endforeach
                            </div>
                        </div>
                        <div class="card">
                            <div class="header bg-amber">
                                <h2>
                                    Featured Image
                                </h2>
                            </div>
                            <div class="body">
                                <img class="img-responsive thumbnail" src="{{ Storage::disk('public')->url('posts/'.$post->image)}}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Vertical Layout | With Floating Label -->
        </div>
    </section>

@endsection

@push('js')

@endpush()
