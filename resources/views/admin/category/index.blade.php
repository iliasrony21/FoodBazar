@extends('admin.admin_master')
@section('admin')
    <div class="card content-header">
        <div class="container-fluid">
            <div class="row pt-3 d-flex justify-content-between ">
                <div class="col-sm-10">
                    <h4 class="m-0 text-dark">Category</h4>
                </div>
                <div class="col-sm-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('category') }}">Category</a></li>
                        <li class="breadcrumb-item active">All Category</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Category</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                <i class="fas fa-plus"></i> Add New
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="example2_wrapper" class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Category Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>

                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>SL</th>
                                            <th>Category Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <form id="deleteForm" action="" method="POST">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        </div>
        </div>
    </section>
    {{--  <!-- Add Category Modal -->  --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('category.store') }}" method="post" id="addForm">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Category Name</label>
                            <input type="text" name="category_name"
                                class="form-control @error('category_name') is-invalid

                            @enderror"
                                id="category_name">
                            @error('category_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary float-right"> <span class="loading d-none">....</span>
                            Submit</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{--  <!-- Edit Modal -->  --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Category</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editPart">

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        let table = $('.dataTable').dataTable({
            processing: true,
            serverSide: true,
            search: true,
            ajax: "{{ route('category') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'category_name',
                    name: 'category_name'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ]
        });

        {{--  add form submit with ajax   --}}

        $('#addForm').submit(function(e) {
            e.preventDefault();
            $('.loading').removeClass('d-none');
            let url = $(this).attr('action');
            let requestData = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                async: false,
                data: requestData,
                success: function(data) {
                    toastr.success(data.message);
                    $('#addForm')[0].reset();
                    $('.loading').addClass('d-none');
                    $('#addModal').modal('hide');
                    table.api().ajax.reload();
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON.message;
                    toastr.error(errorMessage);
                }

            });
        });

        {{--  edit modal show with ajax   --}}

        $('body').on('click', '.edit', function() {
            let id = $(this).data('id');
            let url = "{{ url('admin/category/edit') }}/" + id;
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#editPart').html(data);

                }
            });
        });

        $(document).ready(function() {
            $(document).on('click', '#deleteCat', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteForm').attr('action', url).submit();
                    }
                });
            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();
                let url = $(this).attr('action');
                let request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        console.log('Delete response:', data);
                        toastr.success(data.message); // Assuming you're using toastr for notifications
                        // Reset form and reload table
                        $('#deleteForm')[0].reset();
                        table.api().ajax.reload();
                    },
                    error: function(xhr, status, error) {
                        toastr.error("An error occurred while deleting the category.");
                    }
                });
            });
        });

    </script>
@endsection
