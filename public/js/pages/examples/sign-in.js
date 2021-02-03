$(function () {

  $.extend(jQuery.validator.messages, {
    email: "有効なメールアドレスを入力してください",
    required:"必須項目です"
});
    $('#sign_in').validate({
        highlight: function (input) {
            console.log(input);
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        }
    });
});
