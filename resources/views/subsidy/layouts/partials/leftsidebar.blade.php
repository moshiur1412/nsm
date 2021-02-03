<!-- menu -->
<section>
	<!-- Left Sidebar -->
	<aside id="leftsidebar" class="sidebar">

		<!-- User Info -->
		<div class="user-info">
			<div class="info-container">
				<div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{{ isset(Auth::user()->name) ? Auth::user()->name : ' ' }}}</div>
				<div class="email">{{{ isset(Auth::user()->email) ? Auth::user()->email : ' ' }}}</div>
			</div>
		</div>
		<!-- #User Info -->

		<!-- Menu -->
		<div class="menu">
			<ul class="list">
				<li class="header">メインメニュー</li>

				@if(isset($subsidy_global) ? $subsidy_global != null : '')
				<li class="{{Route::is('subsidyApply.show*') ? 'active' : ''}}"> <a href="{{route('subsidyApply.show', Auth::user()->id)}}"> <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>助成金応募</span> </a> </li>

				@php
				$sub_create_year = date('Y/02/17', strtotime($subsidy_global->created_at));
				$result_show_date = date('Y/m/d', strtotime("+12 months $sub_create_year"));
				$sub_result_year = date('Y', strtotime("+12 months $sub_create_year"));
				@endphp

				@if( time() >  strtotime($result_show_date) )
				<li class="{{Route::is('mypage.result*') ? 'active' : ''}}"> <a href="{{route('mypage.result')}}"> <i class="fa fa-balance-scale" aria-hidden="true"></i> <span>助成金採択結果</span> </a> </li>
				@else
				<li> <a href="#" data-toggle="modal" data-target="#subsidyModal"> <i class="fa fa-balance-scale" aria-hidden="true"></i> <span>助成金採択結果</span> </a> </li>
				@endif

				@elseif(isset($award_global) ? $award_global != null : '')

				<li class="{{Route::is('awardApply.show*') ? 'active' : ''}}"> <a href="{{route('awardApply.show', Auth::user()->id)}}"> <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>野口遵賞応募履歴</span> </a> </li>

				@php
				$aw_create_year = date('Y/02/17', strtotime($award_global->created_at));
				$result_show_date = date('Y/m/d', strtotime("+12 months $aw_create_year"));
				$aw_result_year = date('Y', strtotime("+12 months $aw_create_year"));
				@endphp

				@if( time() >  strtotime($result_show_date) )
				<li> <a href="{{Route::is('mypage.result*') ? 'active' : ''}}"> <a href="{{route('mypage.result')}}"> <i class="fa fa-balance-scale" aria-hidden="true"></i> <span>野口遵賞合否結果</span> </a> </li>
				@else
				<li> <a href="#" data-toggle="modal" data-target="#shitagauModal"> <i class="fa fa-balance-scale" aria-hidden="true"></i> <span>野口遵賞合否結果</span> </a> </li>
				@endif

				@else
				<li class="{{Route::is('subsidyApply.create*') ? 'active' : ''}}"> <a href="#" data-toggle="modal" data-target="#subsidyCreateModal" > <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>助成金応募</span> </a> </li>
				<li class="{{Route::is('awardApply.create*') ? 'active' : ''}}"> <a href="#" data-toggle="modal" data-target="#shitagauCreateModal"> <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>野口遵賞応募</span> </a> </li>

				@endif

				<li>
          <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block toggled">
            <i class="material-icons">assignment</i>
            <span>各種様式ダウンロード</span>
          </a>
          <ul class="ml-menu" style="display: none;">
            <li>
              <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                <span>野口遵助成金応募様式</span>
              </a>
              <ul class="ml-menu" style="display: none;">
                <li>
									<a href="{{ config('url.joseiyousikiDownload1')}}" class=" waves-effect waves-block">様式1</a>
                </li>
                <li>
									<a href="{{ config('url.joseiyousikiDownload2')}}" class=" waves-effect waves-block">様式2</a>
	              </li>
              </ul>
            </li>
            <li>
              <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                <span>野口遵賞応募様式</span>
              </a>
              <ul class="ml-menu" style="display: none;">
                <li>
                  <a href="{{ config('url.awardyousikiDownload')}}" class=" waves-effect waves-block">様式</a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
				<li>
					 <a onclick="logout()" data-type="logout" class=" waves-effect waves-block">
					   <i class="fa fa-sign-out" aria-hidden="true"></i>
					   <span>ログアウト</span>
					 </a>
			  </li>
			</ul>
		</div>
		<!-- #Menu -->

		<!-- Footer -->
		<div class="legal">
			<div class="copyright"> &copy; {{ date('Y') == 2019 ? date('Y') : '2019-'.date('Y') }} <a href="javascript:void(0);">公益財団法人 野口研究所</a>. </div>
			<div class="version"> <b>Version: </b> 1.0.0 </div>
		</div>
		<!-- #Footer -->

	</aside>
	<!-- #END# Left Sidebar -->
</section>

<div class="modal fade" id="subsidyCreateModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-noguchi">
				<h4 class="modal-title" id="">野口遵研究助成金の応募</h4>
			</div>
			<div class="modal-body">
			  <div class="body">
			    <p class="modal-p">ここから先は野口遵研究助成金の応募フォームになります。</p>
					<p>野口遵研究助成金の応募資格者の方は以下チェックボックスにチェックを入れて、<br>応募するボタンをクリックしてください。</p>
					<p><a href="https://noguchi-inst.sakura.ne.jp/subsidy/ja/youshiki_1.doc">応募様式1ダウンロード</a></p>
				  <p><a href="https://noguchi-inst.sakura.ne.jp/subsidy/ja/youshiki_2.doc">応募様式2ダウンロード</a></p>
			    <div class="row">
			      <div class="col-xs-8 p-t-5 m-t-20">
			        <input type="checkbox" name="subsidy-agree" id="subsidy-agree" class="filled-in chk-col-noguchi">
			        <label for="subsidy-agree">私は野口遵研究助成金の応募資格者です</label>
			      </div>
			    </div>
			  </div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="window.location.href='{{route('subsidyApply.create')}}'" class="btn bg-noguchi waves-effect subsidy-agree" data-dismiss="modal" disabled>応募する</button>
				<button type="button" class="btn btn-close waves-effect" data-dismiss="modal">戻る</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="shitagauCreateModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-noguchi">
				<h4 class="modal-title" id="">野口遵賞 応募</h4>
			</div>
			<div class="modal-body">
			  <div class="body">
			    <p class="modal-p">ここから先は野口遵賞の応募フォームになります。</p>
			    <p>野口遵賞の応募資格者の方は以下チェックボックスにチェックを入れて、<br>応募するボタンをクリックしてください。</p>
				  <p>※当年度の応募資格者は{!! date('Y', strtotime('-4 year')) !!}年・{!! date('Y', strtotime('-3 year')) !!}年度の野口遵助成金採択者のみになります。</p>
					<p><a href="https://www.noguchi.or.jp/subsidy/ja/youshiki_1.doc">応募様式ダウンロード</a></p>
			    <div class="row">
			      <div class="col-xs-8 p-t-5 m-t-20">
			        <input type="checkbox" name="shitagau-agree" id="shitagau-agree" class="filled-in chk-col-noguchi">
			        <label for="shitagau-agree">私は野口遵賞の応募資格者です</label>
			      </div>
			    </div>
			  </div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="window.location.href='{{route('awardApply.create')}}'" class="btn bg-noguchi waves-effect shitagau-agree" data-dismiss="modal" disabled>応募する</button>
				<button type="button" class="btn btn-close waves-effect" data-dismiss="modal">戻る</button>
			</div>
		</div>
	</div>
</div>

<!-- ログインモーダル -->
<div class="modal fade" id="subsidyModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-noguchi">
				<h4 class="modal-title" id="">結果はまだ表示できません</h4>
			</div>
			<div class="modal-body">
				<div class="body">
					<p class="modal-p">採択結果は{{ isset($sub_result_year) ? $sub_result_year : ''  }}年2月17日に公開されます</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-close waves-effect" data-dismiss="modal">戻る</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="shitagauModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-noguchi">
				<h4 class="modal-title" id="">結果はまだ表示できません</h4>
			</div>
			<div class="modal-body">
				<div class="body">
					<p class="modal-p">採択結果は{{ isset($aw_result_year) ? $aw_result_year : '' }}年2月17日に公開されます</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-close waves-effect" data-dismiss="modal">戻る</button>
			</div>
		</div>
	</div>
</div>
<!-- #END# menu -->
