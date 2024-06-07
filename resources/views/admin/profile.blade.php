@extends('layouts.admin.app', ['title' => 'Profile'])

@section('breadcrumb')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
        <div class="breadcrumb-item">Profile</div>
    </div>
@endsection

@section('content')
    <h2 class="section-title">Hi, {{ ucwords(auth()->user()->name) }}!</h2>
    <p class="section-lead">
        Change information about yourself on this page.
    </p>

    <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-6">
            <div class="card card-warning">
                <form action="{{ route('user-profile-information.update') }}" method="post" id="form-data" class="needs-validation" novalidate="">
                    @csrf
                    @method('PUT')

                    <div class="card-header">
                        <h4>Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ ucwords(auth()->user()->name) }}" required="">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ strtolower(auth()->user()->email) }}" required="">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-warning">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-6">
            <div class="card card-danger">
                <form action="{{ route('user-password.update') }}" method="post" id="form-change-password" class="needs-validation" novalidate="">
                    @csrf
                    @method('PUT')

                    <div class="card-header">
                        <h4>Change Password</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4 col-12">
                                <label>Current Password</label>
                                <input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" value="">
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4 col-12">
                                <label>Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                    value="">
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4 col-12">
                                <label>Confirmation Password</label>
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" value="">
                                @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-danger">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
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

                    iziToast.success({
                        title: "Success!",
                        message: "Update profile was successful.",
                        position: "topRight",
                        onClosing: function () {
                            window.location.reload();
                        }
                    });
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

        $("#form-change-password").submit(function(e) {
            e.preventDefault();
            LoadingShow();
            //define variable
            var formdata = $('#form-change-password').serialize();
            var formAction = $('#form-change-password').attr('action');

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

                    iziToast.success({
                        title: "Success!",
                        message: "Update password was successful.",
                        position: "topRight",
                        onClosing: function () {
                            window.location.reload();
                        }
                    });
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
