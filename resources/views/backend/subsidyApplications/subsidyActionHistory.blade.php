@extends('backend.layouts.master')
@section('title')
応募管理画面
@endsection

@section('extra-css')

{{ Html::style('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}
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
                    助成金応募者更新履歴
                </h2>
            </div>
            <div class="body">
                <div class="row clearfix">

                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>更新日時</th>
                                <th>応募者</th>
                                <th>更新内容</th>

                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>更新日時</th>
                                <th>応募者</th>
                                <th>更新内容</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($subsidy_actions as $subsidy_action)
                            <tr>
                                <td>{{$subsidy_action->created_at}}</td>
                                <td>{!! '<a href="'.route('subsidyApplications.edit', $subsidy_action->subsidy->id).'">'.$subsidy_action->subsidy->name.'</a>' !!}</td>
                                <td>{{$subsidy_action->action}}</td>
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

{{Html::script('js/script.js')}}
{{Html::script('js/pages/tables/jquery-datatable.js')}}
<script type="text/javascript">

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });


</script>
<style type="text/css">
[data-href] {
    cursor: pointer;
}
</style>
@endsection
