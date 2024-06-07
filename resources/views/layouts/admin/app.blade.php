<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }} &mdash; {{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/10754039?s=48&v=4" />

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/plugins/waitMe/waitMe.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/izitoast/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">

    @stack('styles')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>

<body id="body-app">
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg bg-dark"></div>
            <!-- Navbar -->
            @include('layouts.admin.partials.navbar')

            <!-- Sidebar -->
            @include('layouts.admin.partials.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>{{ $title }}</h1>
                        @yield('breadcrumb')
                    </div>

                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>

            <!-- Footer -->
            @include('layouts.admin.partials.footer')
        </div>
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
    <script src="{{ asset('assets/plugins/waitMe/waitMe.js') }}"></script>
    <script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/modules/select2/dist/js/select2.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        var token = $("meta[name='csrf-token']").attr("content");

        function LoadingShow() {
            $('#body-app').waitMe({
                effect: 'roundBounce',
                text: 'Please wait',
                bg: 'rgba(255,255,255,0.7)',
                color: '#000',
                maxSize: '',
                waitTime: -1,
                textPos: 'vertical',
                fontSize: '',
                source: '',
                // onClose : function() {}
            });
        }

        function LoadingHide() {
            $('#body-app').waitMe("hide")
        }

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

    @stack('scripts')

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
