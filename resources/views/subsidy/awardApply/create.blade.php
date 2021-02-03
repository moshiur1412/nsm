@extends('subsidy.layouts.master')

@section('title')
公益財団法人 野口研究所
@endsection


@section('content')


<div class="row clearfix">


    {!! Form::open(['route' => ['awardApply.confirm'], 'method' => 'post','class'=>'cd-form', 'id'=>'Submit', 'enctype' => 'multipart/form-data']) !!}

        <div class="card">
            <div class="header">
                <h2>【STEP:1 入力】野口遵賞申請書送信フォーム</h2>
                <ul class="header-dropdown m-r--5"></ul>
            </div>
            <div class="box-form">
                @include('subsidy.layouts.partials.errors')
                <div class="notes">
                  <p>こちらの申込みには野口遵賞の応募様式が必要になります。<br>
                    ご用意がお済みでない方はメインメニューからダウンロードして、記載の上再度こちらのフォームにて申請をお願いします。<br>
                    ボタンの押下が出来なくなった時は、ブラウザを更新して再度お試しください。
                  </p>
                    <span class="caution">*</span>は必須項目です
                </div>

                <seciotn class="registForm">
                    <section class="basicForm">
                     <div class="form-group">
                        {!! Form::label("【 氏名 】") !!}<span class="caution">*</span>
                        <div class="fieldset">
                            {!! Form::text("name", empty($prev_input['name']) ? $applicant->name : $prev_input['name'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '.  ($errors->has("name") ? "is-invalid" : ""), 'placeholder' => '例）山田 太郎']) !!}
                        </div>
                        @if($errors->has('name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name')}}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Form::label("【 フリガナ 】") !!}<span class="caution">*</span>
                        <div class="fieldset">
                            {!! Form::text("name_kana", empty($prev_input['name_kana']) ? $applicant->name_kana : $prev_input['name_kana'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '. ($errors->has("name_kana") ? "is-invalid" : ""), 'placeholder' => '例）ヤマダ タロウ']) !!}
                        </div>
                        @if($errors->has('name_kana'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name_kana')}}</strong>
                        </div>
                        @endif
                    </div>


                    <div class="form-group">
                        {!! Form::label("","【 氏名（ローマ字） ※姓・名の順に記載（例1：yamada tarou）（例2：darwin charles) 】") !!}<span class="caution">*</span>
                        <div class="fieldset">
                            {!! Form::text("name_alphabet", empty($prev_input['name_alphabet']) ? null : $prev_input['name_alphabet'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '.($errors->has("name_alphabet") ? "is-invalid" : ""), 'placeholder' => '例）yamada tarou']) !!}
                        </div>
                        @if($errors->has('name_alphabet'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name_alphabet')}}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Form::label("【 生年月日 】") !!}<span class="caution">*</span>
                        <div class="fieldset">
                            {!! Form::text("birthday_year", empty($prev_input['birthday']) ? null : substr($prev_input['birthday'], 0, 4), ['maxlength'=>'4', 'size'=>'8', 'class'=>'has-padding has-border modal_boxSizing', 'placeholder' => '例）1990']) !!}
                            {!! Form::label("-") !!}
                            {!! Form::text("birthday_month", empty($prev_input['birthday']) ? null : substr($prev_input['birthday'], 5, 2), ['maxlength'=>'2', 'size'=>'8', 'class'=>'has-padding has-border modal_boxSizing', 'placeholder' => '例）01']) !!}
                            {!! Form::label("-") !!}
                            {!! Form::text("birthday_day", empty($prev_input['birthday']) ? null : substr($prev_input['birthday'], 8, 2), ['maxlength'=>'2', 'size'=>'8','class'=>'has-padding has-border modal_boxSizing', 'placeholder' => '例）01']) !!}
                        </div>
                        {!! Form::hidden('birthday', empty($prev_input['birthday']) ? null : $prev_input['birthday']) !!}

                        @if($errors->has('birthday'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('birthday')}}</strong>
                        </div>
                        @endif
                    </div>

                    <section class="positionradio">
                        {!! Form::label("【 所属区分 】") !!}<span class="caution">*</span>
                        <div class="form-group">
                            @if(empty($prev_input['belong_type_name']))
                            {!! Form::radio("belong_type_name", "大学", $applicant->belong_type_name == "大学" ? true : false, ['class'=>'with-gap', 'id'=>'universtiy']) !!}
                            {!! Form::label('universtiy',"大学") !!}

                            {!! Form::radio("belong_type_name", "高専", $applicant->belong_type_name == "高専" ? true : false, ['class'=>'with-gap', 'id'=>'college']) !!}
                            {!! Form::label('college',"高専", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("belong_type_name", "その他", $applicant->belong_type_name == "その他" ? true : false, ['class'=>'with-gap','id'=>'others']) !!}
                            {!! Form::label('others',"その他", ['class' => "m-l-20"]) !!}
                            @else
                            {!! Form::radio("belong_type_name", "大学", $prev_input['belong_type_name'] == "大学" ? true : false, ['class'=>'with-gap', 'id'=>'universtiy']) !!}
                            {!! Form::label('universtiy',"大学") !!}

                            {!! Form::radio("belong_type_name", "高専", $prev_input['belong_type_name'] == "高専" ? true : false, ['class'=>'with-gap', 'id'=>'college']) !!}
                            {!! Form::label('college',"高専", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("belong_type_name", "その他", $prev_input['belong_type_name'] == "その他" ? true : false, ['class'=>'with-gap','id'=>'others']) !!}
                            {!! Form::label('others',"その他", ['class' => "m-l-20"]) !!}
                            @endif

                        </div>
                        @if($errors->has('belong_type_name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('belong_type_name')}}</strong>
                        </div>
                        @endif
                    </section>

                    <div class="form-group">
                        {!! Form::label("【 所属機関名 】") !!}<span class="caution">*</span>
                        <p class="fieldset">
                            {!! Form::text("belongs", empty($prev_input['belongs']) ? $applicant->belongs : $prev_input['belongs'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '.($errors->has("belongs") ? "is-invalid" : "") ,'placeholder' => '例）東京大学', 'list' => 'belong-candidate']) !!}
                        </p>
                        @if($errors->has('belongs'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('belongs')}}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Form::label("【 所属名 】") !!}<span class="caution">*</span>
                        <p class="fieldset">
                            {!! Form::text("major", empty($prev_input['major']) ? $applicant->major : $prev_input['major'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '.($errors->has("major") ? "is-invalid" : ""), 'placeholder' => '例）理学部']) !!}
                        </p>
                        @if($errors->has('major'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('major')}}</strong>
                        </div>
                        @endif
                    </div>

                    <section class="positionradio">
                        <label>【 職 】<span class="caution">*</span></label>
                        <div class="form-group">
                            {!! Form::radio("job", "教授", (!empty($prev_input['occupation']) && $prev_input['occupation'] == "教授") ? true : false, ['class'=>'with-gap', 'id'=>'professor']) !!}
                            {!! Form::label('professor',"教授") !!}

                            {!! Form::radio("job", "准教授", (!empty($prev_input['occupation']) && $prev_input['occupation'] == "准教授") ? true : false, ['class'=>'with-gap', 'id'=>'associate_professor']) !!}
                            {!! Form::label('associate_professor',"准教授", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("job", "専任講師",  (!empty($prev_input['occupation']) && $prev_input['occupation'] == "専任講師") ? true : false, ['class'=>'with-gap', 'id'=>'fulltime_lecturer']) !!}
                            {!! Form::label('fulltime_lecturer',"専任講師", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("job", "助教", (!empty($prev_input['occupation']) && $prev_input['occupation'] == "助教") ? true : false, ['class'=>'with-gap', 'id'=>'trainig_assistant']) !!}
                            {!! Form::label('trainig_assistant',"助教", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("job", "その他", (!empty($prev_input['occupation']) && $prev_input['occupation'] != "教授" && $prev_input['occupation'] != "准教授" && $prev_input['occupation'] != "専任講師" && $prev_input['occupation'] != "助教") ? true : false, ['class'=>'with-gap', 'id'=>'radio_job05_other']) !!}
                            {!! Form::label('radio_job05_other',"その他", ['class' => "m-l-20"]) !!}

                            @if($errors->has('job'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('job')}}</strong>
                            </div>
                            @endif

                            <div class="sonota">
                                <label><small>※その他を選択した場合</small></label>
                                    {!! Form::text("job_other", (!empty($prev_input['occupation']) && $prev_input['occupation'] != "教授" && $prev_input['occupation'] != "准教授" && $prev_input['occupation'] != "専任講師" && $prev_input['occupation'] != "助教") ? $prev_input['occupation'] : null, ['class'=>'full-width has-padding has-border modal_boxSizing signup-password','id'=>'job_other', 'placeholder' => '']) !!}
                            </div>
                            @if($errors->has('job_other'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('job_other')}}</strong>
                            </div>
                            @endif
                        </div>
                    </section>



                    <div class="form-group">
                        {!! Form::label("【 郵便番号 】") !!}<span class="caution">*</span>
                        <div class="fieldset">
                            @php
                            $str_arr = empty($prev_input['zip_code']) ? array() : explode ("-", $prev_input['zip_code']);
                            @endphp
                            {!! Form::text("postalcode_1", empty($prev_input['zip_code']) ? null : $str_arr[0], ['maxlength'=>'3', 'size'=>'8', 'class'=>'has-padding has-border modal_boxSizing '.($errors->has('postalcode_1') ? "is-invalid" : "") ,'placeholder' => '例）150']) !!}
                            {!! Form::label("-") !!}
                            {!! Form::text("postalcode_2", empty($prev_input['zip_code']) ? null : $str_arr[1], ['maxlength'=>'4', 'size'=>'8','onKeyUp' => 'AjaxZip3.zip2addr(\'postalcode_1\',\'postalcode_2\',\'address1\',\'address1\');', 'class'=>'has-padding has-border modal_boxSizing '.($errors->has('postalcode_2') ? "is-invalid" : ""), 'placeholder' => '例）0001']) !!}
                        </div>

                        @if($errors->has('postalcode_1'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('postalcode_1')}}</strong>
                        </div>
                        @endif
                        @if($errors->has('postalcode_2'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('postalcode_2')}}</strong>
                        </div>
                        @endif

                    </div>


                    <div class="form-group">
                        {!! Form::label("【 住所1 】") !!}<span class="caution">*</span>
                        <div class="fieldset">
                            {!! Form::text("address1", empty($prev_input['address1']) ? null : $prev_input['address1'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '.($errors->has('address1') ? "is-invalid" : ""), 'placeholder' => '例）東京都板橋区加賀']) !!}
                        </div>
                        @if($errors->has('address1'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('address1')}}</strong>
                        </div>
                        @endif
                    </div>


                    <div class="form-group">
                        {!! Form::label("【 住所2 】") !!}
                        <div class="fieldset">
                            {!! Form::text("address2", empty($prev_input['address2']) ? null : $prev_input['address2'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '.($errors->has('address2') ? "is-invalid" : ""), 'placeholder' => '例）大学 棟名']) !!}
                        </div>
                        @if($errors->has('address2'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('address2')}}</strong>
                        </div>
                        @endif
                    </div>


                    <div class="form-group">
                        {!! Form::label("【 研究テーマ名 】") !!}<span class="caution">*</span>
                        <div class="fieldset">
                            {!! Form::text("theme", empty($prev_input['theme']) ? null : $prev_input['theme'], ['class'=>'full-width has-padding has-border modal_boxSizing signup-password '.($errors->has('theme') ? "is-invalid" : ""), 'placeholder' => '※全角40字以内','maxlength' => '40']) !!}
                        </div>
                        @if($errors->has('theme'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('theme')}}</strong>
                        </div>
                        @endif

                    </div>



                    {!! Form::label("【 助成金採択年度 】") !!}<span class="caution">*</span>
                    <div class="form-group select-m clearfix">
                        {!! Form::select("subsidy_granted_year", [''=>'選択してください', date('Y-m-d', strtotime('-4 year')) => date('Y', strtotime('-4 year')).'年度・野口遵研究助成金', date('Y-m-d', strtotime('-3 year')) => date('Y', strtotime('-3 year')).'年度・野口遵研究助成金'], empty($prev_input['subsidy_granted_year']) ? '選択してください' : $prev_input['subsidy_granted_year'], ['class'=>'form-control show-tick']) !!}
                    </div>


                    <div>
                        {!! Form::label("【 添付PDF 】") !!}<span class="caution">*</span>
                        <section class="positionradio">
                            <div class="fieldset" id="attachment">
                                <div id="js-selectFile01">
                                    <p>様式：申請書表紙</p>
                                    {!! Form::file("attachment",['id'=>'js-upload01', 'style'=>'display:none','accept'=>'attachment/pdf']) !!}
                                    <button class="file">ファイルを選択</button>
                                    <span class="file-icon">未選択</span>
                                </div>
                            </div>

                            @if($errors->has('attachment'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('attachment')}}</strong>
                            </div>
                            @endif
                        </section>
                    </div>
                </section>
            </seciotn>
            <p class="fieldset CancellationButtonGroup">
                {!! Form::submit("入力内容の確認", ['class'=>'full-width has-padding', 'id' => 'submit']) !!}
            </p>
        </div>
    </div>
</form>
</div>

<datalist id="belong-candidate">
</datalist>



@endsection

@section('extra-script')



@endsection

@section('custom-script')
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<script type="text/javascript">

function toggleSubmit() {
    var error = $('.positionradio .checksize ');
    if (error.html() != undefined) {
        $('#submit').prop('disabled', true);
    }else{
        $('#submit').prop('disabled', false);
    }
}

$('input[name=attachment]').on('change', function(){
    var self = $('#attachment');
    if (this.files[0].size/1024/1024 >= 5) {
        self.after('<div class="invalid-feedback checksize attachment"><strong>PDFファイルは5MB未満にしてください。</strong></div>');
    }else{
        $('.attachment').remove();
    }
    toggleSubmit();
});

    checkOccupation();
    birthday();

    $('input[type=radio][name=job]').on('change', function(){
        checkOccupation();
    });

    function checkOccupation(){
        if ($("#radio_job05_other").prop("checked")) {
            $("#job_other").prop('disabled', false);
        }else{
            $("#job_other").val('');
            $("#job_other").prop('disabled', true);
        }
    }

    $('input[name=birthday_day]').keyup(function(){
        birthday();
    });

    $('input[name=birthday_month]').keyup(function(){
        birthday();
    });

    $('input[name=birthday_year]').keyup(function(){
        birthday();
    });

    function birthday(){
        var day = $('input[name=birthday_day]').val();
        var month = $('input[name=birthday_month]').val();
        var year = $('input[name=birthday_year]').val();
        var birthday = year+'-'+month+'-'+day;
        $('input[name=birthday]').val(birthday);
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
@endsection
