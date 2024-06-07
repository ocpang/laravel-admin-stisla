@extends('layouts.admin.app', ['title' => 'Create Permission'])

@section('breadcrumb')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.permission.index') }}">Permission</a>
        </div>
        <div class="breadcrumb-item">Create</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <form action="{{ route('admin.permission.store') }}" method="post" id="form-data" class="needs-validation" novalidate="">
                    @csrf

                    <div class="card-header">
                        <h4>Create</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.permission.index') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required="">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Guard Name</label>
                            <input type="text" name="guard_name" class="form-control @error('guard_name') is-invalid @enderror" value="{{ old('guard_name') }}" required="">
                            @error('guard_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <button type="reset" class="btn btn-light">Clear</button>
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
                            window.location.href = "{{ route('admin.permission.index') }}";
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
