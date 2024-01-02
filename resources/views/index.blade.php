<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    {{-- jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- cloud tables --}}
    <script src="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    {{-- moment.js to play with dates and time --}}
    <!-- Add this in your HTML file, either via CDN or a local file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <title>Document</title>
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
          <span class="navbar-brand mb-0 h1 ms-5">To-do List </span>
        </div>
    </nav>
    <div class="container">
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
    </div>
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
    </script>
</body>
</html>
