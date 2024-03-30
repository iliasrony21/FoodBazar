<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
<form action="{{ route('subcategory.update',$data->id) }}" method="post" id="editForm" enctype="multipart/form-data">
    @csrf
    {{--  <input type="hidden" name="id" value="{{ $data->id }}">  --}}
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Select Category</label>
        <select name="category_id" id="category_id" class="form-control" required>
            <option value="" selected>Select Category</option>
          @foreach ($categories as $cat)
           <option value="{{ $cat->id }}" {{ $data->category_id == $cat->id ? 'selected': ''}}>{{ $cat->category_name }}</option>
          @endforeach
        </select>
        @error('category_id')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Subcategory Name</label>
        <input type="text" name="subcategory_name" value="{{ $data->subcategory_name }}"
            class="form-control @error('subcategory_name') is-invalid @enderror"id="subcategory_name">
        @error('subcategory_name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Image</label>
        <input type="file" class="dropify" name="image">
        <input type="hidden" name="old_image" value="{{ $data->image }}" >
        @error('image')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" id="submitBtn" class="btn btn-primary submit_btn">
        <span class="loading d-none">...</span>
        Submit
    </button>
</form>
<script>
    $('.dropify').dropify();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

<script>
    {{--  //update form submit with ajax  --}}
  $('#editForm').submit(function(e){
    e.preventDefault();
    $('.e_loading').removeClass('d-none');
    let url = $(this).attr('action');
    let requestData = $(this).serialize();
    $.ajax({
        url:url,
        type:'post',
        async:false,
        data:requestData,
        success:function(data){
            toastr.success(data.message);
            $('#editForm')[0].reset();
            $('.e_loading').addClass('d-none');
            $('#editModal').modal('hide');
            table.api().ajax.reload();
        },
        error: function(xhr, status, error) {
            var errorMessage = xhr.responseJSON.message;
            toastr.error(errorMessage);
        }

    });
});
</script>

