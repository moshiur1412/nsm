@extends('subsidy.layouts.master')
@section('title')
    公益財団法人 野口研究所
@endsection

@section('content')
<div class="row clearfix">
    <form class="cd-form" id="Submit" name="inquiry_form" action="input_comp.php" method="POST" enctype="multipart/form-data" role="form" autocomplete="off">
        <div class="card">
            <div class="header">
                <h2>【STEP:3 完了】野口遵研究助成金申請書・参考論文送信フォーム</h2>
                <ul class="header-dropdown m-r--5"></ul>
            </div>
            <div class="box-form">
                <div class="notes">
                    <p>申請書・参考論文の送信が完了しました。</p>
                </div>

                <seciotn class="registForm">
                    <section class="basicForm">
                        <div class="compfsize marginT15">
                            <p>採択結果は後日メールおよび当サイトでご確認いただけます。</p>
                        </div>
                        <div class="backToTop">
                            <p><a href="{{ route('mypage.index') }}">トップページへ戻る</a></p>
                        </div>
                    </section>
                </seciotn>
            </div>
        </div>
    </form>
</div>

@endsection
