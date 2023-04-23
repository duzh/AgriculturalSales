

<!-- 左侧 start -->

    {% for key, val in shopLeft %}
    <ul class="decora_left f-fl" id="decora_left" >
        <li class="title"><span class="arrow_top">{{ val['title'] }}</span></li>
        <li class="list">
            <ul>
                {% for m, nav in val['child'] %}
                <li {% if curId == nav['id'] %}class="active"{% endif %}>&gt;&nbsp;<a href="/{{ nav['models'] }}/{{ nav['controller'] }}/{{ nav['action'] }}">{{ nav['title'] }}</a>{% if nav['controller']=='sell' %}|<a class="add_link" href="/member/sell/new/">添加</a>{% endif %}</li>
                {% endfor %}
            </ul>
        </li>
    </ul>
    {% endfor %}


<!-- 左侧 end -->

<script>
$(function(){
    // $('#decora_left .title span').toggle(function(){
    //     $(this).addClass('arrow_down');
    //     $(this).parent().next('.list').slideToggle();
    // }, function(){
    //     $(this).removeClass('arrow_down');
    //     $(this).parent().next('.list').slideToggle();
    // });
});
</script>
