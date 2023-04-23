<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->

<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}我的云农宝</span>
</div>
<div class="personal_center w960 mb20">
    <!-- 左侧 start -->
    {{ partial('layouts/navs_left') }}
    <!-- 左侧 end -->

    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <div class="bind_account">
            <ul class="title clearfix">
                <li class="f-db f-fl">1、验证手机</li>
                <li class="f-db f-fl active">2、完成</li>
            </ul>
            <?php if($value == 1 ) { ?>
            <div class="success">
                <div class="gx">
                    <span>恭喜您</span>
                </div>
                <p>
                    您的手机号： <font><span id='telChange'> <i>{{mobile}}</i>
                        </span>
                        {{  message }}</font> 
                </p>
                {% if value == 1 %}
                <p> <font color=red>恭喜你已经成功绑定云农宝了，您的初始支付密码是登录密码，为了您的安全，建议你立即更改初始支付密码</font>
                </p>
                {% endif %}
                <a href="/member/account/index">查看我的余额</a>
            </div>

            <?php }else { ?>
            <div class="success">
                <div ></div>
                <p>
                    您的手机号：
                    <font><span id='telChange'> <i>{{mobile}}</i>
                        </span>
                        {{  message }}</font> 
                </p>

            </div>

            <?php } ?></div>
    </div>
    <!-- 右侧 end -->
</div>
<!-- 主体内容结束 -->
<script type="text/javascript">
    $(function(){
    var text = $('#telChange i').text();
    var val = text.substring(0,3) + '****' + text.substring(7,11);
    $('#telChange i').text(val);
   
});

</script>
<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->

</body>
</html>