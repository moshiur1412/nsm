@extends('backend.layouts.master')
@section('title')
応募管理画面
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
                <h2> {{$applicant->exists ? "ユーザー更新" : "ユーザー登録"}}</h2>
            </div>
            <div class="body">
                {!! Form::model($applicant, [
                    'method' => $applicant->exists ? 'put' : 'post',
                    'route' => $applicant->exists ? ['applicants.update', $applicant->id] : ['applicants.store'],
                    'enctype' => 'multipart/form-data', 'files' => true, 'name'=>'check_edit'
                    ]) !!}

                    @include('backend.layouts.partials.errors')

                    <input type="hidden" name="id" value="{{$applicant->exists ? $applicant->id : null}}">

                    <div class="form-group">
                        <label>【 氏名 】<span class="col-red">*</span class="col-red"></label>
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
                        <label>【 フリガナ 】<span class="col-red">*</span></label>
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
                        <label>【 所属区分 】<span class="col-red">*</span></label>
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
                        <label>【 所属機関名 】<span class="col-red">*</span></label>
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
                        <label>【 所属名 】<span class="col-red">*</span></label>
                        <div class="form-line">
                            {!! Form::text('major', null, array('class' =>'form-control modal_boxSizing signup-password'.($errors->has("major") ? " is-invalid" : ""), 'placeholder' => '例）理学部')) !!}
                        </div>
                        @if ($errors->has('major'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('major') }}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label> Email <span class="col-red">*</span></label>
                        <div class="form-line">
                            {!! Form::text("email", null, ['class'=>'form-control '.($errors->has("email") ? "is-invalid" : ""),'autocomplete'=>'off']) !!}
                        </div>
                        @if($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email')}}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label> パスワード <span class="col-red">*</span></label>
                        <div class="form-line">
                            {!! Form::password("password", ['class'=>'form-control '.($errors->has("password") ? "is-invalid" : "")]) !!}
                        </div>
                        @if($errors->has('password'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password')}}</strong>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Form::checkbox("valid", "1", !$applicant->exists || $applicant->valid==1? true:false, ['class'=>'with-gap radio-col-red', 'id'=>'applicant_valid01']) !!}
                        {!! Form::label('applicant_valid01',"有効") !!}
                    </div>

                    {!! Form::submit($applicant->exists ? "更新" : "登録", ['class'=>'btn btn-primary waves-effect']) !!}

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

    <datalist id="belong-candidate">
    </datalist>

    @endsection

    @section('custom-script')

    {{-- {{Html::script('js/pages/tables/jquery-datatable.js')}} --}}
    {{Html::script('js/script.js')}}

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

    @endsection
