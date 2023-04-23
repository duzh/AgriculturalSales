<style>
ul li {line-height: 37px; list-style: none; }
ul li em{font-style:normal;}
</style>
<div class="dialog" style="width:408px;">
    <form action="/manage/orders/saveprice" method="post" id="editprice">
    <div class="message">
    	<ul>
        	<li><span class="label">姓名：</span><em>{{ order.purname }}</em></li>
        	<li><span class="label">电话：</span><em>{{ order.purphone }}</em></li>
        	<li><span class="label">购买数量：</span><em><strong>{{ order.quantity }}{{ goods_unit[order.goods_unit] }}</strong></em></li>
            <li><span class="label">收货地址：</span><em>{{ order.address }}</em></li>
            <li><span class="label">设置价格：</span><em><input class="mr10" type="text" name="price" value="{{ order.price }}" data-rule="required;nimei" data-target="#c_price" />元/{{ goods_unit[order.goods_unit] }} <i id="c_price"></i></em></li>
        	<li><span class="label">&nbsp;</span><em><input type="hidden" name="oid" value="{{ order.id }}" /><input class="fu_btn" type="submit" value="确认订单" /></em></li>
        </ul>
        
    </div>
    </form>
</div>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator-src.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script>
    
var api = frameElement.api, W = api.opener;

$(function(){
    $('#editprice').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字']
        }
    });
    
    window.parent.dialog.size(450,180);
})

</script>