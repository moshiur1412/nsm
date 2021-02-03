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
					野口遵賞応募リスト
					<small><strong>応募件数</strong>: {{ count($awards) }}</small>
				</h2>
			</div>
			<div class="body">
				<form name="zips" action="{{ route('downloadAwardFiles') }}" method="POST">
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


				</input>


				<div class="table-responsive">

					<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						<thead>
							<tr>
								<th>選択</th>
								<th>受付No</th>
								<th>審査分類</th>
								<th>名前</th>
								<th>所属機関名</th>
								<th>職</th>
								<th>課題</th>
								<!-- <th>キーワード</th> -->
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
								<th>職</th>
								<th>課題</th>
								<!-- <th>キーワード</th> -->
								<th>テーマ</th>
							</tr>
						</tfoot>
						<tbody>

							<tr>

								@foreach ($awards as $award)
								<td>
									<input class="chk chk_{{$award->id}}" type="checkbox" id="checkColumn_{{$award->id}}" />
									<label for="checkColumn_{{$award->id}}"></label>

									<input class="chk chk_{{$award->id}}" type="checkbox" name="files_id[]" value="{{$award->id}}">
									<input class="chk chk_{{$award->id}}" type="checkbox" name="files_created[]" value="{{$award->created_at}}">
									<input class="chk chk_{{$award->id}}" id="mer_path_{{$award->id}}" type="checkbox" name="files_pdf[]" value="{{ $award->attachment_path }}"/>
									<input class="chk chk_{{$award->id}}" type="checkbox" name="files_name[]" value="{{$award->receipt}}_award">

								</td>
								<td> {{ $award->receipt}} </td>
								<td> {{ isset($award->custom_topic) ? $award->custom_topic->name : ''}} </td>
								<td> {{ $award->name }} </td>
								<td> {{ $award->belong_type_name }} </td>
								<td> {{ $award->occupation }} </td>
								<td> {{ $award->email }} </td>
								<!-- <td> {{ $award->keyword }} </td> -->
								<td> {{ $award->theme }} </td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</form>
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

	@foreach ($awards as $award)
	$("#checkColumn_{{$award->id}}").change(function () {
		$(".chk_{{$award->id}}").prop('checked', $(this).prop("checked"));
		$('#submit').prop("disabled", false);

		if ($('.chk_{{$award->id}}').filter(':checked').length < 1){

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
