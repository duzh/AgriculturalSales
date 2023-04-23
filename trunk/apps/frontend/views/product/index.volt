<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->

<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span><a href="/">首页</a>&nbsp;>&nbsp;{% if (first) %}<a href="/product/?p={{ first.id }}">帮助中心</a>&nbsp;>&nbsp;{% endif %}{% if (catfirst) %}<a href="/product/index?p={{ catfirst.id }}">
        <?php echo Mdg\Models\ProductCategory::getcategory($article->cid); ?></a>&nbsp;>&nbsp;{% endif %}<?php echo $article->title; ?></span>
</div>
<div class="personal_center w960 mb20">

    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <h6 class="artical_title"><span><?php echo $article->title; ?></span></h6>  
        <div class="artical">
            <?php echo $article->content; ?>
        </div>
        <div class="artical_link">
            {% if (prev) %}
            <span class="f-fl">上一篇：<em>
              <a href="/product/index?p={{ prev.id }}">{{ prev.title }}</a></em></span>
            {% endif %}
            {% if (next) %}
            <span class="f-fr">下一篇：<a href="/product/index?p={{ next.id }}">{{ next.title }}</a></span>
            {% endif %}
        </div>
    </div>
    <!-- 右侧 end -->
</div>
<!-- 主体内容结束 -->

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
