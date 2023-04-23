<!-- 左侧 start -->
<div class="left_nav f-fl">
    <h6 class="f-fs14">个人中心</h6>
    {% for key, val in navLeft %}
    <ul class="n_list">
        <li class="title"><a href="javascript:;">{{ val['title'] }}</a></li>
        <li>
            <ul>
                {% for m, nav in val['child'] %}
                <li {% if curId == nav['id'] %}class="active"{% endif %}>&gt;&nbsp;
                    <a href="/{{ nav['models'] }}/{{ nav['controller'] }}/{{ nav['action'] }}" {% if nav['controller'] == "shop" AND shopVia == 1 %}target="_blank"{% endif %} >
                    {{ nav['title'] }}{% if m == '17' and messagecount %}({{messagecount}}){% endif %}
                    </a>
                    {% if nav['controller'] == "sell" %}
                    <span>|</span><a class="add_link" href="/member/sell/new/">添加</a>
                    {% endif %}
                </li>
                {% endfor %}
            </ul>
        </li>
    </ul>
    {% endfor %}
</div>
<!-- 左侧 end -->


