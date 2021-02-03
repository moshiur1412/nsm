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
             <h2> {{$admin->exists ? "管理者更新" : "管理者登録"}}</h2>
         </div>
         <div class="body">
            {!! Form::model($admin, [
                'method' => $admin->exists ? 'put' : 'post',
                'route' => $admin->exists ? ['admins.update', $admin->id] : ['admins.store'],
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
                    {!! Form::label("パスワード") !!}
                    <div class="form-line">
                        {!! Form::password("password", ['class'=>'form-control '.($errors->has("name") ? "is-invalid" : "")]) !!}
                    </div>
                    @if($errors->has('password'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('password')}}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::checkbox("valid", "1", $admin->exists ? ($admin->valid==1 ? true : false):false, ['class'=>'with-gap radio-col-red', 'id'=>'admin_valid01']) !!}
                    {!! Form::label('admin_valid01',"有効") !!}

                </div>

                {!! Form::submit($admin->exists ? "更新" : "登録", ['class'=>'btn btn-primary waves-effect']) !!}

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
