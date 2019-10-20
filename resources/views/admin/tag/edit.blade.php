@extends('layouts.backend.app')

@section('title','Admin Dashboard Tag')

@push('css')

@endpush()

@section('content')

<section class="content">
        <div class="container-fluid">
            <!-- Vertical Layout | With Floating Label -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Edit Tag
                            </h2>
                        </div>
                        <div class="body">
                            <form action="{{ route('admin.tag.update', $tag->id)}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" id="name" class="form-control" name="name" value="{{ $tag->name }}">
                                        <label class="form-label">Tag Name</label>
                                    </div>
                                </div>
                                <br>
                                <a class="btn btn-danger waves-effect" href="{{ route('admin.tag.index') }}">BACK</a>
                                <button type="submit" class="btn btn-primary waves-effect">UPDATE</button>
                            </form>
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