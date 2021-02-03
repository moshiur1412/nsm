<h2>■仮登録のお知らせ■</h2>
<br/>
Emailアドレス {{$user['user-email']}} への登録はまだ完了していません。下記のリンクへ飛び、登録を完了してください。
<br/>
<a href="{{route('app.user.verify', $user['_token']) }}">登録を完了する</a>

<p>※このメールは登録申し込みをいただいた際に自動的に送信されています。 このメールに返信することはできません。</p>
<p>※登録手続きをした覚えがないのに、このメールが届いた方はお手数ですがこのメールは破棄してください。</p>
<p class="align-center">公益財団法人 野口研究所　野口遵研究助成金　事務局</p>
<p class="align-center">josei@noguchi.or.jp </p>
