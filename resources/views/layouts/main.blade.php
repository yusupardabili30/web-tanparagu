@include("partials.main")

<head>

    @include("partials.title-meta")

    <!-- jsvectormap css -->
    <link href="{{asset('build')}}/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

    <!--Swiper slider css-->
    <link href="{{asset('build')}}/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

    @include("partials.head-css")
    <style>
        /* Loader Spinner */
        #loader-spinner {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        }

        #loader-spinner::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 50%;
        width: 60px;
        height: 60px;
        margin: -30px 0 0 -30px;
        border-radius: 50%;
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        animation: spin 1s linear infinite;
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }


        /*--------------------------------------------------------------
        # Hero Section
        --------------------------------------------------------------*/
        .hero {
        width: 100%;
        min-height: 70vh;
        position: relative;
        padding: 120px 0 60px 0;
        display: flex;
        align-items: center;
        }

        .hero h1 {
        margin: 0;
        font-size: 48px;
        font-weight: 700;
        line-height: 56px;
        }

        .hero p {
        color: color-mix(in srgb, var(--default-color), transparent 30%);
        margin: 5px 0 30px 0;
        font-size: 20px;
        font-weight: 400;
        }

        .hero .btn-get-started {
        color: var(--contrast-color);
        background: var(--accent-color);
        font-family: var(--heading-font);
        font-weight: 400;
        font-size: 15px;
        letter-spacing: 1px;
        display: inline-block;
        padding: 10px 28px 12px 28px;
        border-radius: 50px;
        transition: 0.5s;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1);
        }

        .hero .btn-get-started:hover {
        color: var(--contrast-color);
        background: color-mix(in srgb, var(--accent-color), transparent 15%);
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1);
        }

        .hero .btn-watch-video {
        font-size: 16px;
        transition: 0.5s;
        margin-left: 25px;
        color: var(--default-color);
        font-weight: 600;
        }

        .hero .btn-watch-video i {
        color: var(--accent-color);
        font-size: 32px;
        transition: 0.3s;
        line-height: 0;
        margin-right: 8px;
        }

        .hero .btn-watch-video:hover {
        color: var(--accent-color);
        }

        .hero .btn-watch-video:hover i {
        color: color-mix(in srgb, var(--accent-color), transparent 15%);
        }

        .hero .animated {
        animation: up-down 2s ease-in-out infinite alternate-reverse both;
        }

        @media (max-width: 640px) {
        .hero h1 {
            font-size: 28px;
            line-height: 36px;
        }

        .hero p {
            font-size: 18px;
            line-height: 24px;
            margin-bottom: 30px;
        }

        .hero .btn-get-started,
        .hero .btn-watch-video {
            font-size: 13px;
        }
        }

        @keyframes up-down {
        0% {
            transform: translateY(10px);
        }

        100% {
            transform: translateY(-10px);
        }
        }
        </style>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include("partials.menu")

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




    {{--  <!-- apexcharts -->
    <script src="{{asset('build')}}/libs/apexcharts/apexcharts.min.js"></script>

    <!-- Vector map-->
    <script src="{{asset('build')}}/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="{{asset('build')}}/libs/jsvectormap/maps/world-merc.js"></script>

    <!--Swiper slider js-->
    <script src="{{asset('build')}}/libs/swiper/swiper-bundle.min.js"></script>

    <!-- Dashboard init -->
    <script src="{{asset('build')}}/js/pages/dashboard-ecommerce.init.js"></script>  --}}

    <!-- Sweet Alerts js -->
    <script src="{{asset('build')}}/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- App js -->
    <script src="{{asset('build')}}/js/jquery-3.7.1.min.js"></script>
    <script src="{{asset('build')}}/js/app.js"></script>

    <!-- calendar min js -->
    <script src="{{asset('build')}}/libs/fullcalendar/index.global.min.js"></script>

    <!-- Calendar init -->
    <script src="{{asset('build')}}/js/pages/calendar.init.js"></script>

    @yield('sipproja-js')
</body>

</html>
