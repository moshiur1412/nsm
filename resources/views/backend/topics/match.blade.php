@extends('backend.layouts.master')
@section('title')
課題キーワード関連付け
@endsection

@section('extra-css')

{{ Html::style('plugins/jquery-ui/css/jquery-ui.min.css') }}

@endsection

@section('custom-css')

<style type="text/css">
#unlisted_keywords{
  min-height: 40px;
}

.topic_keywords{
  min-height: 40px;
  padding:0;
  margin:0;
}

</style>
@endsection

@section('content')

<div class="row-clearfix">
  <div class="col-md-12">
    <div class="card">
      <div class="header">
        <h2>課題キーワード関連付け</h2>
      </div>
      <div class="body">
        <a href="#" class="btn btn-primary waves-effect refresh">
            <i class="material-icons">refresh</i>
            <span>更新</span>
        </a>
        <h2 class="card-inside-title">未関連キーワードリスト</h2>
        <div class="button-demo topic_keywords" id="unlisted_keywords">
          @foreach ($keywords as $keyword)
          <li class="btn btn-primary waves-effect box-item" data-keywordid="{{ $keyword->id }}">{{ $keyword->prefix }}-{{ $keyword->name }}</li>
          @endforeach
        </div>
        <hr>
        <h2 class="card-inside-title">キーワードリストから関連課題に登録</h2>
        <div class="topics">
        @foreach ($topics as $topic)
        @if($loop->iteration == 1 || $loop->iteration % 5 == 0)
        <div class="row">
          @endif
          <div class="col-sm-3">
            <div class="card">
              <div class="header bg-red">
                <h2>{{ $topic->name }}</h2>
              </div>
              <div id="{{ $topic->id }}" class="topics body">
                <ul class="button-demo topic_keywords" data-topicid="{{ $topic->id }}">
                  @foreach ($topic->keywords as $keyword)
                  <li class="btn btn-primary waves-effect box-item keyword-to-match" data-keywordid="{{ $keyword->id }}">{{ $keyword->prefix }}-{{ $keyword->name }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          @if($loop->iteration % 4 == 0)
        </div>
        @endif
        @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
</div>

@endsection

@section('extra-script')

{{Html::script('plugins/jquery-ui/js/jquery-ui.min.js')}}

@endsection

@section('custom-script')

<script type="text/javascript">

  $(document).ready(function() {
    var topicids = [];
    $(".topics").each(function(){ topicids.push(this.id); });
    var keywordIds = $.parseJSON("{{ $keywordIds }}");

    var oldList, newList, item;
    $('.topic_keywords').sortable({
        start: function(event, ui) {
            item = ui.item;
            newList = oldList = ui.item.parent().parent();
    },
        stop: function(event, ui) {
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
              }
            });
    },
        change: function(event, ui) {
            if(ui.sender) newList = ui.placeholder.parent().parent();
    },
        connectWith: ".topic_keywords"
    }).disableSelection();
    $(".refresh").on("click",function(e){
        let topics = {};
        $.each(topicids, function( index, value ) {
          if (value != "") topics[value] = getKeywordIdsByTopicId(value);
        });
        let unrelated = [];
        $("#unlisted_keywords").find("li").each(function(){unrelated.push($(this).attr("data-keywordid"));});
        topics[0] = unrelated;
        let formatted = [];
        $.each(topics, function( index, value ) {
            $.each(value, function( index2, value2 ) {
              let record = {};
              record[value2] = parseInt(index);
              formatted.push(record);
            });
        });
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
          });
          $.ajax({
            url: "/back/sortKeyword",
            type: 'POST',
            data: {formatted:formatted},
            dataType: 'JSON',
            context: this,
            beforeSend: function(){
              $('.page-loader-wrapper').find('p').text('Please Wait....');
              $(".page-loader-wrapper").css({opacity:0.5}).show();
            },
            success: function(data){
              if(data.status == 200){
                $(".page-loader-wrapper").hide();
              }
              else{
                location.reload();
              }
            },
            error:function () {
              location.reload();
            }
          });

    });
  });

  function getKeywordIdsByTopicId(id) {
      var IDs = [];
      $("ul[data-topicid="+id+"]").find("li").each(function(){ IDs.push($(this).attr("data-keywordid")); });
      return IDs;
  }

</script>

@endsection
