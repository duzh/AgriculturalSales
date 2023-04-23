
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
                <label><input type="radio"{% if key==0 %} checked  {% endif %}  value="{{ key }}" name='gate_id' /><img src="{{ constant('STATIC_URL') }}mdg/{{val}}" /></label>
            {% endfor %} 
        </div>
        
        <div class="bankBtn">选择其他方式支付</div>

        <div class="list" id="bankMor" style="display:none; margin-top:0px;">
            {% for key,val in  bankList %}           
                <label><input type="radio"   value="{{val['gate_id']}}" name='gate_id' /><img src="{{ constant('STATIC_URL') }}mdg/{{val['bank_img']}}" /></label>
            {% endfor %}
            
            
           
        </div>
    </div>
    {{ hidden_field("order_id","value":order.id) }}
    <input class="btn" type="submit" value="确认付款" />
</div>
<style>
.bankBtn{ margin-left: 68px; width:140px; height: 32px; line-height: 32px; text-align: center; color:#fff; font-size: 14px; background:#ccc; cursor: pointer; border:1px solid #ccc; margin-bottom: 20px;}
</style>
<script>
    $('.bankBtn').click(function(){
        $('#bankMor').toggle();
    })
</script>
<form>
<!-- 付款内容 end -->

<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->
</body>
</html>
