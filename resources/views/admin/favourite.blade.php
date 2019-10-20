@extends('layouts.backend.app')

@section('title','Admin Dashboard posts')

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
                            <button style="font-size:20px"class="btn btn-success btn-lg btn-block waves-effect" type="button">All Favourite Posts <span class="badge">{{ $posts->count()}}</span></button>                          
                        </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th class="text-center"><i class="material-icons">favorite</i></th>
                                            <th class="text-center"><i class="material-icons">comment</i></th>
                                            <th class="text-center"><i class="material-icons">visibility</i></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th class="text-center"><i class="material-icons">favorite</i></th>
                                            <th class="text-center"><i class="material-icons">comment</i></th>
                                            <th class="text-center"><i class="material-icons">visibility</i></th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($posts as $key=>$post)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $post->title }}</td>
                                            <td>{{ $post->user->name }}</td>
                                            <td class="text-center"><span class="badge bg-red">{{ $post->favourite_to_users->count() }}</td>
                                            <td class="text-center"><span class="badge bg-green">0</td>
                                            <td class="text-center"><span class="badge bg-blue">{{ $post->view_count }}</td>
                                            <td class="text-center">
                                                <a href="" 
                                                    class="btn btn-info waves-effect">
                                                    <i class="material-icons">visibility</i>
                                                </a> 
                                                <button type="button" class='btn btn-danger waves-effect'
                                                    onclick="deletepost({{$post->id}})">
                                                    <i class="material-icons">remove</i>
                                                </button>
                                                <form id="delete-fav-post-{{ $post->id }}"
                                                 action="{{ route('admin.favourite.destroy',$post->id )}}"
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
        function deletepost(id)
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
                text: "You went to remove this post your favourite list!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-fav-post-'+id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your favourite post is safe :)',
                'error'
                )
            }
            })
        }
    </script>
@endpush()
