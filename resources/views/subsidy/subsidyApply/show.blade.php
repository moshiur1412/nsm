@extends('subsidy.layouts.master')

@section('title')
公益財団法人 野口研究所
@endsection

@section('content')

<div class="row clearfix">

	{!! Form::open(['method' => 'delete', 'route' => ['subsidyApply.destroy', $subsidy->id], 'name' => 'inquiry_form', 'id' => 'Submit', 'class' => 'cd-form', 'role' => 'form']) !!}

	<div class="card">
		<div class="header">
			<h2>【応募履歴】野口遵研究助成金</h2>
			<ul class="header-dropdown m-r--5"></ul>
		</div>
		<div class="box-form">
			@if($subsidy->state < 3)
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
							<td> {{ $subsidy->name}}
							</td>
						</tr>
						<tr>
							<th>フリガナ</th>
							<td>
								{{ $subsidy->name_kana}}
							</td>
						</tr>
						<tr>
							<th>氏名（ローマ字）</th>
							<td>
								{{ $subsidy->name_alphabet}}
							</td>
						</tr>
						<tr>
							<th>生年月日</th>
							<td>
								{{ $subsidy->birthday}}
							</td>
						</tr>
						<tr>
							<th>所属区分</th>
							<td>
								{{ $subsidy->belong_type_name}}
							</td>
						</tr>
						<tr>
							<th>所属機関名</th>
							<td>
								{{ $subsidy->belongs}}
							</td>
						</tr>
						<tr>
							<th>所属名</th>
							<td>
								{{ $subsidy->major}}
							</td>
						</tr>
						<tr>
							<th>職</th>
							<td>
								{{ $subsidy->occupation}}
							</td>
						</tr>
						<tr>
							<th>郵便番号</th>
							<td>
								{{ $subsidy->zip_code}}
							</td>
						</tr>
						<tr>
							<th>住所1</th>
							<td>
								{{ $subsidy->address1}}
							</td>
						</tr>
						<tr>
							<th>住所2</th>
							<td>
								{{ $subsidy->address2}}
							</td>
						</tr>
						<tr>
							<th>研究テーマ名</th>
							<td>
								{{ $subsidy->theme}}
							</td>
						</tr>
						<tr>
							<th>キーワード</th>
							<td>
								@php
								$topic = json_decode($subsidy['topic'], true);
								@endphp
								{{ $topic['topic']['name'].': '.$topic['keyword']['prefix'].'-'.$topic['keyword']['name'] }}
							</td>
						</tr>
						<tr>
							<th>添付PDF 様式１</th>
							<td>
								<a href="{{ $downloadUrlList[0]  }}" download><i class="fa fa-download" aria-hidden="true"></i>&nbsp;ダウンロード</a>
							</td>
						</tr>
						<tr>
							<th>添付PDF 様式２</th>
							<td>
								<a href="{{ $downloadUrlList[1]  }}" download><i class="fa fa-download" aria-hidden="true"></i>&nbsp;ダウンロード</a>
							</td>
						</tr>
						<tr>
							<th>添付PDF 参考論文ファイル</th>
							<td>
								<a href="{{ $downloadUrlList[3]  }}" download><i class="fa fa-download" aria-hidden="true"></i>&nbsp;ダウンロード</a>
							</td>
						</tr>
					</tbody></table>
				</section>
			</seciotn>
			@if($subsidy->state < 3)
			<p class="fieldset CancellationButtonGroup">
				<input class="harf-width has-padding registBack {{ ($subsidy->expiration_date <=  Carbon\Carbon::now()) ? 'disabled' : '' }} " type="button" onclick="window.location='{{ route('subsidyApply.edit', $subsidy->id) }}'" value="編集する" >
				{!! Form::submit('応募削除', array('class' => 'harf-width has-padding btn-danger form_warning_sweet_alert', 'title'=>'警告', 'text'=>'この応募は削除されます', 'confirmButtonText'=>'削除', 'cancelButtonText'=>'戻る','data-toggle'=>'modal', 'data-target'=>'#deleteModal')) !!}
			</p>
			@endif
		</div>
	</div>
	{!! Form::close() !!}
</div>

@endsection
