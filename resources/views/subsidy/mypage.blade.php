@extends('subsidy.layouts.master')

@section('title')
公益財団法人 野口研究所
@endsection

@section('content')

<div class="row clearfix">

	<form class="cd-form" id="Submit">
		<div class="card">
			<div class="header">
				<h2>マイページ</h2>
				<ul class="header-dropdown m-r--5"></ul>
			</div>

			<div class="box-form clearfix">
				<div class="notes">
					<p>野口遵研究助成金および野口遵賞の応募ページです。<br>
					      応募要件をご確認の上、以下ボタンから申請を進めて下さい。
					      ※なお今年度の野口遵賞の応募資格者は{!! date('Y', strtotime('-4 year')) !!}年・{!! date('Y', strtotime('-3 year')) !!}年野口遵助成金採択者になります。
					</p>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="card rate-wrap-box mypage">
						@if($subsidy == null)
						<a href="#" data-toggle="modal" data-target="#subsidyCreateModal">

							<div class="header bg-noguchi">
								<h2>
									<p>助成金に応募する</p>
								</h2>
							</div>
						</a>
						@else
						<a href="{{route('subsidyApply.show', Auth::user()->id)}}">
							<div class="header bg-noguchi">
								<h2>
									<p>Subsidy Details</p>
								</h2>
							</div>
						</a>
						@endif

					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<div class="card rate-wrap-box mypage">
						@if($award == null)
						<a href="#" data-toggle="modal" data-target="#shitagauCreateModal">
							<div class="header bg-noguchi">
								<h2>
									<p>野口遵賞に応募する</p>
								</h2>
							</div>
						</a>
						@else
						<a href="{{route('awardApply.show', Auth::user()->id)}}">
							<div class="header bg-noguchi">
								<h2>
									<p>Award Details</p>
								</h2>
							</div>
						</a>
						@endif

					</div>
				</div>
			</div>
		</div>
	</form>
</div>

@endsection
