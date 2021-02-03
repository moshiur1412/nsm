@extends('backend.layouts.master')
@section('title')
応募管理システム
@endsection

@section('extra-css')

{{ Html::style('plugins/bootstrap-select/css/bootstrap-select.css') }}

@endsection

@section('custom-css')
<style type="text/css">

</style>
@endsection

@section('content')
<div class="row-clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>野口遵賞申請書送信フォーム
                  <small><code>*</code>は必須項目です</small>
                </h2>
            </div>
            <div class="body clearfix">
                {!! Form::model($award, [
                    'method' => $award->exists ? 'put' : 'post', 'class' => 'demo-masked-input',
                    'route' => $award->exists ? ['awardApplications.update', $award->id] : ['awardApplications.store'],
                    'enctype' => 'multipart/form-data', 'files' => true, 'name'=>'check_edit'
                    ]) !!}

                    @include('backend.layouts.partials.errors')

                    @if ($award->id != "")
                      <a class="btn btn-danger mb"
                      href="{{ $downloadUrlList }}" download="{{ $award->application_path }}">
                      <i class="material-icons">file_download</i>
                      <span>申請書添付書類をダウンロード</span>
                      </a>
                    @endif

                    <h2 class="card-inside-title">【 氏名 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('name', null, array('class' =>'form-control '.($errors->has("name") ? "is-invalid" : ""), 'placeholder' => '例）山田 太郎')) !!}
                        </div>
                        <label class="error" for="name">
                            @if ($errors->has('name'))
                            {{ $errors->first('name') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 フリガナ 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('name_kana', null, array('class' =>'form-control '.($errors->has("name_kana") ? "is-invalid" : ""), 'placeholder' => '例）ヤマダ タロウ')) !!}
                        </div>
                        <label class="error" for="name_kana">
                            @if ($errors->has('name_kana'))
                            {{ $errors->first('name_kana') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 氏名（ローマ字） ※姓・名の順に記載（例1：yamada tarou）（例2：darwin charles) 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('name_alphabet', null, array('class' =>'form-control '.($errors->has("name_alphabet") ? "is-invalid" : ""), 'placeholder' => '例）yamada tarou')) !!}
                        </div>
                        <label class="error" for="name_alphabet">
                            @if ($errors->has('name_alphabet'))
                            {{ $errors->first('name_alphabet') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 生年月日 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('birthday', null, array('class' =>'form-control date '.($errors->has("name_alphabet") ? "is-invalid" : ""), 'placeholder' => '例）1990-01-01')) !!}
                        </div>
                        <label class="error" for="birthday">
                            @if ($errors->has('birthday'))
                            {{ $errors->first('birthday') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 所属区分 】<span class="col-red">*</span></h2>

                    <div class="form-group">
                        {!! Form::radio("belong_type_name", "大学", false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_affiliation01']) !!}
                        {!! Form::label('radio_affiliation01',"大学") !!}

                        {!! Form::radio("belong_type_name", "高専", false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_affiliation02']) !!}
                        {!! Form::label('radio_affiliation02',"高専", ['class' => "m-l-20"]) !!}

                        {!! Form::radio("belong_type_name", "その他", false, ['class'=>'with-gap radio-col-red','id'=>'radio_affiliation03']) !!}
                        {!! Form::label('radio_affiliation03',"その他", ['class' => "m-l-20"]) !!}
                        <label class="error" for="belong">
                            @if ($errors->has('belong_type_name'))
                            {{ $errors->first('belong_type_name') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 所属機関名 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('belongs', null, array('class' =>'form-control '.($errors->has("belongs") ? "is-invalid" : ""), 'placeholder' => '例）東京大学', 'list' => 'belong-candidate')) !!}
                        </div>
                        <label class="error" for="name_kana">
                            @if ($errors->has('belongs'))
                            {{ $errors->first('belongs') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 所属名 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('major', null, array('class' =>'form-control '.($errors->has("major") ? "is-invalid" : ""), 'placeholder' => '例）理学部')) !!}
                        </div>
                        <label class="error" for="name_kana">
                            @if ($errors->has('major'))
                            {{ $errors->first('major') }}
                            @endif
                        </label>
                    </div>

                    @if(!empty($award->occupation))
                    {!! Form::hidden('check_occupation', $award->occupation) !!}
                    @endif

                    <h2 class="card-inside-title">【 職 】<span class="col-red">*</span></h2>

                    <div class="form-group">
                        {!! Form::radio("occupation", "教授", $award->exists && $award->occupation == "教授" ? true : false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_job01']) !!}
                        {!! Form::label('radio_job01',"教授") !!}

                        {!! Form::radio("occupation", "准教授", $award->exists && $award->occupation == "准教授" ? true : false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_job02']) !!}
                        {!! Form::label('radio_job02',"准教授", ['class' => "m-l-20"]) !!}

                        {!! Form::radio("occupation", "専任講師", $award->exists && $award->occupation == "専任講師" ? true : false, ['class'=>'with-gap radio-col-red','id'=>'radio_job03']) !!}
                        {!! Form::label('radio_job03',"専任講師", ['class' => "m-l-20"]) !!}

                        {!! Form::radio("occupation", "助教", $award->exists && $award->occupation == "助教" ? true : false, ['class'=>'with-gap radio-col-red','id'=>'radio_job04']) !!}
                        {!! Form::label('radio_job04',"助教", ['class' => "m-l-20"]) !!}

                        {!! Form::radio("occupation", "その他", $award->exists && $award->occupation == "その他" ? true : false, ['class'=>'with-gap radio-col-red','id'=>'radio_job05_other']) !!}
                        {!! Form::label('radio_job05_other',"その他", ['class' => "m-l-20"]) !!}
                        <label class="error" for="occupation_other">
                            @if ($errors->has('occupation'))
                            {{ $errors->first('occupation') }}
                            @endif
                        </label>
                    </div>


                    <label><small>※その他を選択した場合</small></label>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('occupation_other', null, array('class' =>'form-control '.($errors->has("occupation_other") ? "is-invalid" : ""), 'placeholder' => '', 'id'=>'other_occupation')) !!}
                        </div>
                    </div>

                    <h2 class="card-inside-title">【 郵便番号 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('zip_code', null, array('onKeyUp' => 'AjaxZip3.zip2addr(this,\'\',\'address1\',\'address1\');','class' =>'form-control zip '.($errors->has("zip_code") ? "is-invalid" : ""), 'placeholder' => '例）150-0001')) !!}
                        </div>
                        <label class="error" for="zip_code">
                            @if ($errors->has('zip_code'))
                            {{ $errors->first('zip_code') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 住所1 】<span class="col-red">*</span></h2>

                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::textarea('address1', null, array('class'=>'form-control no-resize auto-growth '.($errors->has("address1") ? "is-invalid" : ""), 'placeholder' => '例）東京都板橋区加賀',  'rows' => '1')) !!}
                        </div>
                        <label class="error" for="address1">
                            @if ($errors->has('address1'))
                            {{ $errors->first('address1') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 住所2 】</h2>

                    <div class="form-group">
                        <div class="form-line">
                            {!! Form::textarea('address2', null, array('class'=>'form-control no-resize auto-growth '.($errors->has("address2") ? "is-invalid" : ""), 'placeholder' => '例）大学 棟名',  'rows' => '1')) !!}
                        </div>
                        <label class="error" for="address2">
                            @if ($errors->has('address2'))
                            {{ $errors->first('address2') }}
                            @endif
                        </label>
                    </div>

                    <h2 class="card-inside-title">【 テーマ 】<span class="col-red">*</span></h2>

                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('theme', null, array('class' =>'form-control '.($errors->has("theme") ? "is-invalid" : ""), 'placeholder' => '※全角40字以内','maxlength' => '40')) !!}
                        </div>
                        <label class="error" for="theme">
                            @if ($errors->has('theme'))
                            {{ $errors->first('theme') }}
                            @endif
                        </label>
                    </div>

                    {!! Form::label("【 助成金採択年度 】") !!}<span class="col-red">*</span>
                    <div class="form-line">
                        {!! Form::select("subsidy_granted_year", [''=>'選択してください', date('Y-m-d', strtotime('-4 year')) => date('Y', strtotime('-4 year')).'年度・野口遵研究助成金', date('Y-m-d', strtotime('-3 year')) => date('Y', strtotime('-3 year')).'年度・野口遵研究助成金'], $award->exists ? date('Y-m-d', strtotime($award->subsidy_granted_year)) : '選択してください', ['class'=>'form-control show-tick']) !!}
                    </div>

                    <h2 class="card-inside-title">【 添付PDF 】
                        @if (!$award->exists)
                        <span class="col-red">*</span>
                        @endif
                    </h2>
                    <p>申請書添付書類</p>
                    {!! Form::file('attachment', array('class' =>'btn btn-default waves-effect mb')) !!}

                    <p class="col-red">
                        @if ($errors->has('attachment'))
                        {{ $errors->first('attachment') }}
                        @endif
                    </p>

                    <!-- {!! Form::submit("入力内容の更新", ['class'=>'btn btn-primary waves-effect']) !!} -->
                    {!! Form::submit("入力内容の更新", array('class' =>'btn btn-primary waves-effect updb', 'form' => 'award_form')) !!}
                    {!! Form::close() !!}

                    @if ($award->id != "")
                    {!! Form::open(['route' => ['awardApplications.destroy', $award->id], 'method'=>'delete']) !!}
                    {!! Form::button('応募内容を削除', array('class' => 'btn btn-danger form_warning_sweet_alert delb', 'title'=>'警告', 'text'=>'この応募は削除されます', 'confirmButtonText'=>'削除', 'type'=>'button')) !!}
                    {!! Form::close() !!}
                    @endif

                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('extra-script')

    {{Html::script('plugins/autosize/autosize.js')}}
    {{Html::script('plugins/jquery-inputmask/jquery.inputmask.bundle.js')}}
    {{Html::script('plugins/jquery-form/jquery.form.js')}}
    <datalist id="belong-candidate">
    </datalist>
    @endsection

    @section('custom-script')

    {{Html::script('js/script.js')}}
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <script type="text/javascript">

        const post_size_over_msg = "PDFファイルは5MB未満にしてください。";
        function toggleSubmit() {
            let target = $("p:contains('"+post_size_over_msg+"')");
            if (target.length > 0) {
                $('input[type=submit]').prop('disabled', true);
            }else{
                $('input[type=submit]').prop('disabled', false);
            }
        }
        $('input[name=attachment]').on('change', function(){
            if (this.files[0].size/1024/1024 >= 5) {
                this.nextElementSibling.textContent = post_size_over_msg;
            }else{
                this.nextElementSibling.textContent = "";
            }
            toggleSubmit();
        });

        check_occupation = $('input[name=check_occupation]').val();
        if(check_occupation && (check_occupation != "教授" && check_occupation != "准教授" && check_occupation != "専任講師" && check_occupation != "助教")){
            $("#radio_job05_other").prop('checked', true);
            $("#other_occupation").val(check_occupation);
        }

        checkOccupation();

        $('input[type=radio][name=occupation]').on('change', function(){
            checkOccupation();
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

        // Initialize Date Input Mask
        var $demoMaskedInput = $('.demo-masked-input');

        $(function () {

            autosize($('textarea.auto-growth'));

            $demoMaskedInput.find('.date').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });

            $demoMaskedInput.find('.zip').inputmask('***-****', { placeholder: '___-____' });

        });

        $(function () {

            autosize($('textarea.auto-growth'));

            $demoMaskedInput.find('.date').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });

            $demoMaskedInput.find('.zip').inputmask('***-****', { placeholder: '___-____' });

        });

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
        //Disable multiple clicks
        $(function(){
          $('[type="submit"]').click(function(){
            $(this).prop('disabled',true);
            $(this).closest('form').submit();
          });
        });
    </script>

    @endsection
