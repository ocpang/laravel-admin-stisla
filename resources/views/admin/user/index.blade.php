@extends('layouts.admin.app', ['title' => 'Users'])

@section('breadcrumb')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.user.index') }}">User</a>
        </div>
        <div class="breadcrumb-item">List</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>User List</h4>
                    <div class="card-header-action">
                        @if(auth()->user()->can('user.create'))
                            <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-data">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="min-w-100px">Name</th>
                                    <th class="min-w-100px">Email</th>
                                    <th>Role</th>
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script>
    'use strict';

    jQuery(document).ready(function () {
        $('#table-data').DataTable({
            destroy: true,
        }).destroy();

        var dataTable = $('#table-data').DataTable({
            processing: true,
            serverSide: true,
            scrollX: false,
            ajax: "{!! url()->current() !!}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                { data: 'name', name: 'name'},
                { data: 'email', name: 'email'},
                { data: 'role', name: 'role'},
                { data: 'created_at', name: 'created_at'},
                { data: 'updated_at', name: 'updated_at'},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [4, "desc"],
            columnDefs: [
                {
                    targets: [0, 6],
                    className: 'text-center'
                },

            ],
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                    .on('change', function () {
                        column.search($(this).val(), false, false, true).draw();
                    });
                });
            }
        });
    });

    // Sweet alert Prompt
    function deletePrompt(id) {
        swal({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this data!',
            icon: 'warning',
            dangerMode: true,
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "btn btn-light",
                    closeModal: true,
                },
                confirm: {
                    text: "Yes",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                }
            }
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'DELETE',
                    url: "{!! url()->current() !!}/"+id,
                    headers: { 'Access-Control-Allow-Origin': '*', 'X-CSRF-TOKEN': token },
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status){
                            iziToast.success({
                                title: "Success!",
                                message: response.message,
                                position: "topRight"
                            });
                        }
                        else{
                            iziToast.error({
                                title: "Error!",
                                message: response.message,
                                position: "topRight"
                            });
                        }

                        $('#table-data').DataTable().ajax.reload();
                    },
                    error: function (response) {
                        iziToast.error({
                            title: "Error!",
                            message: error.responseJSON.message,
                            position: "topRight"
                        });
                    },
                });
            }
        });

    }

</script>
@endpush
