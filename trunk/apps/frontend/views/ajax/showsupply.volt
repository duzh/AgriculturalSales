
    <span class="f-fl f-fs14">供应量</span>
    <div class="list_xiang f-fl">
        <a class="all active"  href="{{ url }}supply=all">全部</a>
        <a href="{{ url }}supply=499" class="">0-499公斤</a>
        <a href="{{ url }}supply=999" class="">500公斤-999公斤</a>
        <a href="{{ url }}supply=10000" class="">1吨-10吨</a>
        <a href="{{ url }}supply=10001" class="">10吨以上</a>
    </div>
   <!--  {% if childData %}
    <div class="xiang clear">
        <div class="hideDiv">
            {% for key, child in childData %}
            <a href="{{ url }}c={{ child.id }}">
                {{ child.title }}
                <img src="{{ constant('STATIC_URL') }}mdg/images/choose_checked_img.png" {% if  id == child.id %}style="display: block;"{% endif %}></a>
            {% endfor %}
        </div>
        {% if childcount and childcount > 28 %}<em class="showMore" style="display:block;">更多</em>{% endif %}
    </div>

{% endif %} -->

<script>
$(function(){
    //分类显示隐藏效果
    /*$('.showMore').toggle(function(){
        var len = $(this).parent().find('.hideDiv').height();
        $(this).parent().css('height', len + 'px');
        $(this).addClass('hideMore');
        $(this).text('收起');
    }, function(){
        $(this).parent().css('height', '56px');
        $(this).removeClass('hideMore');
        $(this).text('更多');
    });*/
    $('.showMore').click(function(){
        var parentLen = $(this).parent().height();
        var len = $(this).parent().find('.hideDiv').height();
        if(parentLen == 56){
            $(this).parent().css('height', len + 'px');
            $(this).addClass('hideMore');
            $(this).text('收起');
        }else{
            $(this).parent().css('height', '56px');
            $(this).removeClass('hideMore');
            $(this).text('更多');
        };
        
    });
});
</script>
