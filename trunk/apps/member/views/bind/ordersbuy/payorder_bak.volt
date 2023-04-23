
<!-- 头部 start -->
{{ partial('layouts/page_header') }}
<!-- 头部 end -->
{{ form("ordersbuy/payorderpro", "method":"post") }}
<div class="fukuan mt20 w960 f-oh">
	<h5 class="f-fs14">成功提交订单</h5>
    <div class="money">
    	<p class="tip f-fs14">您的订单已经提交，请尽快支付!</p>
        <p class="msg"><span>订单号：{{order.order_sn}}</span><em>|</em><span>应付金额：<strong>&yen;&nbsp;{{order_amount}}</strong></span></p>
    </div>
    <div class="bank">
    	<p>选择网上银行支付：</p>
        <div class="list">
            {% for key,val in  bank %}           
        <label><input type="radio" {% if key==0 %} checked  {% endif %}  value="{{val['gate_id']}}" name='gate_id' /><img src="{{ constant('STATIC_URL') }}mdg/{{val['bank_img']}}" /></label>
            {% endfor %}
        </div>
    </div>
    {{ hidden_field("order_id","value":order.id) }}
    <input class="btn" type="submit" value="确认付款" />
</div>
<form>
<!-- 付款内容 end -->

<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->
</body>
</html>
