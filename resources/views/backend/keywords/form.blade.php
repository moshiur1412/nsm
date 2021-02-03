@extends('backend.layouts.master')
@section('title')
応募管理画面
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
             <h2> {{$keyword->exists ? "キーワード更新" : "キーワード登録"}}</h2>
         </div>
         <div class="body">
            {!! Form::model($keyword, [
                'method' => $keyword->exists ? 'put' : 'post',
                'route' => $keyword->exists ? ['keywords.update', $keyword->id] : ['keywords.store'],
                'enctype' => 'multipart/form-data', 'files' => true, 'name'=>'check_edit'
                ]) !!}
                
                @include('backend.layouts.partials.errors')

                <div class="form-group">
                    {!! Form::label("プレフィックス") !!}
                    <div class="form-line">
                        {!! Form::text("prefix", null, ['class'=>'form-control '.($errors->has("prefix") ? "is-invalid" : "")]) !!}
                    </div>
                    @if($errors->has('prefix'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('prefix')}}</strong>
                    </div>
                    @endif
                </div>


                <div class="form-group">
                    {!! Form::label("キーワード") !!}
                    <div class="form-line">
                        {!! Form::text("name", null, ['class'=>'form-control '.($errors->has("prefix") ? "is-invalid" : "")]) !!}
                    </div>
                    @if($errors->has('name'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('name')}}</strong>
                    </div>
                    @endif
                </div>


                {!! Form::submit($keyword->exists ? "更新" : "登録", ['class'=>'btn btn-primary waves-effect']) !!}

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

</script>
@endsection
