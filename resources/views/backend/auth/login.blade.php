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

    <title>公益財団法人 野口研究所</title>

    <!-- Favicon-->
    <link rel="icon" href="{{{ asset('favicon.ico') }}}" type="image/x-icon">

    <!-- CSS-->

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    {{ Html::style('plugins/bootstrap/css/bootstrap.css') }}

    <!-- Waves Effect Css -->
    {{ Html::style('plugins/node-waves/waves.css') }}

    <!-- Animation Css -->
    {{ Html::style('plugins/animate-css/animate.css') }}

    <!-- Morris Chart Css-->
    {{ Html::style('plugins/morrisjs/morris.css') }}

    <!--select-->
    {{ Html::style('plugins/bootstrap-select/css/bootstrap-select.css') }}

    {{ Html::style('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}

    <!-- Custom Css -->
    {{ Html::style('css/style.css') }}

    <!-- overwrite Css-->
    {{ Html::style('css/overwrite.css') }}

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    {{ Html::style('css/all-themes.css') }}

    <!-- LightBox Plugin Css -->
    {{ Html::style('plugins/lightbox/css/lightbox.css') }}

    <!-- Sweetalert Css -->
    {{ Html::style('plugins/sweetalert/sweetalert.css') }}

    <!-- Light Gallery Plugin Css -->
    {{ Html::style('plugins/light-gallery/css/lightgallery.css') }}

    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    {{ Html::style('css/common.css') }}
    {{ Html::style('css/form.css') }}

</head>
<!-- #ENDS# Header -->

<body class="login-page">

    <div class="login-box">
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" action="{{ route('back.login') }}">
                   @csrf
                   @if ($errors->any())
                   <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="msg">公益財団法人 野口研究所</div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input id="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="ID（メールアドレス" value="{{ old('email') }}" required autofocus>
                    </div>


                    @if ($errors->has('email'))
                    <label class="error" for="email">
                        {{ $errors->first('email') }}
                    </label>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input id="login_password" type="password" class="form-control{{ $errors->has('login_password') ? ' is-invalid' : '' }}" name="login_password" placeholder="パスワード" required>
                    </div>
                    @if ($errors->has('login_password'))
                    <label class="error" for="login_password">
                        {{ $errors->first('login_password') }}
                    </label>
                    @endif
            </div>
            <div class="row">
                <div class="col-xs-8 p-t-5">
                    <input type="checkbox" class="filled-in chk-col-red" name="judge" id="remember">
                    <label for="remember">審査員としてログインする</label>
                </div>
                <div class="col-xs-4">
                    <button class="btn btn-block bg-red waves-effect" type="submit">ログイン</button>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-8 p-t-5" style="font-weight :bold">
                    ※アクセス可能期間は11月1日から3月31日までです。
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<!-- footer -->
<!-- Jquery Core Js -->
{{Html::script('plugins/jquery/jquery.min.js')}}

<!-- Bootstrap Core Js -->
{{Html::script('plugins/bootstrap/js/bootstrap.js')}}

<!-- Waves Effect Plugin Js -->
{{Html::script('plugins/node-waves/waves.js')}}

<!-- Validate Plugin Js -->
{{Html::script('plugins/jquery-validation/jquery.validate.js')}}

<!-- Custom Js -->
{{Html::script('js/admin.js')}}
{{Html::script('js/pages/examples/sign-in.js')}}


<!-- #ENDS# Javascript -->


</body>

</html>
