<!DOCTYPE html>
<html lang="ja">

  <!-- Header -->
  <head>
    <!-- meta -->
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Favicon-->
    <link rel="icon" href="{{{ asset('favicon.ico') }}}" type="image/x-icon">

    <!-- CSS-->
    @include('subsidy.layouts.partials.styles')

    <!-- Custom Css -->
    @yield('custom-css')

  </head>
  <!-- #ENDS# Header -->

  <body class="theme-noguchi main-body">

    <!-- Page Loader -->
	<div class="page-loader-wrapper">
		<div class="loader">
			<div class="preloader">
				<div class="spinner-layer pl-light-blue">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
			<p>Loading..</p>
		</div>
	</div>
    <!-- #END# Page Loader -->

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

    <!-- Top Bar -->
    @include('subsidy.layouts.partials.topbar')
    <!-- #END# Top Bar -->

    <!-- Left Sidebar -->
    @include('subsidy.layouts.partials.leftsidebar')
    <!-- #END# Left Sidebar -->


    <!-- Content Section -->
    <section class="content">
		<div class="container-fluid">
        	@yield('content')
    	</div>
    </section>
    <!-- #ENDS# Content Section -->

    <!-- View Alerts -->
    @include('subsidy.layouts.partials.alerts')
    <!-- #END# Alerts -->

	<!-- footer -->
    @include('subsidy.layouts.partials.scripts')

    @yield('custom-script')

	<!-- Demo Js -->
    {{Html::script('js/demo.js')}}

    {{Html::script('js/form.js')}}

	<!-- Flot Charts Plugin Js -->
	<!-- <script src="plugins/flot-charts/jquery.flot.js"></script>
	<script src="plugins/flot-charts/jquery.flot.resize.js"></script>
	<script src="plugins/flot-charts/jquery.flot.pie.js"></script>
	<script src="plugins/flot-charts/jquery.flot.categories.js"></script>
	<script src="plugins/flot-charts/jquery.flot.time.js"></script>  -->


	<!-- #END# footer -->


    <!-- #ENDS# Javascript -->


  </body>

</html>
