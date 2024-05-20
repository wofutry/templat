@extends('layouts.admin')

@section('title')
Menu
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3">Manage Data Menu</h3>
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
            <div class="col-md-2">
                <div class="form-group">
                    <label for="select_type">Type</label>
                    <select name="select_type" id="select_type"
                        class="form-control form-control-sm select2 select-table">
                        <option value="">All</option>
                        <option value="parent">Parent</option>
                        <option value="child">Children</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 select-parent">
                <div class="form-group">
                    <label for="select_parent">Parent</label>
                    <select name="select_parent" id="select_parent"
                        class="form-control form-control-sm select2 select-table" style="width: 100%" required>
                        <option value=""></option>
                        @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12 table-responsive">
                <table class="table table-sm table-striped" id="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Type</th>
                            <th>Parent</th>
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
                        <label for="name">Name Menu</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm" required
                            placeholder="Name Menu">
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control form-control-sm" required
                            placeholder="Slug Menu">
                    </div>
                    <div class="form-group">
                        <label for="name">Type</label>
                        <select name="type" id="type" class="form-control form-control-sm select2" style="width: 100%"
                            required>
                            <option value=""></option>
                            <option value="parent">Parent</option>
                            <option value="child">Children</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_parent">Parent</label>
                        <select name="id_parent" id="id_parent" class="form-control form-control-sm select2"
                            style="width: 100%" required>
                            <option value=""></option>
                            @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" name="order" id="order" class="form-control form-control-sm" min="0"
                            required>
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

<div class="modal fade" id="modal-user">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="form-user" method="POST">
                            @csrf
                            <input type="hidden" name="id_menu">
                            <div class="form-group">
                                <label for="id_user_level">Name Level</label>
                                <select name="id_user_level" id="id_user_level"
                                    class="form-control form-control-sm select2" required>
                                    <option value=""></option>
                                    @foreach ($userLevels as $userLevel)
                                    <option value="{{ $userLevel->id }}">{{ $userLevel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary justify-content-end">Save</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 mt-md-3 table-responsive">
                        <table class="table table-sm" id="table-user" style="width: 100%">
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
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- End Modal --}}
@endsection

@push('js')
<script>
    $(document).ready(function(){
        const url_add = "{{ route('menu.store') }}";
        const url_edit = "{{ route('menu.update') }}";
        const url_delete = "{{ route('menu.destroy') }}";
        let selected_id = 0;
        const table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax : {
                url : "{{ route('menu.data') }}",
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
                    data : 'slug'
                },
                {
                    data : 'type'
                },
                {
                    data : 'parent',
                    render : function(data, type, row){
                        if(data){
                            return data.name;
                        }else{
                            return ''
                        }
                    }
                },
                {
                    data : 'id',
                    render : function(data, type, row){
                        const btn_edit = '<button type="button" class="dropdown-item bg-info btn-edit mr-md-2"><i class="fa fa-save"></i> Edit</button>';
                        const btn_delete = '<button type="button" class="dropdown-item bg-danger btn-delete"><i class="fa fa-trash"></i> Delete</button>';
                        const btn_user = '<button type="button" class="dropdown-item bg-success btn-user"><i class="fa fa-user"></i> Access</button>';
                        const btn_action = `<div class="btn-group">
                            <button type="button" class="btn btn-default">Action</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                ${btn_edit}
                                ${btn_delete}
                                ${btn_user}
                            </div>
                        </div>`;
                        return btn_action;
                    },
                    width : '150px',
                }
            ],
        });

        const table_user = $('#table-user').DataTable({
            processing: true,
            serverSide: true,
            ajax : {
                url : "{{ route('menu.data-user') }}",
                type : "GET",
                data : function(d){
                    d.id_menu = selected_id;
                }
            },
            lengthMenu: [ [10, 25, 50], [10, 25, 50] ],
            columns : [
                {
                    data : 'id_menu',
                },
                {
                    data : 'name'
                },
                {
                    data : 'id_menu',
                    render : function(data, type, row){
                        const btn_delete = '<button type="button" class="dropdown-item bg-danger btn-delete"><i class="fa fa-trash"></i> Delete</button>';
                        const btn_action = `<div class="btn-group">
                            <button type="button" class="btn btn-default">Action</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                            <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                ${btn_delete}
                            </div>
                        </div>`;
                        return btn_action;
                    },
                    width : '150px',
                }
            ],
        });

        //Initialize Select2 Elements
        $('select[name=select_type]').select2({
            placeholder : 'Choose Type',
            allowClear : true,
        })

        $('select[name=type]').select2({
            placeholder : 'Choose Type'
        })

        $('select[name=select_parent]').select2({
            placeholder : 'Choose Parent'
        })

        $('select[name=id_parent]').select2({
            placeholder : 'Choose Parent'
        })

        $('select[name=id_user_level]').select2({
            placeholder : 'Choose User Level'
        })

        $('select[name=type]').on('change', function(){
            const val = $(this).val();
            val=='child'?$('select[name=id_parent]').prop('disabled',false):$('select[name=id_parent]').prop('disabled',true);
        })

        $('select[name=select_type]').on('change', function(){
            const val = $(this).val();
            val=='child'?$('select[name=select_parent]').prop('disabled',false):$('select[name=select_parent]').prop('disabled',true);
        })

        $('.select-table').on('change', function(){
            table.ajax.reload();
        })

        //triger click
        $('button.btn-add').on('click', function(){
            //set modal title
            $('#modal-form h4.modal-title').html('Add Menu');
            //clear id field
            $('#modal-form input[name=id]').val('');
            $('#modal-form select[name=type]').trigger('change');
            //show modal
            $('#modal-form').modal('show');
        })

        $('#table tbody').on('click','button.btn-edit', function(){
            const data = table.row($(this).parents('tr')).data();
            //set modal title
            $('#modal-form h4.modal-title').html('Edit Menu');
            //set data
            $('#modal-form input[name=id]').val(data.id);
            $('#modal-form input[name=name]').val(data.name);
            $('#modal-form input[name=slug]').val(data.slug);
            $('#modal-form select[name=type]').val(data.type).change();
            $('#modal-form select[name=id_parent]').val(data.id_parent).change();
            $('#modal-form input[name=order]').val(data.order);
            //show modal
            $('#modal-form').modal('show');
        })

        $('#table tbody').on('click','button.btn-user', function(){
            const data = table.row($(this).parents('tr')).data();
            //set modal title
            $('#modal-user h4.modal-title').html('Set Menu Access');
            //set data
            selected_id = data.id;
            $('#modal-user input[name=id_menu]').val(selected_id);

            table_user.ajax.reload();
            //show modal
            $('#modal-user').modal('show');
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
                                location.reload();
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

        //delete trigger click
        $('#table tbody').on('click','button.btn-delete', function(){
            const data = table.row($(this).parents('tr')).data();
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this data.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                focusConfirm:true
            }).then((result)=>{
                if(result.value){
                    //submit data
                    $.ajax({
                        url : url_delete,
                        type : "POST",
                        data : {
                            _token : token,
                            id : data.id,
                        },
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
                                    html : result.message ?? 'Delete data success',
                                    icon : 'success'
                                });
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
        })

        //save user function
        $('#form-user').on('submit', function(e){
            e.preventDefault();
            //get form data
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
                        url : "{{ route('menu.storeUser') }}",
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
                                //refresh table
                                table_user.ajax.reload();
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

        $('#table-user tbody').on('click','button.btn-delete', function(){
            const data = table_user.row($(this).parents('tr')).data();
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this data.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                focusConfirm:true
            }).then((result)=>{
                if(result.value){
                    //submit data
                    $.ajax({
                        url : "{{ route('menu.destroyUser') }}",
                        type : "POST",
                        data : {
                            _token : token,
                            id_menu : data.id_menu,
                            id_user_level : data.id_user_level,
                        },
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
                                //refresh table
                                table_user.ajax.reload();
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