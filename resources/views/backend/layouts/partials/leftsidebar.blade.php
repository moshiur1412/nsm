<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <!-- <div class="image">
            <h4><i class="fas fa-user col-white"></i></h4>
        </div> -->
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{{ isset(Auth::user()->name) ? Auth::user()->name : ' ' }}}</div>
            <div class="email">{{{ isset(Auth::user()->email) ? Auth::user()->email : ' ' }}}</div>
        </div>
    </div>
    <!-- #User Info -->

    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="header">ナビゲーション</li>

            @if(isset(Auth::user()->role))



            @if(Auth::user()->role == 1)

            <li class="{{Route::is('judgeSubsidyApplication*')? 'active':''}}">
                <a href="{{route('judgeSubsidyApplication')}}">
                    <i class="material-icons">assignment</i>
                    <span>助成金応募リスト</span>
                </a>
            </li>
            <li class="{{Route::is('judgeAwardApplication*') ? 'active' : ''}}">
                <a href="{{route('judgeAwardApplication')}}">
                    <i class="material-icons">school</i>
                    <span>野口遵賞応募リスト</span>
                </a>
            </li>
            @else
            <li class="{{Route::is('judgeSubsidyApplication*')? 'active':''}}">
                <a href="{{route('judgeSubsidyApplication')}}">
                    <i class="material-icons">assignment</i>
                    <span>助成金応募リスト</span>
                </a>
            </li>
            @endif
            @else
            <li class="{{Route::is('admins*')? 'active':''}}">
                <a href="{{route('admins.index')}}">
                    <i class="material-icons">person</i>
                    <span>管理者</span>
                </a>
            </li>
            <li class="{{Route::is('judges*')? 'active':''}}">
                <a href="{{route('judges.index')}}">
                    <i class="material-icons">person_outline</i>
                    <span>審査員</span>
                </a>
            </li>
            <li class="{{Route::is('applicants*')? 'active':''}}">
                <a href="{{route('applicants.index')}}">
                    <i class="material-icons">person_outline</i>
                    <span>ユーザー</span>
                </a>
            </li>

            <li class="{{Route::is('subsidyApplications*')? 'active':''}}">
                <a href="{{route('subsidyApplications.index')}}">
                    <i class="material-icons">assessment</i>
                    <span>助成金応募</span>
                </a>
            </li>

            <li class="{{Route::is('awardApplications*')? 'active':''}}">
                <a href="{{route('awardApplications.index')}}">
                    <i class="material-icons">account_balance</i>
                    <span>野口遵賞応募</span>
                </a>
            </li>

            <li class="{{Route::is('subsidyActionHistory*')? 'active':''}}">
                <a href="{{route('subsidyActionHistory')}}">
                    <i class="material-icons">history</i>
                    <span>助成金応募者更新履歴</span>
                </a>
            </li>
            <li class="{{Route::is('awardActionHistory*')? 'active':''}}">
                <a href="{{route('awardActionHistory')}}">
                    <i class="material-icons">history</i>
                    <span>野口遵賞応募者更新履歴</span>
                </a>
            </li>

            <li class="{{Route::is('customTopics*')? 'active':''}}">
                <a href="{{route('customTopics.index')}}">
                    <i class="material-icons">all_inclusive</i>
                    <span>審査用課題分類</span>
                </a>
            </li>

            <li class="{{Route::is('topics*')? 'active':''}}">
                <a href="{{route('topics.index')}}">
                    <i class="material-icons">grid_on</i>
                    <span>課題</span>
                </a>
            </li>

            <li class="{{Route::is('keywords*')? 'active':''}}">
                <a href="{{route('keywords.index')}}">
                    <i class="material-icons">library_books</i>
                    <span>キーワード</span>
                </a>
            </li>

            <li class="{{Route::is('topic.keyword')? 'active':''}}">
                <a href="{{route('topic.keyword')}}">
                    <i class="material-icons">library_books</i>
                    <span>課題キーワード関連付け</span>
                </a>
            </li>
            @endif

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
