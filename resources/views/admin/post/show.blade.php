@extends('layouts.backend.app')

@section('title','Admin Post')

@push('css')

@endpush()

@section('content')

<section class="content">
        <div class="container-fluid">
            <a href="{{route('admin.post.index')}}" class="btn btn-danger waves-effect">BACK</a>
            <a href="{{route('admin.post.edit', $post->id)}}" class="btn btn-info waves-effect">EDIT</a> 
            @if($post->is_approved == false)
                <button type="button" class="btn btn-success waves-effect pull-right"
                onclick="approvePost()">
                    <i class="material-icons">done</i>
                    <span>Approve</span>
                </button>
                <form id="approve-form" action="{{ route('admin.post.approve', $post->id )}}"method="POST">
                    @csrf
                    @method('PUT')
                </form>
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
    <!-- Sweet alert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script type="text/javascript">
        function approvePost()
        {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You went to approve this post!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('approve-form').submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'This post remain pending :)',
                'Info'
                )
            }
            })
        }
    </script>
@endpush()
