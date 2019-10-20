@extends('layouts.backend.app')

@section('title','author Post Update')

@push('css')

    <!-- Bootstrap Select Css -->
    <link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

@endpush()

@section('content')

<section class="content">
        <div class="container-fluid">
            <!-- Vertical Layout | With Floating Label -->
            <form action="{{ route('author.post.update',$post->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    Update Post
                                </h2>
                            </div>
                            <div class="body">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" id="title" class="form-control" name="title" value="{{ $post->title }}">
                                        <label class="form-label">Post Title</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    
                                        <label for="image">Featured Image</label>
                                        <input type="file"name="image">
                                
                                </div>
                                <div class="form-group form-float">
                                    
                                    <input type="checkbox" id="publish" name="status" class="filled-in chk-col-light-green" value='1' 
                                    {{ $post->status == true ? 'checked' : '' }}/>
                                    <label for="publish">PUBLISH</label>
                            
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    Categories and Tags
                                </h2>
                            </div>
                            <div class="body">
                                <div class="form-group form-float">
                                    <div class="form-line {{ $errors->has('categories') ? 'focused error' : ''}}">
                                        <label for="category">Select Category</label>
                                        <select name="categories[]" id="category" class="form-control
                                         show-tick" data-live-search="true" multiple>
                                            @foreach($categories as $category)
                                                <option
                                                    @foreach($post->categories as $postedCategory)
                                                        {{ $postedCategory->id == $category->id ? 'selected' : ''}}
                                                    @endforeach
                                                    value="{{ $category->id }}">{{ $category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line {{ $errors->has('tags') ? 'focused error' : ''}}">
                                        <label for="tag">Select Tag</label>
                                        <select name="tags[]" id="tag" class="form-control
                                         show-tick" data-live-search="true" multiple>
                                            @foreach($tags as $tag)
                                                <option
                                                    @foreach($post->tags as $postedTag)
                                                        {{ $postedTag->id == $tag->id ? 'selected' : ''}}
                                                    @endforeach
                                                    value="{{ $tag->id }}">{{ $tag->name}}
                                                 </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <a class="btn btn-danger waves-effect" href="{{ route('author.post.index') }}">BACK</a>
                                <button type="submit" class="btn btn-primary waves-effect">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    Body
                                </h2>
                            </div>
                            <div class="body">
                            <form method="post">
                                <textarea id="body" name="body">{{ $post->body }}</textarea>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Vertical Layout | With Floating Label -->
        </div>
    </section>

@endsection

@push('js')

    <!-- Select Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <!-- Tinymce js latest version -->
    <script src="{{ asset('assets/backend/plugins/tinymce/tinymce.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#body',
            height: 400,
        });
  </script>

@endpush()
