@extends('layouts.admin')

@section('title')
User
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Manage Data User</h3>
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
                <table class="table table-sm table-striped" id="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Status</th>
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
                        <label for="name">Name User</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm" required
                            placeholder="Name User">
                    </div>
                    <div class="form-group">
                        <label for="email">Email User</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm" required
                            placeholder="Email User">
                    </div>
                    <div class="form-group">
                        <label for="id_user_level">Name Level</label>
                        <select name="id_user_level" id="id_user_level" class="form-control form-control-sm select2"
                            required>
                            <option value=""></option>
                            @foreach ($userLevels as $userLevel)
                            <option value="{{ $userLevel->id }}">{{ $userLevel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Type</label>
                        <select name="status" id="status" class="form-control form-control-sm select2"
                            style="width: 100%" required>
                            <option value=""></option>
                            <option value="active">Active</option>
                            <option value="non_active">Non Active</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password User</label>
                        <input type="text" name="password" id="password" class="form-control form-control-sm" required
                            placeholder="Password User">
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

<div class="modal fade" id="modal-password">
    <div class="modal-dialog">
        <form id="form-password" method="post">
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
                        <label for="name">Name User</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm" disabled
                            placeholder="Name User">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="text" name="password" id="password" class="form-control form-control-sm" required
                            placeholder="Password User">
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
        const url_add = "{{ route('user.store') }}";
        const url_edit = "{{ route('user.update') }}";
        const url_delete = "";
        const url_password = "{{ route('user.update-password') }}";
        let selected_id = 0;
        const table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax : {
                url : "{{ route('user.data') }}",
                type : "GET",
                data : function(d){
                    d.type = $('select[name=select_type]').val();
                    d.id_parent = $('select[name=select_parent]').val();
                }
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
                    data : 'email'
                },
                {
                    data : 'user_level',
                    render : function(data, type, row){
                        if(data){
                            return data.name;
                        }else{
                            return ''
                        }
                    }
                },
                {
                    data : 'status'
                },
                {
                    data : 'id',
                    render : function(data, type, row){
                        const btn_edit = '<button type="button" class="dropdown-item bg-info btn-edit mr-md-2"><i class="fa fa-save"></i> Edit</button>';
                        const btn_delete = '<button type="button" class="dropdown-item bg-danger btn-delete"><i class="fa fa-trash"></i> Delete</button>';
                        const btn_password = '<button type="button" class="dropdown-item bg-warning btn-password"><i class="fa fa-key"></i> Change Password</button>';
                        const btn_action = `<div class="btn-group">
                            <button type="button" class="btn btn-default">Action</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                ${btn_edit}
                                ${btn_password}
                            </div>
                        </div>`;
                        return btn_action;
                    },
                    width : '150px',
                }
            ],
        });

        //Initialize Select2 Elements
        $('select[name=id_user_level]').select2({
            placeholder : 'Choose Level',
        })

        $('select[name=status]').select2({
            placeholder : 'Choose Status',
        })

        //triger click
        $('button.btn-add').on('click', function(){
            //set modal title
            $('#modal-form h4.modal-title').html('Add User');
            //clear id field
            $('#modal-form input[name=id]').val('');
            $('#modal-form select[name=type]').trigger('change');
            $('#modal-form input[name=password]').prop('disabled', false);
            //show modal
            $('#modal-form').modal('show');
        })

        $('#table tbody').on('click','button.btn-edit', function(){
            const data = table.row($(this).parents('tr')).data();
            //set modal title
            $('#modal-form h4.modal-title').html('Edit User');
            //set data
            $('#modal-form input[name=id]').val(data.id);
            $('#modal-form input[name=name]').val(data.name);
            $('#modal-form input[name=email]').val(data.email);
            $('#modal-form select[name=id_user_level]').val(data.id_user_level).change();
            $('#modal-form select[name=status]').val(data.status).change();
            $('#modal-form input[name=password]').prop('disabled', true);
            //show modal
            $('#modal-form').modal('show');
        })

        $('#table tbody').on('click','button.btn-password', function(){
            const data = table.row($(this).parents('tr')).data();
            //set modal title
            $('#modal-password h4.modal-title').html('Edit User Password');
            //set data
            $('#modal-password input[name=id]').val(data.id);
            $('#modal-password input[name=name]').val(data.name);
            //show modal
            $('#modal-password').modal('show');
        })
        
        //save function
        $('#form-password').on('submit', function(e){
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
                        url : url_password,
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
                            if(result.code == 200){
                                //show alert
                                Swal.fire({
                                    title : 'Success',
                                    html : result.message ?? 'Save data success',
                                    icon : 'success'
                                });
                                //clear field
                                $('#modal-password input[name=id]').val('');
                                $('#modal-password input[name=name]').val('');
                                //close modal
                                $('#modal-password').modal('hide');
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