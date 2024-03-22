<form action="{{ route('category.update') }}" method="post" id="editForm">
    @csrf
    <div class="mb-3">
        <input type="hidden" name="id" value="{{ $data->id }}">
        <label for="exampleInputEmail1" class="form-label">Category Name</label>
        <input type="text" name="category_name" class="form-control @error('category_name') is-invalid

        @enderror" id="category_name" value="{{$data->category_name}}">
        @error('category_name')
           <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary float-right"> <span class="e_loading d-none">....</span> Update</button>
</form>
  {{--  edit form submit with ajax   --}}
<script>
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
