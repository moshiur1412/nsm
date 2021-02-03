<!DOCTYPE html>
<html lang="ja">

  <!-- Header -->
  <head>
    <!-- meta -->
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>ERROR 403 || 公益財団法人 野口研究所</title>

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




    <!-- Content Section -->
    <section class="content newUser">
		<div class="container-fluid">
        	<div class="row clearfix">
                <form class="cd-form" id="Submit">
                    <div class="card">
                        <div class="header">
                            <h2>403 ERROR Forbidden</h2>
                            <ul class="header-dropdown m-r--5"></ul>
                        </div>
                        <div class="box-form">
                            <div class="notes">
                                <p>アクセスが禁止されています。<br>ページへのアクセス権が与えられていないか、サーバへのアクセスが混み合っている可能性があります。<br>しばらく時間をおいてからもう一度アクセスして頂くか、管理者にお問い合わせください。</p>
                            </div>

                            <seciotn class="registForm">
                                <section class="basicForm">
                                    <!-- <div class="compfsize marginT15">
                                        <p>審査を進めさせていただきます。</p>
                                        <p>注意書き等</p>
                                    </div> -->
                                    <div class="backToTop">
                                        <p><a href="{{ route('mypage.index') }}">トップページへ戻る</a></p>
                                    </div>
                                </section>
                            </seciotn>
                        </div>
                    </div>
                </form>
            </div>
    	</div>
    </section>
    <!-- #ENDS# Content Section -->
	
	<!-- footer -->
    @include('subsidy.layouts.partials.scripts')

    @yield('custom-script')

	<!-- Demo Js --> 
    {{Html::script('js/demo.js')}}

    {{Html::script('js/form.js')}}

  </body>

</html>

