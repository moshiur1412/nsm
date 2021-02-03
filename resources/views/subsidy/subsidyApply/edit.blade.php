@extends('subsidy.layouts.master')
@section('title')
公益財団法人 野口研究所
@endsection

@section('content')
<div class="row clearfix">

    {!! Form::open(['method' => 'put', 'route' => ['subsidyApply.edit.confirm', $subsidy->id], 'name' => 'inquiry_form', 'id' => 'Submit', 'class' => 'demo-masked-input cd-form', 'enctype' => 'multipart/form-data', 'role' => 'form']) !!}

    <div class="card">
        <div class="header">
            <h2>【STEP:1 入力】野口遵研究助成金申請書・参考論文送信フォーム</h2>
            <ul class="header-dropdown m-r--5"></ul>
        </div>
        <div class="box-form">

            @include('subsidy.layouts.partials.errors')
            <div class="notes">
                <span class="caution">*</span>は必須項目です
            </div>

            <seciotn class="registForm">
                <section class="basicForm">

                    <div class="form-group">
                        <label>【 氏名 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('name', empty($prev_input['name']) ? $subsidy->name : $prev_input['name'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("name") ? " is-invalid" : ""), 'placeholder' => '例）山田 太郎' )) !!}
                        </p>
                        @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>【 フリガナ 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('name_kana', empty($prev_input['name_kana']) ? $subsidy->name_kana : $prev_input['name_kana'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("name_kana") ? " is-invalid" : ""), 'placeholder' => '例）ヤマダ タロウ')) !!}
                        </p>
                        @if ($errors->has('name_kana'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name_kana') }}</strong>
                        </div>
                        @endif
                    </div>


                    <div class="form-group">
                        <label>【 氏名（ローマ字） ※姓・名の順に記載（例1：yamada tarou）（例2：darwin charles) 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('name_alphabet', empty($prev_input['name_alphabet']) ? $subsidy->name_alphabet : $prev_input['name_alphabet'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("name_alphabet") ? " is-invalid" : ""), 'placeholder' => '例）yamada tarou')) !!}
                        </p>
                        @if ($errors->has('name_alphabet'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name_alphabet') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>【 生年月日 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('year', empty($prev_input['birthday']) ? substr($subsidy->birthday, 0, 4) : substr($prev_input['birthday'], 0, 4), array('class' =>'has-padding has-border modal_boxSizing'.($errors->has("birthday") ? " is-invalid" : ""), 'placeholder' => '例）1990', 'maxlength' => '4', 'size' => '8')) !!}
                            -
                            {!! Form::text('month', empty($prev_input['birthday']) ? substr($subsidy->birthday, 5, 2) : substr($prev_input['birthday'], 5, 2), array('class' =>'has-padding has-border modal_boxSizing'.($errors->has("birthday") ? " is-invalid" : ""), 'placeholder' => '例）01', 'maxlength' => '2', 'size' => '8')) !!}
                            -
                            {!! Form::text('day', empty($prev_input['birthday']) ? substr($subsidy->birthday, 8, 2) : substr($prev_input['birthday'], 8, 2), array('class' =>'has-padding has-border modal_boxSizing'.($errors->has("birthday") ? " is-invalid" : ""), 'placeholder' => '例）01', 'maxlength' => '2', 'size' => '8')) !!}
                        </p>

                        {!! Form::hidden('birthday', empty($prev_input['birthday']) ? $subsidy->birthday : $prev_input['birthday']) !!}

                        @if ($errors->has('birthday'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('birthday') }}</strong>
                        </div>
                        @endif
                    </div>

                    <section class="positionradio">
                        <label>【 所属区分 】<span class="caution">*</span></label>
                        <div class="form-group">
                            @if(empty($prev_input['belong_type_name']))
                            {!! Form::radio("belong_type_name", "大学", $subsidy->belong_type_name=='大学' ? true : false, ['class'=>'with-gap', 'id'=>'radio_affiliation01']) !!}
                            {!! Form::label('radio_affiliation01',"大学") !!}

                            {!! Form::radio("belong_type_name", "高専", $subsidy->belong_type_name=='高専'? true : false, ['class'=>'with-gap', 'id'=>'radio_affiliation02']) !!}
                            {!! Form::label('radio_affiliation02',"高専", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("belong_type_name", "その他", $subsidy->belong_type_name=='その他' ? true : false, ['class'=>'with-gap','id'=>'radio_affiliation03']) !!}
                            {!! Form::label('radio_affiliation03',"その他", ['class' => "m-l-20"]) !!}
                            @else
                            {!! Form::radio("belong_type_name", "大学", $prev_input['belong_type_name'] =='大学' ? true : false, ['class'=>'with-gap', 'id'=>'radio_affiliation01']) !!}
                            {!! Form::label('radio_affiliation01',"大学") !!}

                            {!! Form::radio("belong_type_name", "高専", $prev_input['belong_type_name'] =='高専'? true : false, ['class'=>'with-gap', 'id'=>'radio_affiliation02']) !!}
                            {!! Form::label('radio_affiliation02',"高専", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("belong_type_name", "その他", $prev_input['belong_type_name'] =='その他' ? true : false, ['class'=>'with-gap','id'=>'radio_affiliation03']) !!}
                            {!! Form::label('radio_affiliation03',"その他", ['class' => "m-l-20"]) !!}
                            @endif
                            @if ($errors->has('belong_type_name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('belong_type_name') }}</strong>
                            </div>
                            @endif
                        </div>
                    </section>


                    <div class="form-group">
                        <label>【 所属機関名 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('belongs', empty($prev_input['belongs']) ? $subsidy->belongs : $prev_input['belongs'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("belongs") ? " is-invalid" : ""), 'placeholder' => '例）東京大学', 'list' => 'belong-candidate')) !!}
                        </p>
                        @if ($errors->has('belongs'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('belongs') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>【 所属名 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('major', empty($prev_input['major']) ? $subsidy->major : $prev_input['major'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("major") ? " is-invalid" : ""), 'placeholder' => '例）理学部')) !!}
                        </p>
                        @if ($errors->has('major'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('major') }}</strong>
                        </div>
                        @endif
                    </div>

                    <section class="positionradio">
                        <label>【 職 】<span class="caution">*</span></label>
                        <div class="form-group">
                            @if(empty($prev_input['occupation']))
                            {!! Form::radio("occupation", "教授", $subsidy->occupation == "教授" ? true : false, ['class'=>'with-gap', 'id'=>'radio_job01']) !!}
                            {!! Form::label('radio_job01',"教授") !!}

                            {!! Form::radio("occupation", "准教授", $subsidy->occupation == "准教授" ? true : false, ['class'=>'with-gap', 'id'=>'radio_job02']) !!}
                            {!! Form::label('radio_job02',"准教授", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("occupation", "専任講師", $subsidy->occupation == "専任講師" ? true : false, ['class'=>'with-gap','id'=>'radio_job03']) !!}
                            {!! Form::label('radio_job03',"専任講師", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("occupation", "助教", $subsidy->occupation == "助教" ? true : false, ['class'=>'with-gap','id'=>'radio_job04']) !!}
                            {!! Form::label('radio_job04',"助教", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("occupation", "その他", ($subsidy->occupation == "教授" || $subsidy->occupation == "准教授" || $subsidy->occupation == "専任講師" || $subsidy->occupation == "助教") ? false : true, ['class'=>'with-gap','id'=>'radio_job05_other']) !!}
                            {!! Form::label('radio_job05_other',"その他", ['class' => "m-l-20"]) !!}
                            @else
                            {!! Form::radio("occupation", "教授", $prev_input['occupation'] == "教授" ? true : false, ['class'=>'with-gap', 'id'=>'radio_job01']) !!}
                            {!! Form::label('radio_job01',"教授") !!}

                            {!! Form::radio("occupation", "准教授", $prev_input['occupation'] == "准教授" ? true : false, ['class'=>'with-gap', 'id'=>'radio_job02']) !!}
                            {!! Form::label('radio_job02',"准教授", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("occupation", "専任講師", $prev_input['occupation'] == "専任講師" ? true : false, ['class'=>'with-gap','id'=>'radio_job03']) !!}
                            {!! Form::label('radio_job03',"専任講師", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("occupation", "助教", $prev_input['occupation'] == "助教" ? true : false, ['class'=>'with-gap','id'=>'radio_job04']) !!}
                            {!! Form::label('radio_job04',"助教", ['class' => "m-l-20"]) !!}

                            {!! Form::radio("occupation", "その他", ($prev_input['occupation'] == "教授" || $prev_input['occupation'] == "准教授" || $prev_input['occupation'] == "専任講師" || $prev_input['occupation'] == "助教") ? false : true, ['class'=>'with-gap','id'=>'radio_job05_other']) !!}
                            {!! Form::label('radio_job05_other',"その他", ['class' => "m-l-20"]) !!}
                            @endif


                            @if ($errors->has('occupation'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('occupation') }}</strong>
                            </div>
                            @endif
                            <div class="sonota">
                                <label><small>※その他を選択した場合</small></label>
                                @if(empty($prev_input['occupation']))
                                {!! Form::text('other_occupation', ($subsidy->occupation == "教授" || $subsidy->occupation == "准教授" || $subsidy->occupation == "専任講師" || $subsidy->occupation == "助教") ? null : $subsidy->occupation, array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("other_occupation") ? " is-invalid" : ""), 'placeholder' => '', 'id'=>'other_occupation')) !!}
                                @else
                                {!! Form::text('other_occupation', ($prev_input['occupation'] == "教授" || $prev_input['occupation'] == "准教授" || $prev_input['occupation'] == "専任講師" || $prev_input['occupation'] == "助教") ? null : $prev_input['occupation'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("other_occupation") ? " is-invalid" : ""), 'placeholder' => '', 'id'=>'other_occupation')) !!}
                                @endif
                            </div>

                            @if ($errors->has('other_occupation'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('other_occupation') }}</strong>
                            </div>
                            @endif
                        </div>
                    </section>

                    <div class="form-group">
                        <label>【 郵便番号 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            @php
                            $str_arr = empty($prev_input['zip_code']) ? explode ("-", $subsidy->zip_code) : explode ("-", $prev_input['zip_code']);
                            @endphp
                            {!! Form::text('zip_1', $str_arr[0], array('class' =>'has-padding has-border modal_boxSizing'.($errors->has("zip_code") ? " is-invalid" : ""), 'id' => 'ext_05', 'placeholder' => '例）150', 'maxlength' => '3', 'size' => '8')) !!}
                            {!! Form::label("-") !!}
                            {!! Form::text('zip_2', $str_arr[1], array('class' =>'has-padding has-border modal_boxSizing'.($errors->has("zip_code") ? " is-invalid" : ""), 'id' => 'ext_06', 'placeholder' => '例）0001', 'maxlength' => '4', 'size' => '8', 'onKeyUp' => 'AjaxZip3.zip2addr(\'zip_1\',\'zip_2\',\'address1\',\'address1\');')) !!}
                        </p>
                        {!! Form::hidden('zip_code', empty($prev_input['zip_code']) ? $subsidy->zip_code : $prev_input['zip_code']) !!}
                        @if ($errors->has('zip_code'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('zip_code') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>【 住所1 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('address1', empty($prev_input['address1']) ? $subsidy->address1 : $prev_input['address1'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("address1") ? " is-invalid" : ""), 'placeholder' => '例）東京都板橋区加賀')) !!}
                        </p>
                        @if ($errors->has('address1'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('address1') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>【 住所2 】</label>
                        <p class="fieldset">
                            {!! Form::text('address2', empty($prev_input['address2']) ? $subsidy->address2 : $prev_input['address2'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("address2") ? " is-invalid" : ""), 'placeholder' => '例）大学 棟名')) !!}
                        </p>
                        @if ($errors->has('address2'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('address2') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>【 研究テーマ名 】<span class="caution">*</span></label>
                        <p class="fieldset">
                            {!! Form::text('theme', empty($prev_input['theme']) ? $subsidy->theme : $prev_input['theme'], array('class' =>'full-width has-padding has-border modal_boxSizing signup-password'.($errors->has("theme") ? " is-invalid" : ""), 'placeholder' => '※全角40字以内', 'maxlength' => '40')) !!}
                        </p>
                        @if ($errors->has('theme'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('theme') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label>【 課題番号・キーワード 】<span class="caution">*</span></label>
                        <div class="tasknum">
                            @foreach($categories as $category)
                            <p>{{ $category->name }}：{{ $category->description }}</p>
                            @endforeach
                        </div>
                        <div class="form-group select-m clearfix">
                            @php
                            if(empty($prev_input['topic'])){
                                $topic = json_decode($subsidy->topic, true);
                            }else{
                                $topic = json_decode($prev_input['topic'], true);
                            }
                            @endphp
                            <select class="form-control show-tick" name="topic" data-show-subtext="true">
                                <option>選択してください</option>
                                @foreach($categories as $category)
                                <optgroup label="{{ $category->name }}">
                                    @foreach($category->keywords as $keyword)
                                    <option value="{{ $keyword->id }}" @if($topic['keyword']['id'] == $keyword->id) selected="selected" @endif>{{ $keyword->prefix }}_{{ $keyword->name }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('topic'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('topic') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div>
                        <label>【添付PDF】</label>
                        <section class="positionradio">
                            <div class="fieldset" id="application">
                                <div id="js-selectFile01">
                                    <p>様式１：申請書表紙</p>
                                    {!! Form::file('application', array('id' => 'js-upload01', 'style' => 'display:none')) !!}
                                    <button class="file">ファイルを選択</button>
                                    <span class="file-icon">未選択</span>
                                </div>
                            </div>
                            @if ($errors->has('application'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('application') }}</strong>
                            </div>
                            @endif

                            <div class="fieldset" id="attachment">
                                <div id="js-selectFile02">
                                    <p>様式２：申請書添付書類</p>
                                    {!! Form::file('attachment', array('id' => 'js-upload02', 'style' => 'display:none')) !!}
                                    <button class="file">ファイルを選択</button>
                                    <span class="file-icon">未選択</span>
                                </div>
                            </div>
                            @if ($errors->has('attachment'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('attachment') }}</strong>
                            </div>
                            @endif

                            <div class="fieldset" id="reference">
                                <div id="js-selectFile03">
                                    <p>参考論文ファイル</p>
                                    {!! Form::file('reference', array('id' => 'js-upload03', 'style' => 'display:none')) !!}
                                    <button class="file">ファイルを選択</button>
                                    <span class="file-icon">未選択</span>
                                </div>
                            </div>
                            @if ($errors->has('reference'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('reference') }}</strong>
                            </div>
                            @endif

                        </section>
                    </div>
                </section>
            </seciotn>
            <p class="fieldset button-box">
                {!! Form::submit('入力内容で更新', array('class' =>'full-width has-padding', 'id' => 'submit')) !!}
            </p>
        </div>
    </div>

    {!! Form::close() !!}

</div>
<datalist id="belong-candidate">
</datalist>

@endsection

@section('custom-script')
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

$('input[name=application]').on('change', function(){
    var self = $('#application');
    if (this.files[0].size/1024/1024 >= 5) {
        self.after('<div class="invalid-feedback application"><strong>PDFファイルは5MB未満にしてください。</strong></div>');
    }else{
        $('.application').remove();
    }
    toggleSubmit();
});

$('input[name=attachment]').on('change', function(){
    var self = $('#attachment');
    if (this.files[0].size/1024/1024 >= 5) {
        self.after('<div class="invalid-feedback attachment"><strong>PDFファイルは5MB未満にしてください。</strong></div>');
    }else{
        $('.attachment').remove();
    }
    toggleSubmit();
});

$('input[name=reference]').on('change', function(){
    var self = $('#reference');
    if (this.files[0].size/1024/1024 >= 5) {
        self.after('<div class="invalid-feedback reference"><strong>PDFファイルは5MB未満にしてください。</strong></div>');
    }else{
        $('.reference').remove();
    }
    toggleSubmit();
});

    checkOccupation();
    zip_code();
    birthday();

    $('input[type=radio][name=occupation]').on('change', function(){
        checkOccupation();
    });

    $('input[name=day]').keyup(function(){
        birthday();
    });

    $('input[name=month]').keyup(function(){
        birthday();
    });

    $('input[name=year]').keyup(function(){
        birthday();
    });

    $('input[name=zip_1]').keyup(function(){
        zip_code();
    });

    $('input[name=zip_2]').keyup(function(){
        zip_code();
    });

    function checkOccupation(){
        if ($("#radio_job05_other").prop("checked")) {
            $("#other_occupation").prop('disabled', false);
        }
        else{
            $("#other_occupation").val('');
            $("#other_occupation").prop('disabled', true);
        }
    }

    function birthday(){
        var day = $('input[name=day]').val();
        var month = $('input[name=month]').val();
        var year = $('input[name=year]').val();
        var birthday = year+'-'+month+'-'+day;
        $('input[name=birthday]').val(birthday);
    }

    function zip_code(){
        var zip_code = $('input[name=zip_1]').val()+'-'+$('input[name=zip_2]').val();
        $('input[name=zip_code]').val(zip_code);
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
