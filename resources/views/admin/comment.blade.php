@extends('layouts.backend.app')

@section('title','Admin Dashboard Comments')

@push('css')

    <!-- JQuery DataTable Css -->
    <link href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
@endpush()

@section('content')
<section class="content">
        <div class="container-fluid">
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                        <div class="header">
                            <button style="font-size:20px"class="btn btn-success btn-lg btn-block waves-effect" type="button">ALL comments <span class="badge">{{ $comments->count()}}</span></button>                          
                        </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>Commenter</th>
                                            <th>Post Summery</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Commenter</th>
                                            <th>Post Summery</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($comments as $comment)
                                        <tr>
                                            <td>
                                            <div class="media">
                                                <div class="media-left">
                                                    <a target="_blank" href="{{ route('post.index',$comment->post->slug) }}">
                                                        <img class="media-object" width="64" height="64"
                                                        src="{{ Storage::disk('public')->url('profile/author/'.$comment->user->image)}}" ><!-- here i show author profile -->
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <h4 class="media-heading">{{ $comment->user->name}} 
                                                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                                                    </h4>
                                                    <p>{{ $comment->comment }}</p>
                                                </div>
                                            </div>
                                            </td>
                                            <td>
                                            <div class="media">
                                            <div class="media-left">
                                            <a target="_blank" href="{{ route('post.index',$comment->post->slug) }}">
                                                        <img class="media-object" width="64" height="64"
                                                        src="{{ Storage::disk('public')->url('posts/'.$comment->post->image)}}" ><!-- here i show author profile -->
                                                    </a>
                                            </div>
                                            <div class="media-body">
                                            <h4 class="media-heading">{{ $comment->post->title }}</h4>
                                            <small>by {{ $comment->post->user->name }}</small>
                                            </div>
                                            </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class='btn btn-danger waves-effect'
                                                    onclick="deletecomment({{$comment->id}})">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                                <form id="delete-comment-{{ $comment->id }}"
                                                 action="{{ route('admin.comment.destroy',$comment->id )}}"
                                                 method="POST" style="display:none">
                                                 @csrf
                                                 @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Exportable Table -->
        </div>
</section>
@endsection()

@push('js')

    <!-- Jquery DataTable Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('assets/backend/js/pages/tables/jquery-datatable.js') }}"></script>

    <!-- Sweet alert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script type="text/javascript"> 
        function deletecomment(id)
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
                text: "You went to remove this comment!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-comment-'+id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your comment is safe :)',
                'error'
                )
            }
            })
        }
    </script>
@endpush()
