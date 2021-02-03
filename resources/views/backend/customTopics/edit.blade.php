@extends('backend.layouts.master')
@section('title')
応募管理画面
@endsection

@section('content')
<div class="row-clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>
                    課題分類を更新
                </h2>
            </div>
            <div class="body">

                {!! Form::open(['method' => 'put', 'route' => ['customTopics.update', $category->id]]) !!}
                
                @include('backend.layouts.partials.errors')


                <div class="form-group form-float">
                    <div class="form-line">
                        {!! Form::text('name', $category->name, array('class' =>'form-control')) !!}
                        {!! Form::label('name', '名称', array('class' => 'form-label')) !!}
                    </div>
                    <label class="error" for="name">
                        @if ($errors->has('name'))
                        {{ $errors->first('name') }}
                        @endif
                    </label>
                </div>

                {!! Form::submit('更新', array('class' =>'btn btn-primary waves-effect')) !!}

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')

{{Html::script('plugins/autosize/autosize.js')}}

@endsection

@section('custom-script')

{{Html::script('js/script.js')}}

@endsection
