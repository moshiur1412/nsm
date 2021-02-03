@extends('subsidy.layouts.master')
@section('custom-css')
<style type="text/css" media="print">
html,body{
	height: auto;
	min-height: 0;
}
nav.navbar, aside#leftsidebar, .card .header, .box-form .notes{
	display: none;
}
.pfResult{
	box-shadow: none;
}
section.content{
	margin: 15px auto;
}
.m-t-b-80{
	margin: 80px 0 0;
}
</style>
@endsection
@section('title')
@if($subsidy != null)
野口研究助成金採択結果
@elseif($award != null)
野口遵賞採択結果
@endif
@endsection

@section('content')
<div class="row clearfix">
	<form class="cd-form" id="Submit" name="inquiry_form" action="input_comp.php" method="POST" enctype="multipart/form-data" role="form" autocomplete="off">
		<div class="card">
			<div class="header">
				<h2>
					@if($subsidy != null)
					野口研究助成金採択結果
					@elseif($award != null)
					野口遵賞採択結果
					@endif
				</h2>
				<ul class="header-dropdown m-r--5"></ul>
			</div>
			<div class="box-form">
				@if($record->is_granted > 0)
				<div class="notes">
				  <a class="print" href="javascript:void(0)" onclick="window.print();return false;">このページを印刷</a>
				</div>
				@endif
		    	<seciotn class="registForm">
		        	<section class="basicForm pfResult">
			            <div class="m-t-b-20">
					        <p class="align-right">
								@if($record->is_granted == 1)
								{!! date('Y') !!}年2月吉日
								@endif
								@if($record->is_granted == 2)
								{!! date('Y') !!}年2月
								@endif
							</p>
						</div>
						<div class="m-t-b-20">
					        <p>{!! $record->belongs !!}<br>&nbsp;&nbsp;&nbsp;&nbsp;{!! $record->name !!} 様</p>
						</div>
						<div class="m-t-b-20">
					        <p class="align-right">東京都板橋区加賀1丁目9番7号<br>公益財団法人 野口研究所<br>理事長 小林 宏史</p>
						</div>
						<div class="m-t-b-40">
							<h4 class="align-center">{!! $record->offer_year !!}
								@if($subsidy != null)
								年度野口遵研究助成金 審査結果のご通知
								@elseif($award != null)
								年度野口遵賞 審査結果のご通知
								@endif
							</h4>
						</div>
						<div class="m-t-b-40 theme">
							<ul>
								<li>受付番号</li>
								<li>{!! $record->receipt !!}</li>
							</ul>
							<ul>
								<li>応募テーマ</li>
								<li>{!! $record->theme !!}</li>
							</ul>
						</div>
						<div class="m-t-b-40">
							@if($record->is_granted == 2)
							<p>貴殿より申請がありました上記の応募テーマについて、当所選考委員会にて審議した結果、<br>
								残念ながら「不採択」となりましたのでお知らせいたします。<br>
								ご応募いただきまして有り難うございました。<br>
								次回のご応募をお待ちしております。</p>
							@endif
							@if($record->is_granted == 1)
							<p>貴殿より申請がありました上記の応募テーマについて、当所選考委員会にて審議した結果、<br>
								採択されたことをお知らせいたします。<br>
								後日正式な通知を郵送にて送付いたします。</p>
							@endif
						</div>
					</section>
				</seciotn>
			</div>
		</div>
	</form>
</div>
@endsection
