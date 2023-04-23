{% include 'layouts/page_header.volt' %}
<!-- 主体内容开始 -->
<div class="login_box w960 mt20 mb20 pb30">
    <h6>消息提示</h6>
    <p class="succes_tip">{{msg}}</p>
    <a class="relogin_btn" href="{{url}}">{{text}}</a>
</div>
<!-- 主体内容结束 -->

<!-- 底部开始 -->
<div class="footer">
    <div class="f_contact">
        <div class="left f-fl">
            <dl>
                <dt>新手上路</dt>
                <dd>
                    <img src="{{ constant('STATIC_URL') }}mdg/images/helpList_h_line.png"></dd>
                <dd>
                    <a href="javascript:;">免责条款</a>
                </dd>
                <dd>
                    <a href="javascript:;">产品质量保证</a>
                </dd>
                <dd>
                    <a href="javascript:;">购物流程</a>
                </dd>
            </dl>
            <dl>
                <dt>服务保证</dt>
                <dd>
                    <img src="{{ constant('STATIC_URL') }}mdg/images/helpList_h_line.png"></dd>
                <dd>
                    <a href="javascript:;">售后服务保证</a>
                </dd>
                <dd>
                    <a href="javascript:;">退换货原则</a>
                </dd>
                <dd>
                    <a href="javascript:;">售后流程</a>
                </dd>
            </dl>
            <dl>
                <dt>联系我们</dt>
                <dd>
                    <img src="{{ constant('STATIC_URL') }}mdg/images/helpList_h_line.png"></dd>
                <dd>
                    <a href="javascript:;">关于云农场</a>
                </dd>
                <dd>
                    <a href="javascript:;">联系我们</a>
                </dd>
            </dl>
        </div>
        <div class="f-fl mg">
            <img src="{{ constant('STATIC_URL') }}mdg/images/helpList_s_line.png"></div>
        <div class="f-fl">
            <img src="{{ constant('STATIC_URL') }}mdg/images/footer_contact_img-04.png"></div>
    </div>
</div>
<div class="fIcp">
    <p>Copyright ©2014,版权所有北京天辰云农场有限公司，京ICP备14023165号-2</p>
    <p>北京天辰云农场有限公司 北京市朝阳区东三环中路39号建外SOHO东区9号楼22F</p>
</div>
<!-- 底部结束 -->