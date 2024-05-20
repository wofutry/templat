@extends('layouts.admin')

@section('title')
Menu
@endsection

@section('content')
<div id="main-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manage Data Menu</h3>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card card-outline card-success">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-sm table-striped" id="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Rhona Carey</td>
                                        <td>Northern Mariana Islands</td>
                                        <td>2020-03-12 03:47:43</td>
                                        <td>9761</td>
                                    </tr>
                                    <tr>
                                        <td>Ria Sherman</td>
                                        <td>Denmark</td>
                                        <td>2019-07-16 18:36:14</td>
                                        <td>7472</td>
                                    </tr>
                                    <tr>
                                        <td>Hoyt Delgado</td>
                                        <td>Tajikistan</td>
                                        <td>2019-11-26 15:47:50</td>
                                        <td>7470</td>
                                    </tr>
                                    <tr>
                                        <td>Bernard Murray</td>
                                        <td>Sint Maarten</td>
                                        <td>2019-09-25 21:14:41</td>
                                        <td>2793</td>
                                    </tr>
                                    <tr>
                                        <td>Arden House</td>
                                        <td>Armenia</td>
                                        <td>2019-01-08 09:02:02</td>
                                        <td>9055</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('components.footer')
</div>
@endsection

@push('js')
<script>
    let dataTable = new simpleDatatables.DataTable(
        document.getElementById("table"),
        {
            serverSide: true,
            deferLoading: 1000,
            ajax: 'https://www.haegi.org/jstable/data.php',
            ajaxParams: {
                foo: 'bar'
            }
        }
    )

    // Patch "per page dropdown" and pagination after table rendered
    dataTable.on("datatable.init", function () {
        adaptPageDropdown()
        adaptPagination()
    })

    // Re-patch pagination after the page was changed
    dataTable.on("datatable.page", adaptPagination)

</script>
@endpush