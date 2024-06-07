@extends('layouts.auth.app', ['title' => 'Reset Password'])

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Reset Password</h4>
        </div>

        <div class="card-body">
            <p class="text-muted">We will send a link to reset your password</p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $request->email }}" tabindex="1" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password" class="control-label">New Password</label>
                    <input id="password" type="password" class="form-control pwstrength @error('password') is-invalid @enderror" data-indicator="pwindicator" name="password" value="{{ old('password') }}" tabindex="2" required autofocus>
                    <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm" class="control-label">Password Confirmation</label>
                    <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}" tabindex="3" required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block"  tabindex="4" required>
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-5 text-muted text-center">
        Already have an account? <a href="{{ route('login') }}">Sign In Now</a>
    </div>
@endsection
