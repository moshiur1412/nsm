@extends('backend.layouts.master')
@section('title')
応募管理システム
@endsection

@section('extra-css')
{{ Html::style('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}
@endsection

@section('content')

<div class="row-clearfix">
	<div class="col-md-12">
		<div class="card">
			<div class="header">
				<h2>
					助成金応募リスト
					<small><strong>応募件数</strong>: {{ count($subsidies) }}</small>
				</h2>
			</div>
			<div class="body">
				<form name="zips" action="{{ route('downloadSubsidyFiles') }}" method="POST">
					@csrf
					@method('POST')

					<label class="btn btn-warning waves-effect mb" id="btnMarkAllUsers" for="markAllUsers">
						<i class="material-icons">select_all</i><span>全選択</span>
					</label>
					<input class="chk" type="checkbox" id="markAllUsers"  />

					<input style="margin-left: 8px; padding: 8px;" class="btn btn-success waves-effect mb" type="submit" id="submit" name="createzip" value="ダウンロード" />
					<div class="notes">
					  <p>※一度に大量のファイルをダウンロードするとエラーが発生します。<br>
						その際はお手数ですが、対象を減らして再度お試しください。<br>
					  </p>
					</div>
					<div class="table-responsive">

						<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
							<thead>
								<tr>
									<th>選択</th>
									<th>受付No</th>
									<th>審査分類</th>
									<th>名前</th>
									<th>所属機関名</th>
									<th>所属名</th>
									<th>職</th>
									<th>課題</th>
									<th>キーワード</th>
									<th>テーマ</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>選択</th>
									<th>受付No</th>
									<th>審査分類</th>
									<th>名前</th>
									<th>所属機関名</th>
									<th>所属名</th>
									<th>職</th>
									<th>課題</th>
									<th>キーワード</th>
									<th>テーマ</th>
								</tr>
							</tfoot>
							<tbody>

								<tr>
									@foreach ($subsidies as $subsidy)
									<td>
										<input class="chk chk_{{$subsidy->id}}" type="checkbox" id="checkColumn_{{$subsidy->id}}" />
										<label for="checkColumn_{{$subsidy->id}}"> </label>

										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_id[]" value="{{$subsidy->id}}">
										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_created[]" value="{{$subsidy->created_at}}">
										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_pdf[]" value="{{ $subsidy->merged_path }}"/>
										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_name[]" value="{{$subsidy->receipt}}_subsidy">

										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_id[]" value="{{$subsidy->id}}">
										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_created[]" value="{{$subsidy->created_at}}">
										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_pdf[]" value="{{ $subsidy->reference_path }}"/>
										<input class="chk chk_{{$subsidy->id}}" type="checkbox" name="files_name[]" value="{{$subsidy->receipt}}_reference">
									</td>
									<td> {{ $subsidy->receipt}} </td>
									<td> {{ isset($subsidy->custom_topic) ? $subsidy->custom_topic->name : ''}} </td>
									<td> {{ $subsidy->name }} </td>
									<td> {{ $subsidy->belongs }} </td>
									<td> {{ $subsidy->major }} </td>
									<td> {{ $subsidy->occupation }} </td>
									@php
									$topic = json_decode($subsidy['topic'], true);
									@endphp
									<td> {{ $topic['topic']['name'] }} </td>
									<td> {{ $topic['keyword']['name'] }} </td>
									<td> {{ $subsidy->theme }} </td>
								</tr>
								@endforeach

							</tbody>
						</table>


					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('extra-script')


{{Html::script('plugins/bootstrap-select/js/bootstrap-select.js')}}
{{Html::script('plugins/autosize/autosize.js')}}
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

	// Disable Default Submit Button
	$('#submit').prop("disabled", true);

	$("#markAllUsers").change(function () {
		$(".chk").prop('checked', $(this).prop("checked"));
		$('#submit').prop("disabled", false);

		$('#btnMarkAllUsers').empty().append('<i class="material-icons">clear</i><span>選択解除</span>');
		$('#btnMarkAllUsers').addClass('btn-danger').removeClass('btn-warning');

		if ($('.chk').filter(':checked').length < 1){
			$('#btnMarkAllUsers').empty().append('<i class="material-icons">select_all</i><span>全選択</span>');
			$('#btnMarkAllUsers').addClass('btn-warning').removeClass('btn-danger');
			$('#submit').attr('disabled',true);}
		});

	@foreach ($subsidies as $subsidy)
	$("#checkColumn_{{$subsidy->id}}").change(function () {
		$(".chk_{{$subsidy->id}}").prop('checked', $(this).prop("checked"));
		$('#submit').prop("disabled", false);

		if ($('.chk_{{$subsidy->id}}').filter(':checked').length < 1){
			if ($('.chk').filter(':checked').length >1){
				$('#submit').attr('disabled',false);
			} else{
				$('#submit').attr('disabled',true);
			}
		}
	});
	@endforeach

</script>
@endsection
