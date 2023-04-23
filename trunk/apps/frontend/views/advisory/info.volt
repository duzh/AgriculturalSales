<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/aqzztx.css" />
<!-- 头部开始 -->
{{ partial('layouts/page_header_old') }}
<!-- 头部结束 -->
<div class="w960 ur_here " style="margin-top:40px;" ><a href="/index">首页</a>&nbsp;>&nbsp;<a href="/advisory/index?c={{catid}}">{{catname}}</a>&nbsp;>&nbsp;<?php echo $article->title; ?></div>
    <div class="contianer w960 mb20 bt2">
    <div class="artical">
        <h5 class="tc mt20"><?php echo $article->title; ?></h5>
        <font class="f-db tc">{{ date('Y-m-d ', article.addtime) }}</font>
        <p><?php echo $article->content; ?></p>
    </div>
    <div class="gl">
            {% if (prev) %}
            <span class="f-fl">上一篇：<em>
              <a href="/advisory/info?p={{ prev.id }}">{{ prev.title }}</a></em></span>
            {% endif %}
            {% if (next) %}
            <span class="f-fr">下一篇：<a href="/advisory/info?p={{ next.id }}">{{ next.title }}</a></span>
            {% endif %}
    </div>
</div>
<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->

<script type="text/javascript" src="js/inputFocus.js"></script>
<script>
function btnHover(obj){
    obj.hover(function(){
        $(this).addClass('hover');
    }, function(){
        $(this).removeClass('hover');
    });
};
jQuery(document).ready(function(){
    var searchBtn = $('.top_search .btn');
    btnHover(searchBtn);
    
    var txtInput = $('.ws_message input[type=text]');
    inputFb(txtInput);
});
</script>
</body>
</html>
