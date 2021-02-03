
	<!-- Jquery Core Js -->
    {{Html::script('plugins/jquery/jquery.min.js')}}

	<!-- Bootstrap Core Js -->
    {{Html::script('plugins/bootstrap/js/bootstrap.js')}}

	<!-- Select Plugin Js -->
    {{Html::script('plugins/bootstrap-select/js/bootstrap-select.js')}}

	<!-- Slimscroll Plugin Js -->
    {{Html::script('plugins/jquery-slimscroll/jquery.slimscroll.js')}}

	<!-- Waves Effect Plugin Js -->
    {{Html::script('plugins/node-waves/waves.js')}}
    {{Html::script('plugins/editable-table/mindmup-editabletable.js')}}

	<!-- Jquery CountTo Plugin Js -->
    {{Html::script('plugins/jquery-countto/jquery.countTo.js')}}

	<!-- Morris Plugin Js -->
    {{Html::script('plugins/raphael/raphael.min.js')}}
    {{Html::script('plugins/morrisjs/morris.js')}}

	<!-- ChartJs -->
    {{Html::script('plugins/chartjs/Chart.bundle.js')}}

	<!-- LightBox Plugin Js -->
    {{Html::script('plugins/lightbox/js/lightbox.js')}}

    {{Html::script('plugins/sweetalert/sweetalert.min.js')}}

	<!-- Light Gallery Plugin Js -->
    {{Html::script('plugins/light-gallery/js/lightgallery-all.js')}}

	<!-- Bootstrap Notify Plugin Js -->
    {{Html::script('plugins/bootstrap-notify/bootstrap-notify.js')}}

	<!-- Sparkline Chart Plugin Js -->
    {{Html::script('plugins/jquery-sparkline/jquery.sparkline.js')}}

    {{Html::script('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}

    <!-- PDF Js -->
    {{Html::script('plugins/pdfjs/build/pdf.js')}}
    {{Html::script('plugins/pdfjs/web/viewer.js')}}
    {{Html::script('plugins/pdfjs/build/pdf.worker.js')}}

	<!-- Custom Js -->
    {{Html::script('js/pages/ui/notifications.js')}}
    {{Html::script('js/pages/medias/image-gallery.js')}}
    {{Html::script('js/admin.js')}}
    {{Html::script('js/pages/ui/dialogs.js')}}
    {{-- Html::script('js/pages/index.js') --}}

    {{Html::script('js/jquery.validate.min.js')}}

    {{Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/localization/messages_ja.js')}}

<script type="text/javascript">

    //  Sweet alert for warning

    $('.form_warning_sweet_alert').on('click',function(e){
      e.preventDefault();
      var form = $(this).parents('form');
      var title = $(this).attr('title');
      var text = $(this).attr('text');
      var confirmButtonText = $(this).attr('confirmButtonText');
      swal({
          title: title,
          text: text,
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: confirmButtonText,
          cancelButtonText: '戻る',
          confirmButtonColor: '#ff9600',
          closeOnConfirm: false
      }, function(isConfirm){
          if (isConfirm) form.submit();
      });
    });

    // Showing Notifications

    var loop_time = 0;

    if ($(".info_messages").length) {
      $( ".info_messages" ).each(function() {
        notificationLoop(loop_time, $(this), "#", "info", "bottom", "right", 20, 20, 'animated fadeInDown', 'animated fadeOutUp');
        loop_time++;
      });
    }
    if ($(".success_messages").length) {
      $( ".success_messages" ).each(function() {
        notificationLoop(loop_time, $(this), "#", "success", "bottom", "right", 20, 20, 'animated fadeInDown', 'animated fadeOutUp');
        loop_time++;
      });
    }
    if ($(".warning_messages").length) {
      $( ".warning_messages" ).each(function() {
        notificationLoop(loop_time, $(this), "#", "warning", "bottom", "right", 20, 20, 'animated fadeInDown', 'animated fadeOutUp');
        loop_time++;
      });
    }
    if ($(".danger_messages").length) {
      $( ".danger_messages" ).each(function() {
        notificationLoop(loop_time, $(this), "#", "danger", "bottom", "right", 20, 20, 'animated fadeInDown', 'animated fadeOutUp');
        loop_time++;
      });
    }

/*==================== end of document ready functions  ====================*/

/*==================== start of individual functions  ====================*/

    // Function for looping notificaitons

    function notificationLoop(time, data, redirect, colorName, placementFrom, placementAlign, offsetFrom, offsetAlign, animateEnter, animateExit){
        setTimeout(function () {
        showNotification(data.find('h1').text(), data.find('p').text(), redirect, colorName, placementFrom, placementAlign, offsetFrom, offsetAlign, animateEnter, animateExit);
        }, 1000 * time)
    }

    // Function for showing notificaitons

    function showNotification(title, text, redirect, colorName, placementFrom, placementAlign, offsetFrom, offsetAlign, animateEnter, animateExit) {
        if (title === null || text === '') { text = ''; }
        if (text === null || text === '') { text = ''; }
        if (colorName === null || colorName === '') { colorName = 'bg-black'; }
        if (redirect === null || redirect === '') { redirect = '#'; }
        if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
        if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
        var allowDismiss = true;

        $.notify({
          // options
          icon: 'glyphicon glyphicon-warning-sign',
          title: title,
          message: text,
          url: redirect,
          target: '_blank'
        },{
          // settings
          element: 'body',
          position: null,
          type: colorName,
          allow_dismiss: allowDismiss,
          newest_on_top: true,
          showProgressbar: false,
          placement: {
            from: placementFrom,
            align: placementAlign
          },
          offset: {
            x: offsetFrom,
            y: offsetAlign
          },
          spacing: 10,
          z_index: 1031,
          delay: 2000,
          timer: 1000,
          url_target: '_blank',
          mouse_over: null,
          animate: {
            enter: animateEnter,
            exit: animateExit
          },
          onShow: null,
          onShown: null,
          onClose: null,
          onClosed: null,
          icon_type: 'class',
          template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                      '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                      '<span data-notify="title">{1}</span>' +
                      '<br><span data-notify="message">{2}</span>' +
                      '<div class="progress" data-notify="progressbar">' +
                        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                      '</div>' +
                      '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    '</div>'
        });
    }
</script>
