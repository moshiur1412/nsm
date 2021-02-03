@extends('subsidy.layouts.master')
@section('title')
公益財団法人 野口研究所
@endsection

@section('content')
<div class="row clearfix">

    {!! Form::open(['method' => $subsidy->exists ?  'put' : 'post', 'route' => $subsidy->exists ?  ['subsidyApply.update', $subsidy->id] : ['subsidyApply.store'], 'name' => 'inquiry_form', 'id' => 'Submit', 'class' => 'demo-masked-input cd-form', 'role' => 'form', 'autocomplete' => "off"]) !!}

    {!! Form::hidden('cancel_redirect', $subsidy->exists ? route('subsidyApply.edit', $subsidy->id) : route('subsidyApply.create')) !!}

    {!! Form::hidden('user_id', $subsidy->exists ? $subsidy->user_id : $auth_user['id']) !!}

    {!! Form::hidden('tmp_folder', $tmp_folder) !!}

    <div class="card">
        <div class="header">
            <h2>【STEP:2 確認】野口遵研究助成金申請書・参考論文送信フォーム</h2>
            <ul class="header-dropdown m-r--5"></ul>
        </div>
        <div class="box-form">
            <div class="notes">
              <p>以下の内容で申し込みます。<br>
                内容をご確認の上「送信する」をクリックしてください。</p>
            </div>
            <seciotn class="registForm">
                <section class="basicForm">
                    <table class="company">
                        <tr>
                            <th>氏名</th>
                            <td>
                                {{ $data['name'] }}
                                {!! Form::hidden('name', $data['name']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>フリガナ</th>
                            <td>
                                {{ $data['name_kana'] }}
                                {!! Form::hidden('name_kana', $data['name_kana']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>氏名（ローマ字）</th>
                            <td>
                                {{ $data['name_alphabet'] }}
                                {!! Form::hidden('name_alphabet', $data['name_alphabet']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>生年月日</th>
                            <td>
                                {{ $data['birthday'] }}
                                {!! Form::hidden('birthday', $data['birthday']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>所属区分</th>
                            <td>
                                {{ $data['belong_type_name'] }}
                                {!! Form::hidden('belong_type_name', $data['belong_type_name']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>所属機関名</th>
                            <td>
                                {{ $data['belongs'] }}
                                {!! Form::hidden('belongs', $data['belongs']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>所属名</th>
                            <td>
                                {{ $data['major'] }}
                                {!! Form::hidden('major', $data['major']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>職</th>
                            <td>
                                {{ $data['occupation'] }}
                                {!! Form::hidden('occupation', $data['occupation']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>郵便番号</th>
                            <td>
                                {{ $data['zip_code'] }}
                                {!! Form::hidden('zip_code', $data['zip_code']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>住所1</th>
                            <td>
                                {{ $data['address1'] }}
                                {!! Form::hidden('address1', $data['address1']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>住所2</th>
                            <td>
                                {{ $data['address2'] }}
                                {!! Form::hidden('address2', $data['address2']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>研究テーマ名</th>
                            <td>
                                {{ $data['theme'] }}
                                {!! Form::hidden('theme', $data['theme']) !!}
                            </td>
                        </tr>
                        <tr>
                            <th>キーワード</th>
                            <td>
                                @php
                                $topic = json_decode($data['topic'], true);
                                @endphp
                                {{ $topic['topic']['name'].': '.$topic['keyword']['prefix'].'-'.$topic['keyword']['name'] }}
                                {!! Form::hidden('topic', $data['topic']) !!}
                            </td>
                        </tr>
                        @if(!empty($data['application_path']))
                        <tr>
                            <th>添付PDF 様式１</th>
                            <td>
                                <object class="pdf_preview_frame" data="{{ $data['application_path'] }}#view=Fit" type="application/pdf" width="100%" height="500">
                                    <iframe src="{{ $data['application_path'] }}#view=Fit" width="100%" height="500">
                                        <p><a href="{{ $data['application_path'] }}" download="{{ $data['application'] }}"><i class="fa fa-download" aria-hidden="true"></i> ダウンロードして確認する</a></p>
                                    </iframe>
                                </object>
                               <p class="pdf-js"><a href="{{ asset('plugins/pdfjs/web/viewer.html') }}?file={{ $data['application_path'] }}" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> PDFが表示されない方はこちら(別タブで開きます)</a></p>
                                <p class="pdf-dl"><a href="{{ $data['application_path'] }}" target="_blank" download="{{ $data['application'] }}"><i class="fa fa-download" aria-hidden="true"></i> ダウンロードして確認する</a></p>
                            </td>
                        </tr>
                        @endif
                        @if(!empty($data['attachment_path']))
                        <tr>
                            <th>添付PDF 様式２</th>
                            <td>
                                <object class="pdf_preview_frame" data="{{ $data['attachment_path'] }}#view=Fit" type="application/pdf" width="100%" height="500">
                                    <iframe src="{{ $data['attachment_path'] }}#view=Fit" width="100%" height="500">
                                        <p><a href="{{ $data['attachment_path'] }}" download="{{ $data['attachment'] }}"><i class="fa fa-download" aria-hidden="true"></i> ダウンロードして確認する</a></p>
                                    </iframe>
                                </object>
                               <p class="pdf-js"><a href="{{ asset('plugins/pdfjs/web/viewer.html') }}?file={{ $data['attachment_path'] }}" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> PDFが表示されない方はこちら(別タブで開きます)</a></p>
                                <p class="pdf-dl"><a href="{{ $data['attachment_path'] }}" target="_blank" download="{{ $data['attachment'] }}"><i class="fa fa-download" aria-hidden="true"></i> ダウンロードして確認する</a></p>
                            </td>
                        </tr>
                        @endif
                        @if(!empty($data['reference_path']))
                        <tr>
                            <th>添付PDF 参考論文ファイル</th>
                            <td>
                                <object class="pdf_preview_frame" data="{{ $data['reference_path'] }}#view=Fit" type="application/pdf" width="100%" height="500">
                                    <iframe src="{{ $data['reference_path'] }}#view=Fit" width="100%" height="500">
                                        <p><a href="{{ $data['reference_path'] }}" download="{{ $data['reference'] }}"><i class="fa fa-download" aria-hidden="true"></i> ダウンロードして確認する</a></p>
                                    </iframe>
                                </object>
                               <p class="pdf-js"><a href="{{ asset('plugins/pdfjs/web/viewer.html') }}?file={{ $data['reference_path'] }}" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> PDFが表示されない方はこちら(別タブで開きます)</a></p>
                                <p class="pdf-dl"><a href="{{ $data['reference_path'] }}" target="_blank" download="{{ $data['reference'] }}"><i class="fa fa-download" aria-hidden="true"></i> ダウンロードして確認する</a></p>
                            </td>
                        </tr>
                        @endif
                    </table>
                    <section class="positioncheckbox">
                        <label class="labeltitle">入力内容を確認し問題がなければチェックを入れてください。</label>
                        <div class="form-group">
                            <input type="checkbox" name="check_affiliation" id="check_affiliation" class="with-gap">
                            <label class="check_agree" for="check_affiliation">確認しました</label>
                        </div>
                    </section>
                </section>
            </seciotn>
            <p class="fieldset CancellationButtonGroup">
                <input class="harf-width has-padding registBack" onclick="cancel()" type="button" value="編集する" id="cancel_subsidy">
                {!! Form::submit('送信する', array('class' =>'harf-width has-padding', 'style' => 'background-color: #d1d8dc;', 'id'=>'form_submit', 'disabled' => 'disabled')) !!}
            </p>
        </div>
    </div>

    {!! Form::close() !!}

</div>

@endsection

@section('custom-script')

<script type="text/javascript">

        // Cancel Form

        function cancel(){

            var datastring = $("#Submit").serialize();

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
              }
          });

            $.ajax({
                url: "/app/subsidyApply/cancel",
                type: 'POST',
                data: {datastring: datastring},
                dataType: 'JSON',
                context: this,
                success:function(response){
                    if(response.status == 200){
                        $(location).attr('href', $("input[name=cancel_redirect]").val());
                    }
                    else{
                        swal("失敗", "サーバーが応答しません。もう一度やり直してください", "エラー");
                    }
                },
                error: function(){
                    swal("失敗", "サーバーに問題が発生しました。もう一度やり直してください", "エラー");
                }
            });
        }

        //Enable when checked
        $('#check_affiliation').change(function (){
            if ($(this).is(":checked")) {
                $("#form_submit").prop('disabled', false);
                $('#form_submit').css('background-color','dodgerblue');
            }
            else{
                $("#form_submit").prop('disabled', true);
                $('#form_submit').css('background-color','#d1d8dc');
            }
        });

        //Disable multiple clicks
        $(function(){
        	$('[type="submit"]').click(function(){
        		$(this).prop('disabled',true);
        		$(this).closest('form').submit();
        	});
        });

        // Disable iframe for Internet Explorer 6-11
        var isIE = /*@cc_on!@*/false || !!document.documentMode;
        if(isIE){
            $('.pdf_preview_frame').hide();
        }

    </script>

    @endsection
