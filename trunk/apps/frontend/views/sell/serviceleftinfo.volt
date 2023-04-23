<!--  不是服务商  -->
{% if !service  %} 
<div class="other_message">
    <h6>供应商信息</h6>
    <ul>
        <li>
            <span>名称：</span> 
            <em>
                {%  if shopgoods and  shopgoods.shop_link %}
                <a href="http://{{shopgoods.shop_link}}.5fengshou.com/store">{{ sell.uname }}</a>
                {% else %}
                {{ sell.uname }}
                {% endif %}
        </em>
        </li>
        <li>
            <span>地区：</span> <em>{{ sell.address }}</em>
        </li>
    </ul>
</div>
{% else  %}

<!--是服务商后-->

{% if service %}

<!-- 块 start -->
<div class="other_message">
    <h6>供应商信息</h6>
    <dl class="gys_info f-tac pb20">
        <dt>

            <img src="<?php if(isset($service['personal_logo_picture'])&&$service['personal_logo_picture']) { echo $service['personal_logo_picture'];}?>"  width='68px' height='68px'/></dt>
        <dd class="f-fs14">
            <span class="mr10">
                {% if sell.uid == 0 %}
                <!-- 开通店铺之后 名称链接到店铺详细 -->
                <?php if(isset($shopgoods->shop_link)&&$shopgoods->shop_link){?>
                <a href="http://{{shopgoods.shop_link}}.5fengshou.com/store">{{ sell.uname ? sell.uname : '无' }}</a>
                <?php }else{
                            echo $sell->uname;
                }?>
                        
                {% else %}
                <!-- 未开通店铺 -->
                <?php if(isset($shopgoods->shop_link)&&$shopgoods->shop_link){?>

                <a href="http://{{shopgoods.shop_link}}.5fengshou.com/store">{{ user  ? user.ext.name : "无" }}</a>
                <?php }else{
                            
                            echo isset($user->ext->name) ?$user->ext->name : '-';
                }?>
                <br>
                {% endif %}
                {% if service['shop_no'] %}
                    {{ service['shop_no']}}
                {% endif %}
            </span>
            <a class="south-west-alt" title="丰收汇服务商" href="javascript:;">
                <img src="/mdg/images/fws_icon.png" />
            </a>
        </dd>
        <dd class="jiami f-fs14 mt10">
            <span id="spanMobile">{% if sell.mobile %}{{sell.mobile}}{% endif %}</span>

            <?php if(isset($_SESSION['user']) &&$_SESSION['user']){ ?>
            <a href="javascript:jiami({{sell.mobile}});">查看</a>
            <?php 
                    }else{
                        ?>
            <a href="javascript:newWindows('login', '登录', '/member/dlogin/index?ref=/sell/info/{{sell.id}}&islogin=1');">查看</a>
            <?php } ?></dd>
        <dd class="mt10">
            <a class="sqghs_btn" id='sqghs_btn' href="javascript:newWindows('sqghs_btn', '申请看货服务', '/member/dialog/lookgoods/{{sell.id}}');;">申请看货服务</a>
        </dd>
    </dl>
</div>
<!-- 块 end -->

<!--是服务商后-->
<!-- 块 start -->
<div class="other_message">
    <h6>交易记录</h6>
    <ul>
        <li>
            <span>主营：</span>
            <em><?php  
                        if(isset($service) && $service) {
                            echo Mdg\Models\ShopCoods::getgoodsname($service['shop_id']);
                        }elseif($shopgoods){
                            echo Mdg\Models\ShopCoods::getgoodsname($shopgoods->
                shop_id);
                        }else{
                            echo "暂无";
                        } 
                        ?></em> 
        </li>
        <li>
            <span>成交：</span>
            <em>{% if ordercount %}成交（{{ordercount}}）笔{% else %} 暂无成交 {% endif %}</em> 
        </li>
        {% if shopgrade %}
        <li>
            <span>服务态度：</span>
            <em>{{shopgrade.service}}星</em>
        </li>
        <li>
            <span>陪同程度：</span>
            <em>{{shopgrade.accompany}}星</em>
        </li>
        <li>
            <span>供货能力：</span>
            <em>{{shopgrade.supply}}星</em>
        </li>
        <li>
            <span>描述相符：</span>
            <em>{{shopgrade.description}}星</em>
        </li>
        {% else %}
        <li>
            <span>店铺评分：</span>
            <em>暂无评分</em>
            <p></p>
        </li>
        {% endif %}
    </ul>

    <div class="wt_line">
        <p>
            委托丰收汇向我采购
            <br />
            拨打400 8811 365详细了解
        </p>
    </div>

</div>
{% endif %}
{% endif %}