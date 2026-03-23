<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark"
    data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | AK-DESIGN - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="DEV~SAM" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- LiveWire --}}
    @livewireStyles

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    @include('layouts.head-css')

    @yield('styles')
</head>

@section('body')
    @include('layouts.body')
@show

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('layouts.topbar')
        @include('layouts.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </div>

            @include('layouts.footer')

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    @include('layouts.customizer')

    {{-- LiveWire --}}
    @livewireScripts


    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    {{-- Scripts الخاصة بكل صفحة --}}
    @yield('script')
</body>

</html>
