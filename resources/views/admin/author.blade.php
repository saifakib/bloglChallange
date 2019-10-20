@extends('layouts.backend.app')

@section('title','Admin Dashboard Author')

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
                            <button style="font-size:20px"class="btn btn-success btn-lg btn-block waves-effect" type="button">All Authors <span class="badge">{{ $authors->count()}}</span></button>                          
                        </div>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Author</th>
                                            <th class="text-center">Posts</th>
                                            <th class="text-center"><i class="material-icons">favorite</i></th>
                                            <th class="text-center"><i class="material-icons">comment</i></th>
                                            <th class="text-center"><i class="material-icons">visibility</i></th>
                                            <th class="text-center">>Join</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Author</th>
                                            <th class="text-center">Posts</th>
                                            <th class="text-center"><i class="material-icons">favorite</i></th>
                                            <th class="text-center"><i class="material-icons">comment</i></th>
                                            <th class="text-center"><i class="material-icons">visibility</i></th>
                                            <th class="text-center">>Join</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($authors as $key=>$author)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $author->name }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn bg-success btn-circle">
                                                    <span><b>{{ $author->posts_count }}</b></span>
                                                </button>
                                            </td>
                                            <td class="text-center"><span class="badge bg-red">{{ $author->favourite_posts_count }}</td>
                                            <td class="text-center"><span class="badge bg-green">{{ $author->comments_count }}</td>
                                            <td class="text-center"><span class="badge bg-blue">
                                            @foreach($author->posts as $post)
                                                <?php 
                                                $view_count += $post->view_count;
                                                ?>
                                            @endforeach()
                                            {{ $view_count }}
                                            </td>
                                            <td class="text-center">{{ $author->created_at->toDateString() }}</td>
                                            <td class="text-center">
                                                <button type="button" class='btn btn-danger waves-effect'
                                                    onclick="deleteauthor({{$author->id}})">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                                <form id="delete-author-{{ $author->id }}"
                                                 action="{{ route('admin.author.destroy',$author->id )}}"
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
        function deleteauthor(id)
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
                text: "You went to remove this Author!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-author-'+id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your Author is safe :)',
                'error'
                )
            }
            })
        }
    </script>
@endpush()
