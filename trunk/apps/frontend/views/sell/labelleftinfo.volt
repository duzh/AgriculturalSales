<!-- 块 start -->
<div class="other_message">
    <h6>供应商信息</h6>
    <ul>
        <li>
            <span>名称：</span>
            {% if shopgoods %} <em><a style="color:#05780a;" href="http://{{shopgoods.shop_link}}.5fengshou.com/store">
                    {% if sell.uid == 0 %} {{ sell.uname ? sell.uname : '' }}{% else %}{{ user.ext  ? user.ext.name : "无" }} {% endif %}
                </a></em> 
            {% else %} <em>{% if sell.uid == 0 %} {{ sell.uname ? sell.uname : '' }}{% else %}{{ user.ext  ? user.ext.name : "无" }}{% endif %}</em> 
            {% endif %}
        </li>
        <!--  {% if sell.mobile %} <li>
        <span>手机号：</span>
        <em   {% if ! (session.user) %}  id="tel" {% endif %}>{{sell.mobile}}</em>
    </li>
    {% endif %} -->
    <li>
        <span>地区：</span>
        <em>
            {% if sell.uid == 0 %} {{sell.areas_name}} {% else %} {{ user.ext.areas_name ?  user.ext.areas_name : user.ext.address }}{% endif %}
        </em>
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
    <li>
        <span>主营商品：</span>
        <em>
            <?php 
           
            if(isset($service) && $service) {
                echo Mdg\Models\ShopCoods::getgoodsname($service['shop_id']);
            }elseif($shopgoods){
                echo Mdg\Models\ShopCoods::getgoodsname($shopgoods->shop_id);
            }else{
                echo "暂无";
            } 
            ?></em>
    </li>

</ul>
</div>
<!-- 块 end -->