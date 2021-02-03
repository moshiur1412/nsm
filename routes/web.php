<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['middleware' => 'auth.very_basic'], function() {

Route::prefix('/back')->group(function(){

	Route::get('login', 'Backend\Auth\LoginController@showLoginForm')->name('back.login');
	Route::post('login', 'Backend\Auth\LoginController@login')->name('back.login');
	Route::post('logout', 'Backend\Auth\LoginController@logout')->name('back.logout');

	/*--- Admins Route ---*/
	Route::resource('admins', 'Backend\AdminController');

	/*--- Judges Route ---*/
	Route::resource('judges', 'Backend\JudgeController');

	/*--- Applicant Route ---*/
	Route::resource('applicants', 'Backend\ApplicantController');

	/*--- Keywords Route ---*/
	Route::resource('keywords', 'Backend\KeywordController');

	/*--- Topics Route ---*/
	Route::resource('topics', 'Backend\TopicController');

	/*--- Topics & Keyword Route ---*/
	Route::get('keyword', 'Backend\TopicController@keyword')->name('topic.keyword');
	Route::post('sortKeyword', 'Backend\TopicController@sortKeyword')->name('categories.sortKeyword');

	/*--- CustomTopics Route ---*/
	Route::resource('customTopics', 'Backend\CustomTopicController');

	/*--- SubsidyApplications Route ---*/
	Route::resource('subsidyApplications','Backend\SubsidyApplicationController');
	Route::prefix('subsidyApplications')->group(function(){
		Route::post('update/customTopic', 'Backend\SubsidyApplicationController@updateCustomTopic')->name('subsidyApplication.customTopic');
		Route::post('update/grantPrimarySelected', 'Backend\SubsidyApplicationController@updateGrantPrimarySelected')->name('subsidyApplication.grantPrimarySelected');
		Route::post('update/grantPrimaryCanceled', 'Backend\SubsidyApplicationController@updateGrantPrimaryCanceled')->name('subsidyApplication.grantPrimaryCanceled');
		Route::post('update/grantSelected', 'Backend\SubsidyApplicationController@updateGrantSelected')->name('subsidyApplication.grantSelected');
		Route::post('update/rejectSelected', 'Backend\SubsidyApplicationController@updateRejectSelected')->name('subsidyApplication.rejectSelected');
		Route::post('sendMail', 'Backend\SubsidyApplicationController@sendMail')->name('subsidyApplication.sendMail');
		Route::post('exportCSV', 'Backend\SubsidyApplicationController@exportCSV')->name('subsidyApplication.exportCSV');
		Route::post('renumber', 'Backend\SubsidyApplicationController@renumber')->name('subsidyApplication.renumber');
		Route::post('relate', 'Backend\SubsidyApplicationController@relate')->name('subsidyApplication.relate');
	});

	/*--- Judge Subsidies & Awards Route ---*/
	Route::get('judgeSubsidyApplication', 'Backend\JudgeApplicationController@judgeSubsidyApplication')->name('judgeSubsidyApplication');
	Route::post('downloadSubsidyFiles', 'Backend\JudgeApplicationController@downloadSubsidyFiles')->name('downloadSubsidyFiles');
	Route::get('judgeAwardApplication', 'Backend\JudgeApplicationController@judgeAwardApplication')->name('judgeAwardApplication');
	Route::post('downloadAwardFiles', 'Backend\JudgeApplicationController@downloadAwardFiles')->name('downloadAwardFiles');

	/*--- SubsidyActionHistory Route ---*/
	Route::get('/subsidyActionHistory', 'Backend\SubsidyApplicationController@subsidyActionHistory')->name('subsidyActionHistory');

	/*--- Award Application Route ---*/
	Route::resource('awardApplications', 'Backend\AwardApplicationController');
	Route::prefix('awardApplications')->group(function(){
		Route::post('update/grantSelected', 'Backend\AwardApplicationController@updateGrantSelected')->name('awardApplication.grantSelected');
		Route::post('update/rejectSelected', 'Backend\AwardApplicationController@updateRejectSelected')->name('awardApplication.rejectSelected');
		Route::post('sendMail', 'Backend\AwardApplicationController@send_mail')->name('awardApplication.sendMail');
		Route::get('download/{download}', 'Backend\AwardApplicationController@download')->name('awardApplication.download');
		Route::post('update/customTopic', 'Backend\AwardApplicationController@updateCustomTopic')->name('awardApplication.customTopic');
		Route::post('exportCSV', 'Backend\AwardApplicationController@exportCSV')->name('awardApplication.exportCSV');
		Route::post('renumber', 'Backend\AwardApplicationController@renumber')->name('awardApplication.renumber');
		Route::post('relate', 'Backend\AwardApplicationController@relate')->name('awardApplication.relate');
	});
	/*--- AwardActionHistory Route ---*/
	Route::get('/awardyActionHistory', 'Backend\AwardApplicationController@awardActionHistory')->name('awardActionHistory');
});

Route::prefix('/app')->group(function(){

	/*--- Registration Routes ---*/
	Route::get('register', 'Subsidy\Auth\RegisterController@showRegistrationForm')->name('app.register');
	Route::post('register', 'Subsidy\Auth\RegisterController@register')->name('app.register');
	Route::get('user/verify/{token}', 'Subsidy\Auth\RegisterController@verifyUser')->name('app.user.verify');

	/*--- Login Routes ---*/
	Route::get('login', 'Subsidy\Auth\LoginController@showLoginForm')->name('app.login');
	Route::post('login', 'Subsidy\Auth\LoginController@login')->name('app.login');
	Route::post('logout', 'Subsidy\Auth\LoginController@logout')->name('app.logout');


	/*--- MyPage Routes ---*/
	Route::get('mypage', 'Subsidy\MyPageController@index')->name('mypage.index');
	Route::get('result', 'Subsidy\MyPageController@result')->name('mypage.result');

	/*--- SubsidyApplies Routes ---*/
	Route::resource('subsidyApply', 'Subsidy\SubsidyApplyController');
	Route::post('subsidyApply/confirm', 'Subsidy\SubsidyApplyController@confirm')->name('subsidyApply.confirm');
	Route::put('subsidyApply/edit_confirm/{id}', 'Subsidy\SubsidyApplyController@editConfirm')->name('subsidyApply.edit.confirm');
	Route::post('subsidyApply/cancel', 'Subsidy\SubsidyApplyController@cancel')->name('subsidyApply.cancel');

	/*--- AwardApplies Routes ---*/
	Route::resource('awardApply', 'Subsidy\AwardApplyController');
	Route::post('awardApply/confirm', 'Subsidy\AwardApplyController@confirm')->name('awardApply.confirm');
	Route::put('awardApply/edit_confirm/{id}', 'Subsidy\AwardApplyController@editConfirm')->name('awardApply.edit.confirm');
	Route::post('awardApply/cancel', 'Subsidy\AwardApplyController@cancel')->name('awardApply.cancel');
	Route::get('downloadFTPFile/{id}','Subsidy\AwardApplyController@downloadFTPFile')->name('awardApply.download');

});
