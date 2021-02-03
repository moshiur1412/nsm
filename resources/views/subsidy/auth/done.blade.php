<!DOCTYPE html>
<html lang="ja">

<!-- Header -->
<head>
	<!-- meta -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>公益財団法人 野口研究所</title>

	<!-- Favicon-->
	<link rel="icon" href="{{{ asset('favicon.ico') }}}" type="image/x-icon">

	<!-- CSS-->
	@include('subsidy.layouts.partials.styles')

	<!-- Custom Css -->
	@yield('custom-css')

</head>
<!-- #ENDS# Header -->

<body class="theme-noguchi main-body">

	<!-- Overlay For Sidebars -->
	<div class="overlay"></div>
	<!-- #END# Overlay For Sidebars -->
	@include('subsidy.layouts.partials.alerts')
	<!-- Top Bar -->
	<nav class="navbar newUserNav">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="{{ route('mypage.index') }}">公益財団法人 野口研究所</a>
			</div>
		</div>
		<div class="nav-underLine"></div>
	</nav>
	<!-- #END# Top Bar -->
	<section class="content newUser">
		<div class="container-fluid">
			<div class="row clearfix">


				<div class="card">
					<div class="header">
						<h2>【完了】新規ユーザー登録</h2>
						<ul class="header-dropdown m-r--5"></ul>
					</div>
					<div class="box-form">
						<div class="notes">
							<p>ユーザーの登録が完了しました。<br>
								続いて応募を行う方は以下のログインボタンより進んでください。
							</p>
						</div>

						<seciotn class="registForm">
							<section class="basicForm">
						            <!-- <div class="compfsize marginT15">
								        <p>審査を進めさせていただきます。</p>
								        <p>注意書き等</p>
								    </div> -->

								    <div class="text-left">
								    	<a href="{{route('app.login')}}" class="btn btn-noguchi border-noguchi waves-effect active">ログイン </a>
								    </div>
								</section>
							</seciotn>
						</div>
					</div>
				</form>
			</div>
		</div>

	</section>


	<!-- footer -->
	@include('subsidy.layouts.partials.scripts')

	@yield('custom-script')

	<!-- Demo Js -->
	{{Html::script('js/demo.js')}}

	{{Html::script('js/form.js')}}

</body>

</html>
