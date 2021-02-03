@extends('backend.layouts.master')
@section('title')
野口遵賞応募
@endsection

@section('extra-css')

{{ Html::style('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}
{{ Html::style('plugins/bootstrap-select/css/bootstrap-select.css') }}

@endsection

@section('custom-css')
<style type="text/css">

</style>
@endsection

@section('content')
<div class="row-clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2>
                    野口遵賞応募
                    <small><strong>応募件数</strong>: {{ count($awards) }}</small>
                </h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-md-12">
                        {!! Form::open(['route' => 'awardApplication.exportCSV', 'method'=>'post', 'id' => 'downloadCSV']) !!}
                        {!! Form::hidden('award_year', $award_year) !!}
                        <a href="{{ route('awardApplications.create') }}" class="btn btn-success waves-effect mb">
                            <i class="material-icons">add</i>
                            <span>新規登録</span>
                        </a>
                        <button type="button" class="btn btn-warning waves-effect mb" id="mark_all_users">
                            <i class="material-icons">select_all</i>
                            <span>全選択</span>
                        </button>
                        <button type="submit" class="btn btn-primary waves-effect mb"><i class="material-icons">file_download</i><span>ダウンロード</span></button>
                        <button class="btn btn-info waves-effect mb" id="renumber">
                            <i class="material-icons">format_list_numbered</i>
                            <span>受付No採番</span>
                        </button>
                        {!! Form::close() !!}
                    </div>


                    <div class="col-md-12">
                        <button type="button" class="btn bg-blue-grey waves-effect mb" id="relate_user" data-toggle="modal">
                            ユーザーを関連付け
                        </button>
                        <button type="button" class="btn bg-blue-grey waves-effect mb" id="update_custom_category" data-toggle="modal" data-target="#update_custom_category_modal">
                            課題分類を更新
                        </button>
                        <button type="button" class="btn bg-blue-grey waves-effect mb" id="grant_selected">
                            選択を採択
                        </button>
                        <button type="button" class="btn bg-blue-grey waves-effect mb" id="reject_selected">
                            選択を不採択
                        </button>
                        <button type="button" class="btn bg-blue-grey waves-effect mb" id="send_reject_email">
                            不採択を通知
                        </button>

                    </div>
                    <div class="col-md-1">
                       <h2 class="card-inside-title">表示年</h2>
                   </div>
                   <div class="col-md-3">
                    {!! Form::open(['url' => '/back/awardApplications', 'method'=>'get', 'id' => 'sortByYearForm']) !!}
                    <select class="form-control show-tick" name="award_year" data-live-search="true">
                        <option>選択してください</option>
                        @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year ==  $award_year ? 'selected = "selected"' : '' }} >{{ $year }}</option>
                        @endforeach
                    </select>
                    {!! Form::close() !!}
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary waves-effect" onclick="$('#sortByYearForm').submit();">再表示</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover dataTable js-award-datatable">
                    <thead>
                        <tr>
                            <th>選択</th>
                            <th>ID</th>
                            <th>受付No</th>
                            <th>名前</th>
                            <th>所属機関名</th>
                            <th>所属名</th>
                            <th>職</th>
                            <th>審査分類</th>
                            <th>採択年</th>
                            <th>テーマ</th>
                            <th>採択/不採択</th>
                            <th>送信済/未送信</th>
                            <th>変更</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>選択</th>
                            <th>ID</th>
                            <th>受付No</th>
                            <th>名前</th>
                            <th>所属機関名</th>
                            <th>所属名</th>
                            <th>職</th>
                            <th>審査分類</th>
                            <th>採択年</th>
                            <th>テーマ</th>
                            <th>採択/不採択</th>
                            <th>送信済/未送信</th>
                            <th>変更</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($awards as $award)

                        <tr>
                            <td>
                                <input type="checkbox" id="applicants_{{ $loop->iteration}}" name="applications[]" value="{{ $award->id }}"
                                data-userid="{{ isset($award->user)? $award->user->id : '' }}"
                                data-userName="{{ isset($award->user) && !empty($award->user->name) ? $award->user->name : 'N/A' }}"
                                data-email="{{ isset($award->user)? $award->user->email : 'N/A' }}"
                                data-receipt="{{ $award->receipt }}"
                                data-name="{{ $award->name }}"
                                />
                                <label for="applicants_{{ $loop->iteration}}"></label>
                            </td>
                            <td>{{ $award->id }}</td>
                            <td>{{ $award->receipt }}</td>
                            <td>{{ $award->name }}</td>
                            <td>{{ $award->belongs }}</td>
                            <td>{{ $award->major }}</td>
                            <td>{{ $award->occupation }}</td>
                            <td>{{ empty($award->custom_topic->name) ? '' : substr($award->custom_topic->name, 0, 5)  }}</td>
                            <td>{{ date('Y', strtotime($award->subsidy_granted_year)) }}</td>
                            <td>{{ $award->theme }}</td>
                            <td>
                                @if($award->is_granted == 0)
                                {!! '<span class="label label-warning">未処理</span>' !!}
                                @elseif($award->is_granted == 1)
                                {!! '<span class="label label-success">採択済</span>' !!}
                                @elseif($award->is_granted == 2)
                                {!! '<span class="label label-danger">不採択</span>' !!}
                                @endif
                            </td>
                            <td>{!! $award->mail_sent == 0 ? '<span class="label label-danger">未送信</span>' : '<span class="label label-success">送信済</span>' !!}</td>



                            <td>
                                <!-- {!! Form::open(['route' => ['awardApplications.destroy', $award->id], 'method'=>'delete']) !!} -->
                                <a class="btn btn-success" href="{{route('awardApplications.edit', $award->id)}}" title="Show/Edit Subsidy"><i class="material-icons">edit</i></a>
                                <!-- {!! Form::button('<i class="material-icons">delete</i>', array('class' => 'btn btn-danger form_warning_sweet_alert', 'title'=>'警告', 'text'=>'この応募は削除されます', 'confirmButtonText'=>'削除', 'type'=>'submit')) !!} -->
                                <!-- {!! Form::close() !!} -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Relate User-->
<div class="modal fade" id="relate_user_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ユーザーを関連付け</h4>
            </div>
            <div class="modal-body">
                <div>
                    <small>
                        <strong>ユーザー名:</strong> <span id="modal_user_name">未選択</span>,　
                        <strong>Email:</strong> <span id="modal_user_email">未選択</span>
                    </small>
                </div>
                <div>
                    <small>
                        <strong>受付No:</strong> <span id="modal_receipt_no">未選択</span>,　
                        <strong>応募名</strong> <span id="modal_app_name">未選択</span>
                    </small>
                </div>
                <div class="table-responsive">
                    <table id="applicant-table" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>選択</th>
                                <th>ユーザー名</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>選択</th>
                                <th>ユーザー名</th>
                                <th>Email</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($applicants as $applicant)
                            <tr>
                                <td>
                                    <input type="radio" id="relate-user-radio-{{ $applicant->id }}" name="will_relate_user" value="{{ $applicant->id}}"/>
                                    <label for="relate-user-radio-{{ $applicant->id }}"></label>
                                    <!-- {!! Form::radio('will_relate_user', $applicant->id, false, ['id' => 'relate-user-radio-'.$applicant->id ]) !!} -->
                                </td>
                                <td>{{ $applicant->name }}</td>
                                <td>{{ $applicant->email }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" id="relate_user_submit">更新</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Category -->
<div class="modal fade" id="update_custom_category_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">審査用課題分類を割当</h4>
            </div>
            <div class="modal-body">
                <div class="form-group form-float">
                    <select class="form-control show-tick" name="custom_topic_id">
                        <option>-- 分類を選択 --</option>
                        @foreach($custom_categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label class="error" for="topic_id">
                        @if ($errors->has('custom_topic_id'))
                        {{ $errors->first('custom_topic_id') }}
                        @endif
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" id="update_custom_category_submit">更新</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>


<!-- Send Mail -->
<div class="modal fade" id="send_mail_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="send_mail_modal_title"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group form-float">
                    <div class="form-line focused">
                        {!! Form::text('subject', null, array('class' =>'form-control')) !!}
                        {!! Form::label('subject', 'Subject', array('class' => 'form-label')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-line">
                        {!! Form::textarea('message', null, array('class'=>'form-control no-resize auto-growth', 'placeholder' => 'Message',  'rows' => '1', 'id' => 'mail_message')) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" id="send_mail_submit">SEND MAIL</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>


@endsection

@section('extra-script')


{{Html::script('plugins/bootstrap-select/js/bootstrap-select.js')}}
{{Html::script('plugins/autosize/autosize.js')}}
{{Html::script('plugins/jquery-datatable/jquery.dataTables.js')}}
{{Html::script('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/jszip.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}
{{Html::script('plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}

@endsection

@section('custom-script')

{{Html::script('js/script.js')}}
{{Html::script('js/pages/tables/jquery-datatable.js')}}

<script type="text/javascript">

    $(document).ready(function(){

        var next_no = {{$next_no}};

        autosize($('textarea.auto-growth'));
        $("input[name='applications[]']").prop('checked', false);
        var selected_user_id;
        var user_table = $("#applicant-table").DataTable();
        user_table.on( 'draw', function () {
            if (selected_user_id == undefined) return;
            $('input[name=will_relate_user').each(function(){
                if (selected_user_id != parseInt($(this).val())) {
                    $(this).prop('checked',false);
                }
            });
        });

        buttonsDisable();

        var subTableData = [];

        $('.js-award-datatable').dataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Japanese.json"
            },
            "fnPreDrawCallback": function(oSettings) {
                /* reset currData before each draw*/
                subTableData = [];
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                /* push this row of data to currData array*/
                subTableData.push(aData);
            },
            "fnDrawCallback": function(oSettings) {
                /* can now access sorted data array*/
                $('input[name="ids[]"]').remove();
                for($i = 0; $i < subTableData.length; $i++){
                    $('#downloadCSV').append('<input type="hidden" name="ids[]" value="'+subTableData[$i][1]+'" />');
                }
            }
        });

        $('input[name="applications[]"]').change(function () {
            if($('input[name="applications[]"]:checked').length > 0){
                $("#mark_all_users").empty().append('<i class="material-icons">clear</i><span>選択解除</span>');
                $("#mark_all_users").addClass('btn-danger').removeClass('btn-warning');
                buttonsEnable();
            }
            else{
                $("#mark_all_users").empty().append('<i class="material-icons">select_all</i><span>全選択</span>');
                $("#mark_all_users").addClass('btn-warning').removeClass('btn-danger');
                buttonsDisable();
            }
        });

        $("#mark_all_users").click(function () {
            if($('input[name="applications[]"]:checked').length > 0){
                $(this).empty().append('<i class="material-icons">select_all</i><span>全選択</span>');
                $(this).addClass('btn-warning').removeClass('btn-danger');
                $("input[name='applications[]']").prop('checked', false);
                buttonsDisable();
            }
            else{
                $(this).empty().append('<i class="material-icons">clear</i><span>選択解除</span>');
                $(this).addClass('btn-danger').removeClass('btn-warning');
                $("input[name='applications[]']").prop('checked', true);
                buttonsEnable();
            }
        });

        // Update Custome Topic

        $('#update_custom_category_submit').unbind().click(function(){

            var applications = new Array();

            $('input[name="applications[]"]:checked').each(function(){
                applications.push($(this).val());
            });

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
              }
          });
            $.ajax({
                url: "/back/awardApplications/update/customTopic",
                type: 'POST',
                data: {custom_topic_id: $('select[name=custom_topic_id]').val(), applications: applications},
                dataType: 'JSON',
                context: this,
                success:function(response){
                    if(response.error) {
                        swal("失敗", "サーバーが応答しません。もう一度やり直してください", "エラー");
                    }
                    else{
                        if(response['status'] == 'error'){
                            swal("Error Found!", response['message'], "error");
                        }
                        else if(response['status'] == '成功'){
                            swal("成功", response['message']+" 審査分類が更新されました", "success");
                            setTimeout(function() {
                                location.reload();
                            }, 1500)
                        }
                    }
                },
                error: function (response) {
                    swal("失敗", "サーバーに問題が発生しました。もう一度やり直してください", "エラー");
                }
            });
        });

        // Update grant selected of the selections

        $('#grant_selected').unbind().click(function(){
            swal({
                title: "採択処理",
                text: "選択した応募を採択状態にします",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: '一括採択',
                cancelButtonText: "戻る"
            },
            function(isConfirm){
                if (isConfirm){

                    var applications = new Array();

                    $('input[name="applications[]"]:checked').each(function(){
                        applications.push($(this).val());
                    });

                    $.ajaxSetup({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                      }
                  });

                    $.ajax({
                        url: "/back/awardApplications/update/grantSelected",
                        type: 'POST',
                        data: {applications: applications},
                        dataType: 'JSON',
                        context: this,
                        success:function(response){
                            if(response.error) {
                                swal("失敗", "サーバーが応答しません。もう一度やり直してください", "エラー");
                            }
                            else if(response['status'] == 'success'){
                                swal("成功", response['message'], "success");
                                setTimeout(function() {
                                    location.reload();
                                }, 1500)
                            }
                        },
                        error: function (response) {
                            swal("失敗", "サーバーに問題が発生しました。もう一度やり直してください", "エラー");
                        }
                    });
                }
            });
        });

            // Update reject selected of the selections
            $('#reject_selected').unbind().click(function(){
                swal({
                    title: "不採択処理",
                    text: "選択した応募を不採択状態にします",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: '一括不採択',
                    cancelButtonText: "戻る"
                },
                function(isConfirm){
                    if (isConfirm){

                        var applications = new Array();

                        $('input[name="applications[]"]:checked').each(function(){
                            applications.push($(this).val());
                        });

                        $.ajaxSetup({
                            headers: {
                              'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                          }
                      });
                        $.ajax({
                            url: "/back/awardApplications/update/rejectSelected",
                            type: 'POST',
                            data: {applications: applications},
                            dataType: 'JSON',
                            context: this,
                            success:function(response){
                                if(response.error) {
                                    swal("失敗", "サーバーが応答しません。もう一度やり直してください", "エラー");
                                }
                                else if(response['status'] == 'success'){
                                    swal("成功", response['message'], "success");
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1500)
                                }
                            },
                            error: function (response) {
                                swal("失敗", "サーバーに問題が発生しました。もう一度やり直してください", "エラー");
                            }
                        });

                    }
                });
            });

        // Open Mail Sender modal for granted
        $("#send_grant_email").click(function(){
            $("#send_mail_modal").modal();
            $("#send_mail_modal_title").text('Send Grant Email');
            $('input[name=subject]').val('You Have Been Granted!');
        });

        // Open Mail Sender modal for rejected
        $("#send_reject_email").click(function(){
            swal({
                title: "不採択を通知",
                text: "選択した応募に不採択を通知します",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: '一括通知',
                cancelButtonText: "戻る"
            },
            function(isConfirm){
                if (isConfirm){

                    var applications = new Array();

                    $('input[name="applications[]"]:checked').each(function(){
                        applications.push($(this).val());
                    });

                    $.ajaxSetup({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                      }
                  });
                    $.ajax({
                      url: "/back/awardApplications/sendMail",
                      type: 'POST',
                      data: {applications: applications},
                      dataType: 'JSON',
                      context: this,
                      success:function(response){
                          if(response.error) {
                              swal("Oops!", "Server did not respond. Please try again", "error");
                          }
                          else{
                              if(response['status'] == 'error'){
                                  swal("失敗", response['message'], "error");
                              }
                              else if(response['status'] == 'success'){
                                  swal("成功", response['message'], "success");
                                  setTimeout(function() {
                                      location.reload();
                                  }, 1500)
                              }
                          }
                      },
                      error: function (response) {
                          swal("Oops!", "Something went wrong in the server. Please try again", "error");
                      }
                  });
                }
            });
        });

        // Send Mail to selections
/*
        $('#send_mail_submit').unbind().click(function(){

            var applications = new Array();

            $('input[name="applications[]"]:checked').each(function(){
                applications.push($(this).val());
            });

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
              }
          });
            $.ajax({
                //url: "/back/awardApplications/sendMail",
                url: "/back/awardApplications/testsendEmail",
                type: 'POST',
                // data: {subject: $('input[name=subject]').val(), message: $('#mail_message').val(), applications: applications},
                data: {applications: applications},
                dataType: 'JSON',
                context: this,
                success:function(response){
                    if(response.error) {
                        swal("失敗", "サーバーが応答しません。もう一度やり直してください", "エラー");
                    }
                    else{
                        if(response['status'] == 'error'){
                            swal("Error Found!", response['message'], "error");
                        }
                        else if(response['status'] == 'success'){
                            swal("Success!", response['message']+" Wait till page refreshes..", "success");
                            setTimeout(function() {
                                location.reload();
                            }, 1500)
                        }
                    }
                },
                error: function (response) {
                    swal("失敗", "サーバーに問題が発生しました。もう一度やり直してください", "エラー");
                }
            });
        });
        */

        $("#relate_user").click(function(){
            var applications = new Array();
            var userid;
            var userName;
            var email;
            var appId;
            var name;
            var receipt;

            $('input[name="applications[]"]:checked').each(function(){
                applications.push($(this).val());
                userName = $(this).attr('data-userName');
                userid = $(this).attr('data-userid');
                email = $(this).attr('data-email');
                appId = $(this).val();
                name = $(this).attr('data-name');
                receipt = $(this).attr('data-receipt');
            });
            if (applications.length != 1) {
                swal({
                 title: '複数の応募が選択されています',
                 type: 'warning',
                 showCancelButton: false,
                 confirmButtonText: "戻る",
                 closeOnConfirm: true,
                 html: true
             });
                return;
            }
            user_table.page.len(-1).draw();

            $('#modal_user_name').html(userName);
            $('#modal_user_email').html(email);
            $('#relate_user_modal').modal('show');
            $('#relate-user-radio-'+userid).prop('checked',true);
            $('#modal_app_name').attr('data-appId',appId);
            user_table.page.len(10).draw();
        });

        $('input[name=will_relate_user]').click(function(e){
            selected_user_id = $(this).val();
        });

        $("#relate_user_submit").click(function(e){
            var userId = $('input[name="will_relate_user"]:checked').val();
            var appId = $('#modal_app_name').attr('data-appId');
            if (userId == null || appId == null) {
                swal({
                    title: 'ユーザーを選択してください',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: "戻る",
                    closeOnConfirm: true
                });
                return;
            }
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
              }
          });
            $.ajax({
              url: "/back/awardApplications/relate",
              type: 'POST',
              data: {user: userId, application: appId},
              dataType: 'JSON',
              context: this,
              success:function(response){
                  if(response.error) {
                      swal("Oops!", "Server did not respond. Please try again", "error");
                  }
                  else{
                      if(response['status'] == 'error'){
                          swal("失敗", response['message'], "error");
                      }
                      else if(response['status'] == 'success'){
                          swal("成功", response['message'], "success");
                          setTimeout(function() {
                              location.reload();
                          }, 1500)
                      }
                  }
              },
              error: function (response) {
                  swal("Oops!", "Something went wrong in the server. Please try again", "error");
              }
          });
        });
        $("#renumber").click(function(e){
            e.preventDefault();
            swal({
                title: "現在の次の受付Noは「"+next_no+"」です",
                text: '<span style="color:red;">【注意】採番すると復元はできません。</span>',
                type: "warning",
                showCancelButton: true,
                confirmButtonText: '次の受付Noを「1」にする',
                cancelButtonText: "戻る",
                html:true
            },
            function(isConfirm){
                if (isConfirm){
                    $.ajaxSetup({
                        headers: {
                          'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                      }
                  });
                    $.ajax({
                      url: "/back/awardApplications/renumber",
                      type: 'POST',
                      dataType: 'JSON',
                      context: this,
                      success:function(response){
                          if(response.error) {
                              swal("Oops!", "Server did not respond. Please try again", "error");
                          }
                          else{
                              if(response['status'] == 'error'){
                                  swal("失敗", response['message'], "error");
                              }
                              else if(response['status'] == 'success'){
                                  swal("成功", response['message'], "success");
                                  setTimeout(function() {
                                      location.reload();
                                  }, 1500)
                              }
                          }
                      },
                      error: function (response) {
                          swal("Oops!", "Something went wrong in the server. Please try again", "error");
                      }
                  });
                }
            });
        });
            // Refresh values on modal close
            $('#send_mail_modal').on('hidden.bs.modal', function () {
                $("#send_mail_modal_title").text('');
                $("input[name=subject]").val('');
                $("input[name=message]").val('');
            })

            function buttonsEnable(){
                $("#relate_user").prop('disabled', false);
                $("#update_custom_category").prop('disabled', false);
                $("#grant_selected").prop('disabled', false);
                $("#send_grant_email").prop('disabled', false);
                $("#reject_selected").prop('disabled', false);
                $("#send_reject_email").prop('disabled', false);
            }

            function buttonsDisable(){
                $("#relate_user").prop('disabled', true);
                $("#update_custom_category").prop('disabled', true);
                $("#grant_selected").prop('disabled', true);
                $("#send_grant_email").prop('disabled', true);
                $("#reject_selected").prop('disabled', true);
                $("#send_reject_email").prop('disabled', true);
            }
        });

    </script>

    @endsection
