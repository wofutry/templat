@extends('layouts.admin')

@section('title')
User Level
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Manage Data User Level</h3>
        <div class="row ml-auto p-2">
            <div class="col-md-12">
                @if (Auth::user()->id_user_level == 1)
                <button type="button" class="btn btn-secondary btn-sm btn-add">
                    <i class="fa fa-plus"></i> Add Data
                </button>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-striped" style="width: 100%" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="modal-form">
    <div class="modal-dialog">
        <form id="form" method="post">
            @csrf
            <input type="hidden" name="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name Level</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm" required
                            placeholder="Name Level">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- End Modal --}}
@endsection

@push('js')
<script>
    $(document).ready(function(){
        const url_add = "{{ route('user-level.store') }}";
        const url_edit = "{{ route('user-level.update') }}";
        const table = $('#table').DataTable({
            // processing: true,
            serverSide: true,
            ajax : {
                url : "{{ route('user-level.data') }}",
                type : "GET",
            },
            lengthMenu: [ [10, 25, 50], [10, 25, 50] ],
            columns : [
                {
                    data : 'id',
                },
                {
                    data : 'name'
                },
                {
                    data : 'id',
                    render : function(data, type, row){
                        const btn = '<button type="button" class="btn btn-primary btn-sm btn-edit">Edit</button>';
                        return btn;
                    }
                }
            ],
        });

        //add triger click
        $('button.btn-add').on('click', function(){
            //set modal title
            $('#modal-form h4.modal-title').html('Add User Level');
            //clear id field
            $('#modal-form input[name=id]').val('');
            //show modal
            $('#modal-form').modal('show');
        })

        //update trigger click
        $('#table tbody').on('click','button.btn-edit', function(){
            const data = table.row($(this).parents('tr')).data();
            //set modal title
            $('#modal-form h4.modal-title').html('Edit User Level');
            //set data
            $('#modal-form input[name=id]').val(data.id);
            $('#modal-form input[name=name]').val(data.name);
            //show modal
            $('#modal-form').modal('show');
        });

        //save function
        $('#form').on('submit', function(e){
            e.preventDefault();
            //get form data
            const id = $('#form input[name=id]').val();
            const formData = new FormData($(this)[0]);
            //prompt
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to save this data.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                focusConfirm:true
            }).then((result)=>{
                if(result.value){
                    //submit data
                    $.ajax({
                        url : id==''?url_add:url_edit,
                        type : "POST",
                        data : formData,
                        contentType: false,
                        processData: false,
                        dataType : 'JSON',
                        beforeSend : function(){
                            Swal.fire({
                                title: 'Please Wait',
                                text: 'Fetching data...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                        },
                        success : function(result){
                            Swal.close();
                            if(result.code == 200){
                                //show alert
                                Swal.fire({
                                    title : 'Success',
                                    html : result.message ?? 'Save data success',
                                    icon : 'success'
                                });
                                //clear field
                                $('#modal-form input[name=id]').val('');
                                $('#modal-form input[name=name]').val('');
                                //close modal
                                $('#modal-form').modal('hide');
                                //refresh table
                                table.ajax.reload();
                            }else{
                                Swal.fire({
                                    title : 'Error',
                                    html : result.message??'Save data failed',
                                    icon : 'error'
                                });
                            }
                        },
                        error : function(xhr){
                            Swal.close();
                            const error = xhr.responseJSON;
                            const message = error == undefined ? 'Save data failed' : error.message;
                            Swal.fire({
                                title : 'Error',
                                html : message,
                                icon : 'error'
                            });
                        }
                    })
                }
            })
        });
    })
</script>
@endpush