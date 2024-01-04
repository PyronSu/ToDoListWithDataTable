@extends('master')
@section('content')

    <button type="button" class="btn btn-primary mt-2" onclick="add()" data-bs-toggle="modal" data-bs-target="#todo-modal">Create</button>

    <!-- Modal -->
    <div class="modal fade" id="todo-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Task</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="todoForm" method="POST" name="TaskForm" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                      <label for="" class="form-label">Add a Task </label>
                      <input type="text" class="form-control" id="task" name="task" aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary float-end" id="btn-save">Submit</button>
                  </form>
            </div>
            {{-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
        </div>
        </div>
    </div>
    <table class="table" id="ajax-datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>Tasks</th>
            <th>Created_at</th>
            <th>Actions</th>
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

        $('#ajax-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{url('index')}}",
            columns:[
                {data: 'id', name: 'id'},
                {data: 'task',name: 'task'},
                {data: 'created_at',
                    name: 'created_at',
                     render: function(data, type, full, meta){
                         return moment(data).format('DD/MM/YYYY');
                     }
                },
                {data: 'action',name: 'action',orderable:false},
            ],
            order:[[0,'desc']]
        });
    });
$('#todoForm').submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type:'POST',
        url: "{{ url('store') }}",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            console.log("Success" + data);
            $('#todo-modal').modal('hide');
            var oTable = $('#ajax-datatable').dataTable();
            oTable.fnDraw(false);
            //to test whether moment.js work or not
            console.log("Success Again"+moment());
            $('#btn-save').attr("disabled", false);
        },
    error: function(data){console.log("error");}
});
});

function add(){
$('#todoForm').trigger("reset");
$('#id').val('');
}

function editFunc(id){
$.ajax({
    type: "POST",
    url: "{{url('edit')}}",
    data: {id:id},
    dataType: "json",
    success: function(res){
        console.log(res);
        $('#todo-modal').modal('show');
        $('#id').val(res.id);
        $('#task').val(res.task);
    },
    error: function(){
        console.log("error");
    }
});
}

function deleteFunc(id){
if(confirm("Delete Task?") == true){
    var id = id;
    $.ajax({
        type: "POST",
        url: "{{url('delete')}}",
        data: {id:id},
        dataType: 'json',
        success: function(res){
            var oTable = $("#ajax-datatable").dataTable();
            oTable.fnDraw(false);
        }
    });
}
}
</script>
@endsection
