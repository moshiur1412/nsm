@extends('subsidy.layouts.master')

@section('title')
公益財団法人 野口研究所
@endsection

@section('content')


<div class="container-fluid">
 <div class="row clearfix">



  <form class="cd-form" id="Submit" name="inquiry_form" method="POST" action="{{ route('app.changePassword') }}">
    {{ csrf_field() }}
    
    <div class="card">
      <div class="header">
        <h2>パスワード変更</h2>
        <ul class="header-dropdown m-r--5"></ul>
      </div>
      <div class="box-form clearfix">
        
        @include('subsidy.layouts.partials.errors')
        <div class="notes">
          <p>パスワード変更をします。</p>
        </div>

        <label for="email" >メールアドレス</label>
        <div class="form-group">
          <div class="form-line focused">
            <input type="email" name="email" class="form-control" placeholder="メールアドレス" value="{{Auth::user()->email}}">
          </div>
        </div>
        <label for="new-password">パスワード</label>
        <div class="form-group ">
          <div class="form-line">
            <input type="password" name="new-password" class="form-control  {{ $errors->has('new-password') ? 'is-invalid' : ''}} " placeholder="パスワード" value="{{old('new-password')}}">
          </div>
          @if($errors->has('new-password'))
          <div class="invalid-feedback">
           <strong>{{ $errors->first('new-password')}}</strong>   
         </div>
         @endif
       </div>
       <label for="password_confirmation_lavel">パスワード確認用</label>
       <div class="form-group">
        <div class="form-line">
          <input type="password"  name="new-password_confirmation" class="form-control {{ $errors->has('new-password_confirmation') ? 'is-invalid' : ''}}" placeholder="パスワード確認用" value="{{old('new-password_confirmation')}}">
        </div>
        @if($errors->has('new-password_confirmation'))
        <div class="invalid-feedback">
          <strong>{{ $errors->first('new-password_confirmation')}}</strong>
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


@endsection