// 1
$('#js-selectFile01').on('click', 'button', function () {
    $('#js-upload01').click();
    return false;
});

$('#js-upload01').on('change', function() {
    //選択したファイル情報を取得し変数に格納
    var file = $(this).prop('files')[0];
    //アイコンを選択中に変更
    $('#js-selectFile01').find('.file-icon').addClass('select').html('選択中');
    //未選択→選択の場合（.filenameが存在しない場合）はファイル名表示用の<div>タグを追加
    if(!($('.filename01').length)){
        $('#js-selectFile01').append('<div class="filename01"></div>');
    };
    //ファイル名を表示
    $('.filename01').html('ファイル名：' + file.name);
});

//2
$('#js-selectFile02').on('click', 'button', function () {
    $('#js-upload02').click();
    return false;
});

$('#js-upload02').on('change', function() {
    //選択したファイル情報を取得し変数に格納
    var file = $(this).prop('files')[0];
    //アイコンを選択中に変更
    $('#js-selectFile02').find('.file-icon').addClass('select').html('選択中');
    //未選択→選択の場合（.filenameが存在しない場合）はファイル名表示用の<div>タグを追加
    if(!($('.filename02').length)){
        $('#js-selectFile02').append('<div class="filename02"></div>');
    };
    //ファイル名を表示
    $('.filename02').html('ファイル名：' + file.name);
});

//3
$('#js-selectFile03').on('click', 'button', function () {
    $('#js-upload03').click();
    return false;
});

$('#js-upload03').on('change', function() {
    //選択したファイル情報を取得し変数に格納
    var file = $(this).prop('files')[0];
    //アイコンを選択中に変更
    $('#js-selectFile03').find('.file-icon').addClass('select').html('選択中');
    //未選択→選択の場合（.filenameが存在しない場合）はファイル名表示用の<div>タグを追加
    if(!($('.filename03').length)){
        $('#js-selectFile03').append('<div class="filename03"></div>');
    };
    //ファイル名を表示
    $('.filename03').html('ファイル名：' + file.name);
});

$('#subsidy-agree').prop('checked', false);
$('#shitagau-agree').prop('checked', false);

$('#subsidy-agree').on('click', function(){
    if($(this).prop('checked')){
        $('.subsidy-agree').prop("disabled", false);
    }else{
        $('.subsidy-agree').prop("disabled", true);
    }
});

$('.btn-close').on('click', function(){
    $('#subsidy-agree').prop('checked', false);
    $('.subsidy-agree').prop("disabled", true);
});

$('#shitagau-agree').on('click', function(){
    if($(this).prop('checked')){
        $('.shitagau-agree').prop("disabled", false);
    }else{
        $('.shitagau-agree').prop("disabled", true);
    }
});

$('.btn-close').on('click', function(){
    $('#shitagau-agree').prop('checked', false);
    $('.shitagau-agree').prop("disabled", true);
});
