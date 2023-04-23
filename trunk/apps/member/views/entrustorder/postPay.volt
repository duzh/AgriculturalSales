{{ partial('layouts/page_header') }}

<div class="wrapper f-oh">
        <div class="subsidy w1185 mtauto mt10 f-oh">
            <div class="box">

                <div class="success f-tac">您的订单已经提交，请尽快支付!</div>
                <div class="order-num f-tac">订单号：{{ order.order_sn }}</div>
                <div class="order-price f-tac">应付金额：<strong>¥{{ order.goods_amount }}</strong></div>
                <!-- 使用补贴 -->
                
                {% if 0 %}
                {{ partial('layouts/order_subsidy') }}
                {% elseif  0 %}
                <div class="matter f-tac">
                    <span>
                        您已使用补贴：
                        <strong>{{ order.subsidy_total }}</strong>
                        元
                    </span>
                </div>
                {% else %}

                {% endif %}

                <div class="border"></div>
                <div class="lave-balance">
                    <div class="lave-price" id="make_money">还需支付金额：{{ order.order_amount }}元</div>
                    <div class="lave-style f-oh">
                        <font class="f-db f-fl">请选择支付方式：</font>
                        <span class="f-db f-fl">
                            <!-- <a href="/member/entrustorder/pay?id={{order.order_id}}&ptype=YNP"><img src="{{ constant("STATIC_URL")}}/mdg/version2.4/images/subsidy/bank-img1.png"></a> -->
                            <a href="/member/entrustorder/pay?id={{order.order_id}}&ptype=ABC"><img src="{{ constant("STATIC_URL")}}/mdg/version2.4/images/subsidy/bank-img2.png"></a>
                        </span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

<!-- 底部 -->
{{ partial('layouts/footer') }}
