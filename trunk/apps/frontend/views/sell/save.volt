<!-- 弹框 start -->

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-1.11.1.min.js"></script>
<!-- 回执信息 start -->
<div class="hz_alerter" style="display:block" >
    <p>下单成功，您可以在"个人中心"—"我的订单"中找到</p>
    <div class="btn">
        <a class="btn1 f-fl" href="{{orderid}}"  onclick="guanbi()" id="success">查看订单</a><a class="btn2 f-fl" href="" onclick="guanbi()">继续看货</a>
    </div>
</div>
<!-- 回执信息 start -->
<script>
	function guanbi(){

		window.parent.parent.closeDialog1();
	}
</script>