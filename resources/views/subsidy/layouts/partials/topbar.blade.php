<!-- Top Bar -->
<!-- Top Bar -->
<nav class="navbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
			<a href="javascript:void(0);" class="bars"></a> <a class="navbar-brand" href="{{ config('url.home') }}/">公益財団法人 野口研究所</a> </div>
			<div class="collapse navbar-collapse js-sweetalert" id="navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<!-- Call Search -->
				<!--<li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a>
				</li>-->
				<!-- #END# Call Search -->

				@guest
				<!--log in-->
				<li class="dropdown">
					<a href="javascript:void(0);" role="button" data-toggle="modal" data-target="#loginModal">
						<i class="fa fa-sign-in" aria-hidden="true"></i>
					</a>
				</li>
				<!--log in-->
				@if (Route::has('register'))
				<li class="nav-item">
					<a class="nav-link" href="#">{{ __('Register') }}</a>
				</li>
				@endif
				@else


				<!--log out-->
				<li class="dropdown">
					<a href="{{ route('app.logout') }}" onclick="logout()" role="button" data-type="logout">
						<i class="fa fa-sign-out" aria-hidden="true"></i>
					</a>
				</li>
				<script>
					function logout(){
						event.preventDefault();
						swal({
							title: 'ログアウトしますか？',
							type: 'warning',
							showCancelButton: true,
							confirmButtonColor: "#d9534f",
							confirmButtonText: 'ログアウト',
							cancelButtonText: '戻る',
							closeOnConfirm: false,
							closeOnCancel: true
						},
						function(isConfirm){
							if (isConfirm) {
								document.getElementById('logout-form').submit();
							} else {
								// swal('Cancelled', 'You are still signed in!', 'info');
							}
						});
					}
				</script>
				<form id="logout-form" action="{{ route('app.logout') }}" method="POST" style="display: none;">
					@csrf
				</form>
				<!--log out-->
				@endguest




			</ul>
		</div>
	</div>
	<div class="nav-underLine"></div>
</nav>
<!-- #Top Bar -->




<!-- ログインモーダル -->
<!-- <div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header bg-noguchi">
				<h4 class="modal-title" id="defaultModalLabel">ログイン</h4>
			</div>
			<div class="modal-body">

				<div class="body">
					<form>
						<label for="email_address">ID（Emailアドレス）</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" id="email_address" class="form-control" placeholder="Enter your email address">
							</div>
						</div>
						<label for="password">パスワード</label>
						<div class="form-group">
							<div class="form-line">
								<input type="password" id="password" class="form-control" placeholder="Enter your password">
							</div>
						</div>

						<input type="checkbox" id="remember_me" class="filled-in">
						<label for="remember_me">パスワードを記憶する</label>
						<br>
						<button type="button" class="btn btn-noguchi m-t-15 waves-effect">ログイン</button>

					</form>
					<div class="m-t-30 passwordReset">
						パスワードを忘れた方は<a href="javascript:void(0);" data-toggle="modal" data-target="#passwordReset">こちら</a>

					</div>
				</div>

			</div>
			<div class="modal-footer bg-noguchi">
				<button type="button" class="btn btn-white waves-effect border-noguchi" data-dismiss="modal">戻る</button>
			</div>
		</div>
	</div>
</div> -->


<!-- パスワードリセットモーダル -->
<!-- <div class="modal fade" id="passwordReset" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header bg-red">
					<h4 class="modal-title" id="defaultModalLabel">パスワードをリセットします。</h4>
				</div>
				<div class="modal-body">

					<div class="body js-sweetalert">
						<div class="m-b-30">
							<p>お客様のメールアドレスにパスワードリセット用のメールを送信します。<br>ご登録いただいたメールアドレスを入力してください。</p>
						</div>
						<form>
							<label for="email_address">ID（Emailアドレス）</label>
							<div class="form-group">
								<div class="form-line">
									<input type="text" id="email_address" class="form-control" placeholder="emailアドレスを入力してください。">
								</div>
							</div>
							<button type="button" class="btn btn-danger m-t-15 waves-effect" data-type="passwordResetSuccess">メール送信（OK）</button>
							<button type="button" class="btn btn-danger m-t-15 waves-effect" data-type="passwordResetNG">メール送信（NG）</button>
						</form>
						<div class="m-t-30 passwordReset">
							※メールアドレスに誤りがあると、リセット用のメールが送信されません。<br>
							※@xxxxx.xxxからのメールの受信許可をお願いします。

						</div>
					</div>

				</div>
				<div class="modal-footer bg-red">
					<button type="button" class="btn btn-white border-red waves-effect" data-dismiss="modal">戻る</button>
				</div>
			</div>
		</div>
	</div> -->	<!-- #END# Top Bar -->
