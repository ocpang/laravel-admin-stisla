<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }} &mdash; {{ $title }}</title>
    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/10754039?s=48&v=4" />

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/css/iziToast.min.css') }}">

    @stack('styles')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <img src="https://avatars.githubusercontent.com/u/10754039?s=400&u=1fe8bca3ada2aa8bb75913dee46e3b2243f66f2c&v=4" alt="OCTAVIAN PNG" width="150">
                        </div>

                        @yield('content')

                        <div class="simple-footer">
                            Copyright &copy; OCTAVIAN PNG {{ date('Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <!-- JS Libraries -->
    <script src="{{ asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>

    @stack('scripts')

    <!-- Page Specific JS File -->
    <script>
        // Toast Alert Config
        @if(session('success') != "")
            iziToast.success({
                title: "Success!",
                message: "{{ session('success') }}",
                position: "topRight"
            });
        @endif
        @if(session('info') != "")
            iziToast.info({
                title: "Info!",
                message: "{{ session('info') }}",
                position: "topRight"
            });
        @endif
        @if(session('warning') != "")
            iziToast.warning({
                title: "Warning!",
                message: "{{ session('warning') }}",
                position: "topRight"
            });
        @endif
        @if(session('error') != "")
            iziToast.error({
                title: "Error!",
                message: "{{ session('error') }}",
                position: "topRight"
            });
        @endif
    </script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>
</html>
