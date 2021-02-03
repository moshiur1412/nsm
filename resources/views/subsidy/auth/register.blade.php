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
        
        
        <form class="cd-form" id="Submit" name="inquiry_form" method="POST" action="{{ route('app.register') }}">
          {{ csrf_field() }}
          
          
          
          <div class="card">
            <div class="header">
              <h2>新規ユーザー登録</h2>
              <ul class="header-dropdown m-r--5"></ul>
            </div>
            <div class="box-form clearfix">
              @include('subsidy.layouts.partials.errors')
              
              <div class="notes">
                <p>野口遵研究助成金および野口遵賞の応募のためのユーザー登録を行います。</p>
              </div>
              
              <div class="form-group">
                <label>【 氏名 】<span class="caution">*</span></label>
                <div class="form-line">
                  {!! Form::text('name', null, array('class' =>'form-control modal_boxSizing signup-password'.($errors->has("name") ? " is-invalid" : ""), 'placeholder' => '例）山田 太郎')) !!}
                </div>
                @if ($errors->has('name'))
                <div class="invalid-feedback">
                  <strong>{{ $errors->first('name') }}</strong>
                </div>
                @endif
              </div>
              
              <div class="form-group">
                <label>【 フリガナ 】<span class="caution">*</span></label>
                <div class="form-line">
                  {!! Form::text('name_kana', null, array('class' =>'form-control modal_boxSizing signup-password'.($errors->has("name_kana") ? " is-invalid" : ""), 'placeholder' => '例）ヤマダ タロウ')) !!}
                </div>
                @if ($errors->has('name_kana'))
                <div class="invalid-feedback">
                  <strong>{{ $errors->first('name_kana') }}</strong>
                </div>
                @endif
              </div>
              
              <section class="positionradio">
                <label>【 所属区分 】<span class="caution">*</span></label>
                <div class="form-group">
                  {!! Form::radio("belong_type_name", "大学", false, ['class'=>'with-gap', 'id'=>'radio_affiliation01']) !!}
                  {!! Form::label('radio_affiliation01',"大学") !!}
                  
                  {!! Form::radio("belong_type_name", "高専", false, ['class'=>'with-gap', 'id'=>'radio_affiliation02']) !!}
                  {!! Form::label('radio_affiliation02',"高専", ['class' => "m-l-20"]) !!}
                  
                  {!! Form::radio("belong_type_name", "その他", false, ['class'=>'with-gap','id'=>'radio_affiliation03']) !!}
                  {!! Form::label('radio_affiliation03',"その他", ['class' => "m-l-20"]) !!}
                  @if ($errors->has('belong_type_name'))
                  <div class="invalid-feedback">
                    <strong>{{ $errors->first('belong_type_name') }}</strong>
                  </div>
                  @endif
                </div>
              </section>
              
              <div class="form-group">
                <label>【 所属機関名 】<span class="caution">*</span></label>
                <div class="form-line">
                  {!! Form::text('belongs', null, array('class' =>'form-control modal_boxSizing signup-password'.($errors->has("belongs") ? " is-invalid" : ""), 'placeholder' => '例）東京大学', 'list' => 'belong-candidate')) !!}
                </div>
                @if ($errors->has('belongs'))
                <div class="invalid-feedback">
                  <strong>{{ $errors->first('belongs') }}</strong>
                </div>
                @endif
              </div>
              
              <div class="form-group">
                <label>【 所属名 】<span class="caution">*</span></label>
                <div class="form-line">
                  {!! Form::text('major', null, array('class' =>'form-control modal_boxSizing signup-password'.($errors->has("major") ? " is-invalid" : ""), 'placeholder' => '例）理学部')) !!}
                </div>
                @if ($errors->has('major'))
                <div class="invalid-feedback">
                  <strong>{{ $errors->first('major') }}</strong>
                </div>
                @endif
              </div>
              
              <label for="user-email" >メールアドレス</label>
              <div class="form-group">
                <div class="form-line">
                  <input type="email" name="user-email" class="form-control {{ $errors->has('user-email') ? 'is-invalid' : ''}}" placeholder="メールアドレス" value="{{ old('user-email')}}">
                </div>
                @if($errors->has('user-email'))
                <div class="invalid-feedback">
                  <strong>{{ $errors->first('user-email')}}</strong>
                </div>
                @endif
              </div>
              <label for="user-password">パスワード</label>
              <div class="form-group ">
                <div class="form-line">
                  <input type="password" name="user-password" class="form-control  {{ $errors->has('user-password') ? 'is-invalid' : ''}} " placeholder="パスワード" value="{{old('user-password')}}">
                </div>
                @if($errors->has('user-password'))
                <div class="invalid-feedback">
                  <strong>{{ $errors->first('user-password')}}</strong>
                </div>
                @endif
              </div>
              <label for="password_confirmation_lavel">パスワード確認用</label>
              <div class="form-group">
                <div class="form-line">
                  <input type="password"  name="user-password_confirmation" class="form-control {{ $errors->has('user-password_confirmation') ? 'is-invalid' : ''}}" placeholder="パスワード確認用" value="{{old('user-password_confirmation')}}">
                </div>
                @if($errors->has('user-password_confirmation'))
                <div class="invalid-feedback">
                  <strong>{{ $errors->first('user-password_confirmation')}}</strong>
                </div>
                @endif
                
              </div>
              <div class="text-left">
                <button type="submit" class="btn btn-noguchi border-noguchi waves-effect active">登録</button>
              </div>
              
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
</div>

<!-- footer -->
@include('subsidy.layouts.partials.scripts')

@yield('custom-script')

<!-- Demo Js -->
{{Html::script('js/demo.js')}}

{{Html::script('js/form.js')}}

<datalist id="belong-candidate">
</datalist>

<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

<script type="text/javascript">

function toggleSubmit() {
    var error = $('.positionradio .invalid-feedback ');
    if (error.html() != undefined) {
        $('#submit').prop('disabled', true);
    }else{
        $('#submit').prop('disabled', false);
    }
}

    let $belong = $('input[name=belongs]');
    let belong_json = null;
    $belong.on('input', function(event) {
        if ($belong.val().length == 0) return;
        $.ajax({
          type: "GET",
          url: "{{ route('school.index') }}/?keyword="+$belong.val(),
          success:function (data) {
            if (data == undefined || data == null || $.isEmptyObject(data)) return;
            belong_json = $.parseJSON(data);
            $("#belong-candidate").empty();
            $.each(belong_json, function(index, element) {
                $('#belong-candidate').append('<option value="'+element+'"></option>');
            });
        },
        error:function (data) {
            console.log('Error:', data);
        }
    });
    });

</script>


</body>

</html>


