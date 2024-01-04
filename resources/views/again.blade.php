@extends('master')
@section('content')
<!-- Button trigger modal -->
  <!-- Modal -->
  <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalLabel">Add New Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="javascript:void(0)" id="categoryForm" method="POST">
                <input type="hidden" name="id" id="id">
                Create new Category: <input type="text" name="category" id="category"> <input type="submit" id="btn-save" value="CREATE">
            </form>
        </div>
      </div>
    </div>
  </div>


<table id="categoryTable" class="table table-border">
    <thead>
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script type="text/javascript">
$(document).ready(function(){
        $.ajaxSetup({
         headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        $('#categoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax : "{{url('again')}}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name:'title'},
                {data:'created_at', name:'created_at'},
                {data: 'action', name: 'action', orderable: false}
            ],
            order: [[0,'desc']]
        });
    });
    $('#categoryForm').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ url('storeArea') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data)=>{
                console.log(data);
                $('#categoryForm').trigger('reset');
                var oTable2 = $('#categoryTable').dataTable();
                oTable2.fnDraw(false);
                $('#categoryModal').modal('hide');
                $('#btn-save').attr("disabled", false);
            },
            error: function(data){console.log("error");}
        });
    });

    function editFunc(id){
        $.ajax({
            type: "POST",
            url: "{{url('editCategory')}}",
            data: {id:id},
            dataType:"json",
            success: function(res){
                $('#categoryModal').modal('show');
                $('#id').val(res.id);
                $('#category').val(res.title);
            },
            error: function(){
                console.log("error");
            }
        });
    }

    function deleteFunc(id){
        if(confirm("Delete Record?")==true){
            var id=id;
            $.ajax({
                type: "POST",
                url: "{{url('deleteCategory')}}",
                data: {id:id},
                dataType: 'json',
                success: function(res){
                    console.log('success');
                    var oTable = $('#categoryTable').dataTable();
                    oTable.fnDraw(false);
                },
                error: function(){
                    console.log('error');
                }
            });
        }
    }
</script>
@endsection
