{{ partial('layouts/page_header') }}
	<div class="wrapper">
        <div class="w1190 mtauto clearfix">
            <div class="bread-crumbs w1185 mtauto">
               {{ partial('layouts/ur_here') }}供应推荐
            </div>
            <!-- 左侧 -->
            {{ partial('layouts/navs_left') }}
            <!-- 右侧 -->
            <div class="center-right f-fr">
                <div class="my-information">
                
                    <div class="title f-oh"><?php echo Mdg\Models\Message::$_type[$message->type];?></div>
                    <div class="message clearfix">
                        <font class="name">{% if message.type == 1 %}
                            供应商名称：
                            {% else %}
                            采购商名称：
                            {% endif %}</font>
                        <div class="msg-box">{{message.buyer_name}}</div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">联系人：</font>
                        <div class="msg-box">{{message.contact_man}}</div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">联系电话：</font>
                        <div class="msg-box">{{message.contact_phone}}</div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">
                            {% if message.type == 1 %}
                            供应商品名称：
                            {% else %}
                            采购商品名称：
                            {% endif %}</font>
                        <div class="msg-box">{{message.goods_name}}</div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">
                            {% if message.link_type != 2 %}
                            {% if message.type == 1 %}
                            供应要求：
                            {% else %}
                            采购要求：
                            {% endif %}
                            {% endif %}</font>
                        <div class="msg-box">{{message.require}}</div>
                    </div>
                    {% if messageinspect %}
                    <div class="message clearfix">
                        <font class="name">采购规格：</font>
                        <div class="msg-box">{{messageinspect.spec}}</div>
                    </div>
                    {% endif %}
                    
                </div>
            </div>

        </div>
    </div>
</div>

    <!-- 底部 -->
{{ partial('layouts/footer') }}

  <style>
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f; margin-left:75px;}
</style>

