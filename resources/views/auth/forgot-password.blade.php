@extends('layouts.auth.app', ['title' => 'Forgot Password'])

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Forgot Password</h4>
        </div>

        <div class="card-body">
            <p class="text-muted">We will send a link to reset your password</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" tabindex="1" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="2">
                        Forgot Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-5 text-muted text-center">
        Already have an account? <a href="{{ route('login') }}">Sign In Now</a>
    </div>
@endsection

@push('scripts')
<script>
    // Toast Alert Config
    @if(session('status') != null)
        iziToast.success({
            title: "Success!",
            message: "{{ session('status') }}",
            position: "topRight"
        });
    @endif

</script>
@endpush
