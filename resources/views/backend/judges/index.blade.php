@extends('backend.layouts.master')
@section('title')
応募管理システム
@endsection

@section('extra-css')
{{ Html::style('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}
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
                    審査員
                </h2>
            </div>
            <div class="body">
                <a href="{{ route('judges.create') }}" class="btn btn-success waves-effect mb">
                    <i class="material-icons">add</i>
                    <span>新規登録</span>
                </a>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>項番</th>
                                <th>名前</th>
                                <th>Email</th>
                                <th>最終ログイン日</th>
                                <th>最終ログイン可能日</th>
                                <th>有効/無効</th>
                                <th>変更</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>項番</th>
                                <th>名前</th>
                                <th>Email</th>
                                <th>最終ログイン日</th>
                                <th>最終ログイン可能日</th>
                                <th>有効/無効</th>
                                <th>変更</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($judges as $judge)

                            <tr>
                                <td>{{ $loop->iteration}}</td>
                                <td>{{ $judge->name }}</td>
                                <td>{{ $judge->email }}</td>
                                <td>{{ isset($judge->last_login_at)? date('Y-m-d', strtotime($judge->last_login_at)) : '' }}</td>
                                <td>{{ isset($judge->login_expires_at)? date('Y-m-d', strtotime($judge->login_expires_at)) : '' }}</td>
                                <td>{{ $judge->valid==1 ? "有効" :"無効" }}</td>
                                <td>
                                    {!! Form::open(['route' => ['judges.destroy', $judge->id], 'method'=>'delete']) !!}
                                    <a class="btn btn-success" href="{{route('judges.edit', $judge->id)}}" title="Show/Edit Subsidy"><i class="material-icons">edit</i></a>
                                    {!! Form::button('<i class="material-icons">delete</i>', array('class' => 'btn btn-danger form_warning_sweet_alert', 'title'=>'警告', 'text'=>'この応募は削除されます', 'confirmButtonText'=>'削除', 'type'=>'submit')) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')

{{Html::script('plugins/jquery-datatable/jquery.dataTables.js')}}
{{Html::script('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/jszip.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}

@endsection

@section('custom-script')

{{Html::script('js/pages/tables/jquery-datatable.js')}}

{{Html::script('js/script.js')}}
<script type="text/javascript">

</script>
@endsection
