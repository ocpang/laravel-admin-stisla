<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name', 'Laravel') }} &mdash; Error 500</title>
    <link rel="shortcut icon" href="https://avatars.githubusercontent.com/u/10754039?s=48&v=4" />

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>500</h1>
                        <div class="page-description">
                            Whoopps, something went wrong.
                        </div>
                        <div class="page-search">
                            <div class="mt-3">
                                <a href="{{ route('admin.dashboard') }}">Back to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simple-footer mt-5">
                    Copyright &copy; OCTAVIAN PNG {{ date('Y') }}
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

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
