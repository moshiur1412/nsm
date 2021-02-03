@extends('subsidy.layouts.master')

@section('title')
公益財団法人 野口研究所
@endsection

@section('content')

<div class="row clearfix">
	{!! Form::open(['method' => 'delete', 'route' => ['awardApply.destroy', $award->id], 'name' => 'inquiry_form', 'id' => 'Submit', 'class' => 'cd-form', 'role' => 'form']) !!}
	<div class="card">
		<div class="header">
			<h2>【応募履歴】野口遵賞</h2>
			<ul class="header-dropdown m-r--5"></ul>
		</div>
		<div class="box-form">
			@if($award->state < 3)
			<div class="notes">
				<p>あなたが応募した内容になります。
					※１：応募内容を修正したい場合は【編集する】ボタンをクリックしてください。<br>
				※２：誤って野口遵賞および野口研究助成金に応募した方は、【応募削除】をクリックして再度応募をしてください。</p>
			</div>
			@endif

			<seciotn class="registForm">
				<section class="basicForm">
					<table class="company">
						<tbody><tr>
							<th>氏名</th>
							<td>
								{{ $award->name }}
							</td>
						</tr>
						<tr>
							<th>フリガナ</th>
							<td>
								{{ $award->name_kana }}
							</td>
						</tr>
						<tr>
							<th>氏名（ローマ字）</th>
							<td>
								{{ $award->name_alphabet }}
							</td>
						</tr>
						<tr>
							<th>生年月日</th>
							<td>
								{{ $award->birthday }}
							</td>
						</tr>
						<tr>
							<th>所属区分</th>
							<td>
								{{ $award->belong_type_name }}
							</td>
						</tr>
						<tr>
							<th>所属機関名</th>
							<td>
								{{ $award->belongs }}
							</td>
						</tr>
						<tr>
							<th>所属名</th>
							<td>
								{{ $award->major}}
							</td>
						</tr>
						<tr>
							<th>職</th>
							<td>
								{{ $award->occupation }}
							</td>
						</tr>
						<tr>
							<th>郵便番号</th>
							<td>
								{{ $award->zip_code }}
							</td>
						</tr>
						<tr>
							<th>住所1</th>
							<td>
								{{ $award->address1 }}
							</td>
						</tr>
						<tr>
							<th>住所2</th>
							<td>
								{{ $award->address2 }}
							</td>
						</tr>
						<tr>
							<th>研究テーマ名</th>
							<td>
								{{ $award->theme }}
							</td>
						</tr>
						<tr>
							<th>助成金採択年度</th>
							<td>
								{{ date('Y',strtotime($award->subsidy_granted_year)) }}
							</td>
						</tr>
						<tr>
							<th>添付PDF 様式</th>
							<td>
								<a href="{{ $downloadUrlList  }}" download="{{ $award->attachment_path }}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;ダウンロード</a>
								<input type="hidden" name="ext17" value="{{ $award->attachment_path}}">
							</td>
						</tr>

					</tbody>
				</table>
			</section>
		</seciotn>
		@if($award->state < 3)
		<p class="fieldset CancellationButtonGroup">
			<input class="harf-width has-padding registBack {{ ($award->expiration_date <=  Carbon\Carbon::now()) ? 'disabled' : '' }} " type="button" onclick="window.location='{{ route('awardApply.edit', $award->id) }}'" value="編集する" >
			<input data-toggle="tooltip" data-placement="top" title="応募を削除しますか？" confirmButtonText="削除" type="submit" class="harf-width has-padding btn-danger form_warning_sweet_alert" value="応募削除" > </input>
		</p>
		@endif
	</div>
</div>
{!! Form::close() !!}
</div>

@endsection
