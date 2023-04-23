<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL')}}/mdg/css/channel_pindao.css" />
{{ partial('layouts/page_header') }}

<!-- 主体内容开始 -->
<div class="wrapper f-oh" style="background:#f2f2f2;">
    <div class="w968 mtauto f-oh">
        <ul class="channel_pindao_list">
            {% for key , item in data['items'] %}
            <li>
                <a href="/sell/info/{{ item['id']}}&source=1">
                    <div class="imgs">
                        {% if  item['thumb'] %}

                        <img src="{{ constant('IMG_URL')}}{{ item['thumb']}}"  width='221px' height='151px'/>
                        {% else %}
                        <img src="http://static.ync365.com/mdg/images/detial_b_img.jpg"  width='221px' height='151px'/>

                        {% endif %}
                    </div>
                    <div class="img_name">{{ item['title']}}</div>
                </a>
            </li>
            {% endfor %}
        </ul>
        <!-- 分页 start -->
        <div class="page mb20" style="background:#f2f2f2;">
            <form action="/tag/index">
            {{ data['pages']}}
             <em>跳转到第
                <input type="text" name='p' value='{{ p }}' />
                页</em> 
                <input type="submit" value='确定'>
            </form>
    </div>
    <!-- 分页 end -->

    <!-- 使用指南 -->
    <div class="guide">
        <div class="title">使用指南</div>
        <ul class="msg">
            <li>
                <img src="{{ constant('STATIC_URL')}}/mdg/images/guide_msg_img1.png" />
            </li>
            <li>
                <img src="{{ constant('STATIC_URL')}}/mdg/images/guide_msg_img2.png" />
            </li>
        </ul>
    </div>

    <!-- 标签申请流程 -->
    <div class="label_process">
        <div class="title">标签申请流程</div>
        <div class="msg">
            <img src="{{ constant('STATIC_URL')}}/mdg/images/label_process_img.png" />
        </div>
    </div>

</div>
</div>
<!-- 主体内容结束 -->
{{ partial('layouts/footer') }}
<style>
    .gl{ margin:10px 0;}
    .gl a{ color:#05780a;}
    .gl a:hover{ text-decoration: underline;}
</style>