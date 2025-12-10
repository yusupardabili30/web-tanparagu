@include("partials.main")

<head>

    @include("partials.title-meta")

    <!-- jsvectormap css -->
    <link href="{{asset('build')}}/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{asset('build')}}/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

    @include("partials.head-css")

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        {{-- @include("partials.menu")  --}}

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                @yield('mycontent')
            </div>
            <!-- End Page-content -->

            @include("partials.footer")
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    {{--
    @include("partials.customizer")  --}}

    @include("partials.vendor-scripts")




    {{-- <!-- apexcharts -->
    <script src="{{asset('build')}}/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Vector map-->
    <script src="{{asset('build')}}/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="{{asset('build')}}/libs/jsvectormap/maps/world-merc.js"></script>

    <!--Swiper slider js-->
    <script src="{{asset('build')}}/libs/swiper/swiper-bundle.min.js"></script>

    <!-- Dashboard init -->
    <script src="{{asset('build')}}/js/pages/dashboard-ecommerce.init.js"></script> --}}

    <!-- Sweet Alerts js -->
    <script src="{{asset('build')}}/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- App js -->
    <script src="{{asset('build')}}/js/jquery-3.7.1.min.js"></script>
    <script src="{{asset('build')}}/js/appsecondary.js"></script>

    @yield('sipproja-js')
</body>

</html>