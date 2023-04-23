{{ partial('layouts/page_header_old') }}
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}服务站管理</span>
</div>
<div class="personal_center w960 mb20">
    <!-- 左侧导航栏 start -->
    {{ partial('layouts/navs_old_left') }}
    <!-- 左侧导航栏 end -->

    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <!-- 服务站基本资料 start -->
        <div class="service-station-message f-oh">
            <div class="title">服务站基本资料</div>

            <div class="message f-oh">
                <ul class="f-fl" style="width:440px;">
                    <li>
                        <span>联系人：</span> <em>{{ shop['contact_man']}}</em>
                    </li>
                    <li>
                        <span>服务站编号：</span> <em>{{ shop['shop_no']}}</em>
                    </li>
                    <li>
                        <span>电话：</span>
                        <em>{{ shop['contact_phone']}}</em>
                    </li>
                    <li>
                        <span>地址：</span>
                        <em>{{ addressArea }}</em>
                    </li>
                    <li>
                        <span>主营产品：</span>
                        <em>{{ shopgoods }}</em>
                    </li>
                    <li>
                        <span>负责区域：</span>
                        <em>
                            {{province}} {{city }} {{distinct}}
                            <br>
                            {{ Viewareas }}
                            </em> 
                    </li>
                </ul>
                <div class="pic f-fr">
                    <img src="{{ constant("IMG_URL")}}/{{credit.personal_logo_picture}}" width='144px' height='144px' />
                </div>
                <div class="more f-fr">
                    <a href="/member/service/look">查看更多信息</a>
                </div>
            </div>

            <div class="about-tuijian">
                {% for key,val in sellData['items'] %}
                <div class="list">
                    <dl class="clearfix">
                        <dt>{{ sellData['start'] + key + 1 }}</dt>
                        <dd>
                            <span>供应推荐</span>
                            <p>
                                供应信息推荐：{{ val['uname']}}推出了 {{val['title']}}，
                                <a href="/sell/info/{{val['id']}}">点击查看详情！</a>
                            </p>
                        </dd>
                    </dl>
                    <div class="time">{{date('Y-m-d H:i:s', val['createtime'])}}</div>
                </div>
                {% endfor %}
            </div>

            <!-- 分页 -->
            <div class="page">{{sellData['pages']}}</div>

        </div>
        <!-- 服务站基本资料 end --> </div>
    <!-- 右侧 end -->

</div>

<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->
</body>
</html>
p