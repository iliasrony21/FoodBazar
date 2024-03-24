@extends('admin.admin_master')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
@section('admin')
    <div class="card content-header">
        <div class="container-fluid">
            <div class="row pt-3 d-flex justify-content-between bg-dark">
                <div class="col-sm-9">
                    <h4 class="m-0 text-white">Subcategory</h4>
                </div>
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('subcategory') }}">Subcategory</a></li>
                        <li class="breadcrumb-item active">All Subcategory</li>
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
                            <h3 class="card-title mb-0">Subcategory</h3>
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
                                            <th>Subcategory Name</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row" class="odd">

                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>SL</th>
                                            <th>Category Name</th>
                                            <th>Subcategory Name</th>
                                            <th>Image</th>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Subcategory</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('subcategory.store') }}" method="post" id="addForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="" selected>Select Category</option>
                              @foreach ($categories as $cat)
                               <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                              @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Subcategory Name</label>
                            <input type="text" name="subcategory_name"
                                class="form-control @error('subcategory_name') is-invalid @enderror"id="subcategory_name">
                            @error('subcategory_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Image</label>
                            <input type="file" class="dropify" name="image" >
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" id="submitBtn" class="btn btn-primary submit_btn">
                            <span class="loading d-none">...</span>
                            Submit
                        </button>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Subcategory</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editPart">

                </div>

            </div>
        </div>
    </div>

   // <!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
//<!-- DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<script>
    $('.dropify').dropify();
</script>
<script>

       // All Data for Category data table
        let table = $('.dataTable').dataTable({
            processing: true,
            serverSide: true,
            search: true,
            ajax: "{{ route('subcategory') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'category_id',
                    name: 'category_id'
                },
                {
                    data: 'subcategory_name',
                    name: 'subcategory_name'
                },
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'action',
                    name: 'action'
                },

            ]
        });

      //   add form submit with ajax

      $('#addForm').submit(function(e) {
        e.preventDefault();
        $('.loading').removeClass('d-none');
        let url = $(this).attr('action');
        let formData = new FormData(this); // Construct FormData object with the form data
        $('.submit_btn').prop('type', 'button');

        $.ajax({
            url: url,
            type: 'post',
            data: formData, // Pass FormData object as the data
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting contentType
            success: function(data) {
                console.log('success a gese', data);
                toastr.success(data.message);
                $('#addForm')[0].reset();
                $('.loading').addClass('d-none');
                $('#addModal').modal('hide');
                $('.submit_btn').prop('type', 'button');
                table.api().ajax.reload();
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON.message;
                console.log('error ki', error);
                toastr.error(errorMessage);
            }
        });
    });


        // edit modal show with ajax

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
 //  dropify

    </script>

@endsection
