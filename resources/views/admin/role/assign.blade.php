@extends('layouts.admin.app', ['title' => 'Assign Permission'])

@section('breadcrumb')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.role.index') }}">Role</a>
        </div>
        <div class="breadcrumb-item">Assign</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-warning">
                <form action="{{ route('admin.role.assign-save', $model->id) }}" method="post" id="form-data" class="needs-validation" novalidate="">
                    @csrf

                    <div class="card-header">
                        <h4>Assign Permission List</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.role.index') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $model->name }}" disabled="">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Guard Name</label>
                            <input type="text" name="guard_name" class="form-control @error('guard_name') is-invalid @enderror" value="{{ $model->guard_name }}" disabled="">
                            @error('guard_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Permissions</label>
                            <select name="permissions[]" id="permissions" class="form-control select2 @error('permissions') is-invalid @enderror" multiple="">
                                @foreach ($permissions as $row)
                                    <option value="{{ $row->name }}" {{ in_array($row->name, $permissionOfRole) ? 'selected' : '' }}>
                                        {{ $row->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('permissions')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-warning">Save</button>
                        <button type="reset" class="btn btn-light">Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script>
    'use strict';

    $("#form-data").submit(function(e) {
        e.preventDefault();
        LoadingShow();
        //define variable
        var formdata = $('#form-data').serialize();
        var formAction = $('#form-data').attr('action');

        $.ajax({
            url: formAction,
            type: "POST",
            data: formdata,
            datatype: "json",
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(response) {
                LoadingHide();

                if(response.status){
                    iziToast.success({
                        title: "Success!",
                        message: response.message,
                        position: "topRight",
                        onClosing: function () {
                            window.location.href = "{{ route('admin.role.index') }}";
                        }
                    });
                }
                else{
                    iziToast.error({
                        title: "Error!",
                        message: response.message,
                        position: "topRight"
                    });
                }
            },
            error: function(error) {
                LoadingHide();

                iziToast.error({
                    title: "Error!",
                    message: error.responseJSON.message,
                    position: "topRight"
                });
            }
        });
    });

</script>
@endpush
