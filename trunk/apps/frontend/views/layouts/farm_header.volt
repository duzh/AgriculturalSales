<div class="trustFarm-header f-oh">
    {% if farm_logo.logo_pic %}
    <div class="tf-logo f-fl">
        <a href="#">
            <img src="{{ constant('IMG_URL')}}{{farm_logo.logo_pic}}" height="61" width="96" alt="">
        </a>
    </div>
    {% endif %}
    {% if farm_header %}
    <div class="tf-con f-fl">
        <strong>{{farm_header ? farm_header.farm_name : '' }}</strong>
       <!--  <font style="margin-left:30px;margin-top:20px"><a href="javascript:void(0);" onclick = "collectFarm({{ farm_logo.id }});" id="col_{{ farm_logo.id }}">
        {% if collectstore %}
        <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/trusted-farm/cancel_farm_collect.png">
        {%else%}
        <img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/trusted-farm/farm_collect.png">
        {% endif %}</a>
        </font> -->
        <p style="width:550px">
           {% if farmdesc %}<?php echo mb_substr($farmdesc, 1,75);?>{% else %}{{ farm_header ?  farm_header.describe : ''}}{% endif %}
        </p>
    </div>
    {% endif %}
</div>
<ul class="trustFarm-nav clearfix">
    <li {% if nav=='indexfarm' %}class="active" {% endif %} >
        <a href="/indexfarm/index">农场首页</a>
        <div class="border"></div>
    </li>
    <li {% if action=='farmplant' %}class="active" {% endif %}>
        <a href="/crediblefarm/farmplant/">种植过程</a>
        <div class="border"></div>
    </li>
    <li {% if action=='goodslist' %}class="active" {% endif %}>
        <a href="/crediblefarm/goodslist">所有产品</a>
        <div class="border"></div>
    </li>
    <li {% if nav=='qualifications' %}class="active" {% endif %}>
        <a href="/qualifications/memberindex">资质认证</a>
        <div class="border"></div>
    </li>
    <li {% if nav=='picturewall' %}class="active" {% endif %}>
        <a href="/picturewall/memberindex">图片墙</a>
        <div class="border"></div>
    </li>
</ul>
<script>
    function collectFarm(farmId){

    $.ajax({
        type:"POST",
        url:"/indexfarm/collectFarm",
        data:{farmId:farmId},
        dataType:"json",
        success:function(msg){      
            if(msg['code'] == 0){
                newWindows('login', '登录', "http://www{{ constant('CUR_DEMAIN') }}/member/dlogin/index?ref=/{{nav}}/{{action}}&islogin=1");
            } else if(msg['code'] == 4){
                alert(msg['result']);
                $("#col_"+farmId).html('<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/trusted-farm/cancel_farm_collect.png">');
                //window.location.reload();
                return;
            } else if( msg['code'] == 6 ){
                alert(msg['result']);
                $("#col_"+farmId).html('<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/trusted-farm/farm_collect.png">');
                //window.location.reload();
                return;
            } else {
                alert(msg['result']);
                return;
            }
        }
    });
}
</script>   