  <link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/aqzztx.css" />
 {{ partial('layouts/page_header') }}
<div class="w960 ur_here" style="margin-top:40px;"><a href="javascript:;">首页</a>&nbsp;>&nbsp;{{catname}}</div>
<div class="contianer w960 mb20 bt2">
    <ul class="artical_list">
        {% if data.items is defined %}
        {% for article in data.items %}
        <li>
            <a href="/product/info?p={{article.id}}">
                <span><em>{{ article.title }}</em><i>{{ date('Y-m-d H:i:s', article.addtime) }}</i></span>
                <font>{{article.description}}</font>
            </a>
        </li>
        {% endfor %}
        {% endif %}

    </ul>
    <!-- 块 start -->
    <div class="page mb20">
        {{ pages }}
        <span>共{{ data.total_pages }}页</span>
    </div>
    
    <!-- 块 end -->
</div>
<script>
jQuery(document).ready(function(){
    $('.artical_list li').hover(function(){
        $(this).addClass('hover');
        $(this).prev('li').addClass('bg_line');
    }, function(){
        $(this).removeClass('hover');
        $(this).prev('li').removeClass('bg_line');
    });
});
</script>
{{ partial('layouts/footer') }}