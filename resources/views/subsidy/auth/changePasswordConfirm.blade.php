@extends('subsidy.layouts.master')

@section('title')
公益財団法人 野口研究所
@endsection

@section('content')

<div class="container-fluid">
  <div class="row clearfix">

    <form class="cd-form" id="Submit" name="inquiry_form" action="input_comp.php" method="POST" enctype="multipart/form-data" role="form" autocomplete="off">
      <div class="card">
        <div class="header">
          <h2>【完了】パスワード変更</h2>
          <ul class="header-dropdown m-r--5"></ul>
        </div>
        <div class="box-form">
          <div class="notes">
            <p>パスワードの変更が完了しました。</p>
          </div>

          <seciotn class="registForm">
            <section class="basicForm">
              <div class="backToTop">
                <p><a href="{{route('mypage.index')}}">トップページへ戻る</a></p>
              </div>
            </section>
          </seciotn>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection