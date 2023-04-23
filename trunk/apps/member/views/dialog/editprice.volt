<style>
ul li {line-height: 37px;}
.dialog li span.label{ margin-top:0;}
</style>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog" style="width:500px; padding-bottom:3px;">
    <form action="/member/dialog/saveprice" method="post" id="editprice">
    <div class="message">
        <ul>
            <li><span class="label">姓名：</span><em>{{ order.purname }}</em></li>
            <li><span class="label">电话：</span><em>{{ order.purphone }}</em></li>
            <li><span class="label">购买数量：</span><em><strong>{{ order.quantity }}{{ goods_unit[order.goods_unit] }}</strong></em></li>
            <li><span class="label">收货地址：</span><em style="width:344px; overflow:hidden;">{{ order.address }}</em></li>
            <li>
                <span class="label">设置价格：</span>
                <em class="clearfix" style="width:360px;">
                    <input class="mr10 f-fl" type="text" name="price" value="{{ order.price }}"  data-target="#c_price" />
                    <font class="f-fl" style="display:inline-block; font-size:12px; line-height:37px;">元/{{ goods_unit[order.goods_unit] }}</font> 
                    <i id="c_price"></i>
                </em>
            </li>
            <li>
                <span class="label">&nbsp;</span>
                <em>
                <input type="hidden" name="oid" value="{{ order.id }}" /><input class="fu_btn submit_btn" type="submit" value="确认订单" style="margin:0px!important;margin-bottom:5px;"/>
                </em>
            </li>
        </ul>
        
    </div>
    <input type="hidden" name="min_price" value="{{min_price}}">
    <input type="hidden" name="max_price" value="{{max_price}}">
    </form>
</div>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>
    
var api = frameElement.api, W = api.opener;

$(function(){
    $('#editprice').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字'],
            xxx : [/^(0\.[0-9][1-9]*|[1-9]\d*(\.\d{1,2})?)$/, '请输入正确的数字']
            //xxx : [/^\d+(\.\d{2})?$/, '小数点后保留两位']
            //^(0\.[1-9]\d*|[1-9]\d*(\.\d{1,2})?)$
        },
        fields: {
            price: "价格:required;xxx;remote[/member/dialog/checkprice, min_price, max_price, price]"
        }
    });
    
    //window.parent.dialog.size(450,297);
})

</script>