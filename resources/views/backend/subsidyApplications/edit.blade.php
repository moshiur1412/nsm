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
                <h2>
                    応募内容編集
                    <small>
                        <strong>応募者:</strong> {{ $subsidy->name }},
                        <strong>Email:</strong> {{ isset($subsidy->user->email)? $subsidy->user->email : '' }},
                        <strong>審査用分類:</strong> {{ empty($subsidy->custom_topic_id) ? '未分類' : $subsidy->custom_topic->name }},
                        <strong>受付番号:</strong> {{ $subsidy->receipt }},
                        <strong>状態:</strong>
                        @if($subsidy->is_granted == 0)
                        {!! '<span class="label label-warning ml">未審査</span>' !!}
                        @elseif($subsidy->is_granted == 1)
                        {!! '<span class="label label-success ml">採択</span>' !!}
                        @elseif($subsidy->is_granted == 2)
                        {!! '<span class="label label-danger ml">不採択</span>' !!}
                        @endif
                        <span class="label label-info ml">{{ $states[$subsidy->state] }}</span>
                    </small>
                </h2>
            </div>
            <div class="body clearfix">

                <a class="btn btn-danger mb"
                href="{{ $downloadUrlList[0] }}" download="{{ $subsidy->application_path }}">
                <i class="material-icons">file_download</i>
                <span>申請書をダウンロード</span>
            </a>
            <a class="btn btn-danger mb"
            href="{{ $downloadUrlList[1] }}" download="{{ $subsidy->attachment_path }}">
            <i class="material-icons">file_download</i>
            <span>申請書添付書類をダウンロード</span>
        </a>
        <a class="btn btn-danger mb"
        href="{{ $downloadUrlList[2] }}" download="{{ $subsidy->merged_path }}">
        <i class="material-icons">file_download</i>
        <span>審査用結合ファイルをダウンロード</span>
    </a>
    <a class="btn btn-danger mb"
    href="{{ $downloadUrlList[3] }}" download="{{ $subsidy->reference_path }}">
    <i class="material-icons">file_download</i>
    <span>参考論文ファイルをダウンロード</span>
</a>

<h2 class="card-inside-title">応募詳細</h2>

{!! Form::open(['method' => 'put', 'route' => ['subsidyApplications.update', $subsidy->id], 'name' => 'check_edit', 'id' => 'subsidy_form', 'class' => 'demo-masked-input', 'enctype' => 'multipart/form-data']) !!}

@include('backend.layouts.partials.errors')

<h2 class="card-inside-title">【 氏名 】<span class="col-red">*</span></h2>

<div class="form-group form-float">
    <div class="form-line">
        {!! Form::text('name', $subsidy->name, array('class' =>'form-control '.($errors->has("name") ? "is-invalid" : ""), 'placeholder' => '例）山田 太郎')) !!}
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
        {!! Form::text('name_kana',  $subsidy->name_kana, array('class' =>'form-control '.($errors->has("name_kana") ? "is-invalid" : ""), 'placeholder' => '例）ヤマダ タロウ')) !!}
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
        {!! Form::text('name_alphabet', $subsidy->name_alphabet, array('class' =>'form-control '.($errors->has("name_alphabet") ? "is-invalid" : ""), 'placeholder' => '例）yamada tarou')) !!}
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
        {!! Form::text('birthday', date('Y-m-d', strtotime($subsidy->birthday)), array('class' =>'form-control date '.($errors->has("birthday") ? "is-invalid" : ""), 'placeholder' => '例）1990-01-01')) !!}
    </div>
    <label class="error" for="birthday">
        @if ($errors->has('birthday'))
        {{ $errors->first('birthday') }}
        @endif
    </label>
</div>


<h2 class="card-inside-title">【 所属区分 】<span class="col-red">*</span></h2>

<div class="form-group">
    {!! Form::radio("belong_type_name", "大学", $subsidy->belong_type_name == "大学" ? true : false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_affiliation01']) !!}
    {!! Form::label('radio_affiliation01',"大学") !!}

    {!! Form::radio("belong_type_name", "高専", $subsidy->belong_type_name == "高専" ? true : false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_affiliation02']) !!}
    {!! Form::label('radio_affiliation02',"高専", ['class' => "m-l-20"]) !!}

    {!! Form::radio("belong_type_name", "その他", $subsidy->belong_type_name == "その他" ? true : false, ['class'=>'with-gap radio-col-red','id'=>'radio_affiliation03']) !!}
    {!! Form::label('radio_affiliation03',"その他", ['class' => "m-l-20"]) !!}
    <label class="error" for="belong_type_name">
        @if ($errors->has('belong_type_name'))
        {{ $errors->first('belong_type_name') }}
        @endif
    </label>
</div>

<h2 class="card-inside-title">【 所属機関名 】<span class="col-red">*</span></h2>

<div class="form-group form-float">
    <div class="form-line">
        {!! Form::text('belongs', $subsidy->belongs, array('class' =>'form-control '.($errors->has("belongs") ? "is-invalid" : ""), 'placeholder' => '例）東京大学', 'list' => 'belong-candidate')) !!}
    </div>
    <label class="error" for="belongs">
        @if ($errors->has('belongs'))
        {{ $errors->first('belongs') }}
        @endif
    </label>
</div>

<h2 class="card-inside-title">【 所属名 】<span class="col-red">*</span></h2>

<div class="form-group form-float">
    <div class="form-line">
        {!! Form::text('major', $subsidy->major, array('class' =>'form-control '.($errors->has("major") ? "is-invalid" : ""), 'placeholder' => '例）理学部')) !!}
    </div>
    <label class="error" for="major">
        @if ($errors->has('major'))
        {{ $errors->first('major') }}
        @endif
    </label>
</div>

<h2 class="card-inside-title">【 職 】<span class="col-red">*</span></h2>
<div class="form-group">
    {!! Form::radio("occupation", "教授", $subsidy->occupation == "教授" ? true : false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_job01']) !!}
    {!! Form::label('radio_job01',"教授") !!}

    {!! Form::radio("occupation", "准教授", $subsidy->occupation == "准教授" ? true : false, ['class'=>'with-gap radio-col-red', 'id'=>'radio_job02']) !!}
    {!! Form::label('radio_job02',"准教授", ['class' => "m-l-20"]) !!}

    {!! Form::radio("occupation", "専任講師", $subsidy->occupation == "専任講師" ? true : false, ['class'=>'with-gap radio-col-red','id'=>'radio_job03']) !!}
    {!! Form::label('radio_job03',"専任講師", ['class' => "m-l-20"]) !!}

    {!! Form::radio("occupation", "助教", $subsidy->occupation == "助教" ? true : false, ['class'=>'with-gap radio-col-red','id'=>'radio_job04']) !!}
    {!! Form::label('radio_job04',"助教", ['class' => "m-l-20"]) !!}

    {!! Form::radio("occupation", "その他", ($subsidy->occupation == "教授" || $subsidy->occupation == "准教授" || $subsidy->occupation == "専任講師" || $subsidy->occupation == "助教") ? false : true, ['class'=>'with-gap radio-col-red','id'=>'radio_job05_other']) !!}
    {!! Form::label('radio_job05_other',"その他", ['class' => "m-l-20"]) !!}
</div>
<label class="error" for="occupation">
    @if ($errors->has('occupation'))
    {{ $errors->first('occupation') }}
    @endif
</label>
<label><small>※その他を選択した場合</small></label>

<div class="form-group form-float">
    <div class="form-line">
        {!! Form::text('occupation_other', ($subsidy->occupation == "教授" || $subsidy->occupation == "准教授" || $subsidy->occupation == "専任講師" || $subsidy->occupation == "助教") ? null : $subsidy->occupation, array('class' =>'form-control '.($errors->has("occupation") ? "is-invalid" : ""), 'placeholder' => '', 'id'=>'occupation_other')) !!}
    </div>
    <label class="error" for="occupation_other">
        @if ($errors->has('occupation_other'))
        {{ $errors->first('occupation_other') }}
        @endif
    </label>
</div>

<h2 class="card-inside-title">【 郵便番号 】<span class="col-red">*</span></h2>

<div class="form-group form-float">
    <div class="form-line">
        {!! Form::text('zip_code', $subsidy->zip_code, array('class' =>'form-control zip '.($errors->has("zip_code") ? "is-invalid" : ""), 'maxlength' => '8', 'size' => '10', 'onKeyUp' => 'AjaxZip3.zip2addr(this,\'\',\'address1\',\'address1\');', 'placeholder' => '例）150-0001')) !!}
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
        {!! Form::textarea('address1', $subsidy->address1, array('class'=>'form-control no-resize auto-growth '.($errors->has("address1") ? "is-invalid" : ""), 'placeholder' => '例）東京都板橋区加賀',  'rows' => '1')) !!}
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
        {!! Form::textarea('address2', $subsidy->address2, array('class'=>'form-control no-resize auto-growth '.($errors->has("address2") ? "is-invalid" : ""), 'placeholder' => '例）大学 棟名',  'rows' => '1')) !!}
    </div>
    <label class="error" for="address2">
        @if ($errors->has('address2'))
        {{ $errors->first('address2') }}
        @endif
    </label>
</div>

<h2 class="card-inside-title">【 研究テーマ名 】<span class="col-red">*</span></h2>

<div class="form-group form-float">
    <div class="form-line">
        {!! Form::text('theme', $subsidy->theme, array('class' =>'form-control '.($errors->has("theme") ? "is-invalid" : ""), 'placeholder' => '※全角40字以内','maxlength' => '40')) !!}
    </div>
    <label class="error" for="theme">
        @if ($errors->has('theme'))
        {{ $errors->first('theme') }}
        @endif
    </label>
</div>

<h2 class="card-inside-title">【 課題番号・キーワード 】<span class="col-red">*</span></h2>

<div class="tasknum">
    @foreach($categories as $category)
    <p>{{ $category->name }}：{{ $category->description }}</p>
    @endforeach
</div>
@php
$topic = json_decode($subsidy->topic, true)
@endphp
<div class="form-group form-float">
    <select class="form-control show-tick" name="topic" data-show-subtext="true">
        <option value='0'>選択してください</option>
        @foreach($categories as $category)
        <optgroup label="{{ $category->name }}">
            @foreach($category->keywords as $keyword)
            <option value="{{ $keyword->id }}" {{ Arr::get($topic, 'keyword.id') ==  $keyword->id ? 'selected="selected"' : '' }}>{{ $keyword->prefix }}_{{ $keyword->name }}</option>
            @endforeach
        </optgroup>
        @endforeach
    </select>
    <label class="error" for="topic">
        @if ($errors->has('topic'))
        {{ $errors->first('topic') }}
        @endif
    </label>
</div>

<div class="row clearfix">
                   <div class="col-sm-6">
                    {!! Form::checkbox('valid', 1, $subsidy->valid == 1 ? 'checked' : '', ['id' => 'valid']) !!}
                    {!! Form::label('valid', '有効') !!}
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-sm-6">
                    <h2 class="card-inside-title">応募締切日</h2>
                    <div class="form-group form-float">
                        <div class="form-line">
                            {!! Form::text('expiration_date', empty($subsidy->expiration_date) ? date('Y-10-31') : date('Y-m-d', strtotime($subsidy->expiration_date)), array('class' =>'form-control date '.($errors->has("expiration_date") ? "is-invalid" : ""), 'placeholder' => 'Update Expiration Date')) !!}
                        </div>
                        <label class="error" for="expiration_date">
                            @if ($errors->has('expiration_date'))
                            {{ $errors->first('expiration_date') }}
                            @endif
                        </label>
                    </div>
                </div>
            </div>

            <h2 class="card-inside-title">【 添付PDF 】</h2>

            <p>様式１：申請書表紙</p>
            {!! Form::file('application', array('class' =>'btn btn-default waves-effect')) !!}
            <p class="col-red">
                @if ($errors->has('application'))
                {{ $errors->first('application') }}
                @endif
            </p>

            <p>様式２：申請書添付書類</p>
            {!! Form::file('attachment', array('class' =>'btn btn-default waves-effect')) !!}
            <p class="col-red">
                @if ($errors->has('attachment'))
                {{ $errors->first('attachment') }}
                @endif
            </p>

            <p>参考論文ファイル</p>
            {!! Form::file('reference', array('class' =>'btn btn-default waves-effect')) !!}
            <p class="col-red">
                @if ($errors->has('reference'))
                {{ $errors->first('reference') }}
                @endif
            </p>

            {!! Form::submit('入力内容の更新', array('class' =>'btn btn-primary waves-effect updb', 'form' => 'subsidy_form')) !!}
            {!! Form::close() !!}
            {!! Form::open(['route' => ['subsidyApplications.destroy', $subsidy->id], 'method'=>'delete']) !!}
            {!! Form::button('応募内容を削除', array('class' => 'btn btn-danger form_warning_sweet_alert delb', 'title'=>'警告', 'text'=>'この応募は削除されます', 'confirmButtonText'=>'削除', 'type'=>'button')) !!}
            {!! Form::close() !!}

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

    $('input[name=application]').on('change', function(){
        if (this.files[0].size/1024/1024 >= 5) {
            this.nextElementSibling.textContent = post_size_over_msg;
        }else{
            this.nextElementSibling.textContent = "";
        }
        toggleSubmit();
    });

    $('input[name=attachment]').on('change', function(){
        if (this.files[0].size/1024/1024 >= 5) {
            this.nextElementSibling.textContent = post_size_over_msg;
        }else{
            this.nextElementSibling.textContent = "";
        }
        toggleSubmit();
    });

    $('input[name=reference]').on('change', function(){
        if (this.files[0].size/1024/1024 >= 5) {
            this.nextElementSibling.textContent = post_size_over_msg;
        }else{
            this.nextElementSibling.textContent = "";
        }
        toggleSubmit();
    });

    checkOccupation();

    $('input[type=radio][name=occupation]').on('change', function(){
        checkOccupation();
    });

    function checkOccupation(){
        if ($("#radio_job05_other").prop("checked")) {
            $("#occupation_other").prop('disabled', false);
        }
        else{
            $("#occupation_other").val('');
            $("#occupation_other").prop('disabled', true);
        }
    }

        // Initialize Date Input Mask
        var $demoMaskedInput = $('.demo-masked-input');

        $(function () {

            autosize($('textarea.auto-growth'));

            $demoMaskedInput.find('.date').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });

            $demoMaskedInput.find('.zip').inputmask('***-****', { placeholder: '___-___' });

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

    </script>

    @endsection
