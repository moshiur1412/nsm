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
             <h2> {{$topic->exists ? "課題を更新" : "課題を登録"}}</h2>
         </div>
         <div class="body">
            {!! Form::model($topic, [
                'method' => $topic->exists ? 'put' : 'post',
                'route' => $topic->exists ? ['topics.update', $topic->id] : ['topics.store'],
                'enctype' => 'multipart/form-data', 'files' => true, 'name'=>'check_edit'
                ]) !!}
                
                @include('backend.layouts.partials.errors')

                <div class="form-group">
                    {!! Form::label("プレフィックス") !!}
                    <div class="form-line">
                        {!! Form::text("name", null, ['class'=>'form-control '.($errors->has("name") ? "is-invalid" : "")]) !!}
                    </div>
                    @if($errors->has('name'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('name')}}</strong>
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label("説明") !!}
                    <div class="form-line">
                        {!! Form::textarea("description", null, ['rows' => 4, 'cols' => 50, 'class'=>'form-control '.($errors->has("description") ? "is-invalid" : "")] ) !!}
                    </div>
                    @if($errors->has('description'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('description')}}</strong>
                    </div>
                    @endif
                </div>

                {!! Form::submit($topic->exists ? "更新" : "登録", ['class'=>'btn btn-primary waves-effect']) !!}

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
