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
                <form id="sign_in" method="POST" action="{{ route('app.login') }}">
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
                @if (session('message'))
                <div class="alert alert-info">
                    <ul>
                        <li>{{ session('message') }}</li>
                    </ul>
                </div>
                @endif
                <div class="msg">公益財団法人 野口研究所</div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">person</i>
                    </span>
                    <div class="form-line">
                        <input id="email" type="email" name="email" class="form-control  {{ $errors->has('email') ? 'is-invalid' : ''}} "  placeholder="ID（メールアドレス)" value="{{ old('email') }}" required autofocus>
                    </div>
                    @if($errors->has('email'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('email')}}</strong>
                    </div>
                    @endif

                </div>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">lock</i>
                    </span>
                    <div class="form-line">
                        <input id="login_password" type="password" name="login_password" class="form-control{{ $errors->has('login_password') ? ' is-invalid' : '' }}"  placeholder="パスワード" required>
                    </div>
                    @if($errors->has('login_password'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('login_password')}}</strong>
                    </div>
                    @endif
                    @if ($errors->has('login_password'))
                    <label class="error" for="login_password">
                        {{ $errors->first('login_password') }}
                    </label>
                    @endif
                </label>
            </div>
            <div class="row">
                <div class="col-xs-8 p-t-5">
                    <input type="checkbox" class="filled-in chk-col-noguchi" name="judge" id="remember">
                    <label for="remember">パスワードを記憶する</label>
                </div>
                <div class="col-xs-4">
                    <button class="btn btn-block bg-noguchi waves-effect" type="submit">ログイン</button>
                </div>
            </div>
            <div class="row m-t-15 m-b--20">
                <div class="col-xs-6">
                    <a href="{{ route('app.register')}}">新規登録</a>
                </div>
                <div class="col-xs-6 align-right">
                </div>
            </div>
            @php
            $month = date("m");
            $project_months = ["09,10,11,12,01,02,03"];
            @endphp
            @if (!in_array($month,$project_months))
            <div class="row m-t-15 m-b--20">
                <div class="col-xs-12 p-t-5" style="font-weight :bold">
                    <!--{{date("Y")-1}}年度野口研究助成金および遵賞の募集および審査は終了しました。<br>
                    {{date("Y")}}年度の応募時期に関しては後日当ホームページにてご案内いたします。<br>-->
                    ※<font color="red">仮登録後にログインができない方、また仮登録ができない方は以下メールアドレスに様式1・様式2・参考文献に情報を記載の上メールを送付してください。</font><br>
                    &nbsp;&nbsp;jyosei@noguchi.or.jp<br>
                    <br>
                    ※{{date("Y")}}年度のご応募に際には改めてユーザー登録が必要です。
                </div>
            </div>
            @endif
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
