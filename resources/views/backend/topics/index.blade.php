@extends('backend.layouts.master')
@section('title')
応募管理画面
@endsection

@section('extra-css')
{{ Html::style('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}
@endsection

@section('custom-css')
<style type="text/css">
    /*.admins-table{
        margin-top:2%;
        margin-left: 1%;
        margin-right: 1%;
    }

    .page-utility{
        margin-left: 1%;
        margin-right: 1%;
        }*/
    </style>
    @endsection

    @section('content')
    <div class="row-clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2>
                        課題
                    </h2>
                </div>
                <div class="body">
                    <a href="{{ route('topics.create') }}" class="btn btn-success waves-effect mb">
                        <i class="material-icons">add</i>
                        <span>新規登録</span>
                    </a>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                                <tr>
                                    <th>項番</th>
                                    <th>プレフィックス</th>
                                    <th>説明</th>
                                    <th>変更</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>項番</th>
                                    <th>プレフィックス</th>
                                    <th>説明</th>
                                    <th>変更</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($topics as $topic)

                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $topic->name }}</td>
                                    <td>{{ $topic->description }}</td>
                                    <td>
                                        {!! Form::open(['route' => ['topics.destroy', $topic->id], 'method'=>'delete']) !!}
                                        <a class="btn btn-success" href="{{ route('topics.edit', [$topic->id]) }}" title="Show/Edit Category"><i class="material-icons">edit</i></a>
                                        {!! Form::button('<i class="material-icons">delete</i>', array('class' => 'btn btn-danger form_warning_sweet_alert', 'title'=>'分類を削除', 'text'=>'この課題分類は削除されます', 'confirmButtonText'=>'削除', 'type'=>'submit')) !!}
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
        /*$(document).ready( function () {
            $('#admins-list').DataTable();
        });*/
    </script>
    @endsection
