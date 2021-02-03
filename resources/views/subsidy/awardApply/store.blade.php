@extends('subsidy.layouts.master')

@section('title')
公益財団法人 野口研究所
@endsection

@section('content')

<div class="row clearfix">

    {!! Form::open(['route' => 'awardApply.create', 'method' => 'post','class'=>'cd-form', 'id'=>'Submit']) !!}

    <div class="card">
        <div class="header">
            <h2>【STEP:3 完了】野口遵賞申請書送信フォーム</h2>
            <ul class="header-dropdown m-r--5"></ul>
        </div>
        <div class="box-form">
            <div class="notes">
                <p>申請書の送信が完了しました。</p>
            </div>

            <seciotn class="registForm">
                <section class="basicForm">
                  <div class="compfsize marginT15">
                    <p>採択結果は後日書面にてご案内いたします。</p>
                  </div>
                <div class="backToTop">
                  <p><a href="{{route('mypage.index')}}">トップページへ戻る</a></p>
                </div>
            </section>

        </section>
    </seciotn>

</div>
</div>

</div>


@endsection

@section('extra-script')



@endsection

@section('custom-script')

{{Html::script('js/pages/tables/jquery-datatable.js')}}
<script type="text/javascript">
        /*$(document).ready( function () {
            $('#admins-list').DataTable();
        });*/
    </script>
    @endsection
