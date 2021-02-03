@extends('backend.layouts.master')
@section('title')
応募管理システム
@endsection

@section('custom-css')
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
               <h2> {{$judge->exists ? "審査員更新" : "審査員登録"}}</h2>
           </div>
           <div class="body">
            {!! Form::model($judge, [
                'method' => $judge->exists ? 'put' : 'post',
                'route' => $judge->exists ? ['judges.update', $judge->id] : ['judges.store'],
                'enctype' => 'multipart/form-data', 'files' => true, 'name'=>'check_edit'
                ]) !!}

                @include('backend.layouts.partials.errors')

                <div class="form-group">
                    {!! Form::label("名前") !!}
                    <div class="form-line">
                        {!! Form::text("name", null, ['class'=>'form-control '.($errors->has("name") ? "is-invalid" : ""),'autocomplete'=>'off']) !!}
                    </div>
                    @if($errors->has('name'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('name')}}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label("email") !!}
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
                    {!! Form::label("審査員タイプ") !!}
                    <div class="form-line">
                        {{ Form::select('role', ["1"=>"助成金・遵賞の両方閲覧可", "2"=>"助成金のみ閲覧可"], $judge->exists ? $judge->role : 0, ['class' => 'form-control']) }}
                    </div>
                    @if($errors->has('role'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('role')}}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label("パスワード") !!}
                    <div class="form-line">
                        {!! Form::password("password", ['class'=>'form-control '.($errors->has("email") ? "is-invalid" : "")]) !!}
                    </div>
                    @if($errors->has('password'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password')}}</strong>
                    </div>
                    @endif
                </div>
                @if ($judge->exists)
                <div class="row clearfix">
                    <div class="col-sm-6">
                        <h2 class="card-inside-title">最終ログイン可能日</h2>
                        <div class="form-group form-float">
                            <div class="form-line">
                                {!! Form::text('login_expires_at', empty($judge->login_expires_at) ? date('Y-10-31') : date('Y-m-d', strtotime($judge->login_expires_at)), array('class' =>'form-control date '.($errors->has("login_expires_at") ? "is-invalid" : ""), 'placeholder' => '2020-03-01')) !!}
                            </div>
                            <label class="error" for="login_expires_at">
                                @if ($errors->has('login_expires_at'))
                                {{ $errors->first('login_expires_at') }}
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    {!! Form::checkbox("valid", "1", $judge->exists ? ($judge->valid==1 ? true : false):false, ['class'=>'with-gap radio-col-red', 'id'=>'judge_valid01']) !!}
                    {!! Form::label('judge_valid01',"有効") !!}
                </div>

                {!! Form::submit($judge->exists ? "更新" : "登録", ['class'=>'btn btn-primary waves-effect']) !!}

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')



@endsection

@section('custom-script')

{{Html::script('js/pages/tables/jquery-datatable.js')}}
{{Html::script('js/script.js')}}
<script type="text/javascript">
        /*$(document).ready( function () {
            $('#admins-list').DataTable();
        });*/
    </script>
    @endsection
