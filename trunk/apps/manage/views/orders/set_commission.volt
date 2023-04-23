<style>
ul li {line-height: 37px; list-style: none; }
ul li em{font-style:normal;}
</style>
<div class="dialog" style="width:408px;">
    <form action="/manage/orders/savecommission" method="post" id="set_commission">
    <div class="message">
    	<ul>
            {% if is_edit == 1 and order.commission_party == 2 %}
            <li>
                此订单不能修改采购商佣金,支付交易已创建!
            </li>
            {% endif %}
        	<li><span class="label">佣金支付方：</span>
                <em>

                    {% if is_edit == 1 and order.commission_party == 2 %}
                    采购商
                    {% else %}
                    <select id="commission_party" name="commission_party" data-rule="required;select">
                        <option value="0" {% if (order.commission_party == 0) %}selected=selected{% endif %}>请选择</option>
                        <option value="1" {% if (order.commission_party == 1) %}selected=selected{% endif %}>供应商</option>
                        {% if is_edit != 1 %}<option value="2"  {% if (order.commission_party == 2) %}selected=selected{% endif %}>采购商</option>{% endif %}
                    </select>
                    {% endif %}
                </em>
            </li>
            <li><span class="label">佣金金额：</span>
                <em>
                    {% if is_edit == 1 and order.commission_party == 2 %}
                    {{ order.commission }}
                    {% else %}
                    <input class="mr10" type="text" name="commission" value="{{ order.commission }}" data-rule="required;nimei" data-target="#c_price" />元<i id="c_price"></i>
                    {% endif %}
                 </em></li>
        	<li><span class="label">&nbsp;</span><em><input type="hidden" name="oid" value="{{ order.id }}" /><input type="hidden" name="content" value="{{ content }}" /><input class="fu_btn" type="submit" value="确认设置" /></em></li>
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
    $('#set_commission').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字']
        },
        valid:function(form){
            $('input[type="submit"]').attr('disabled',true);
            if($("#commission_party").val() == 1){
                var ordertotal = parseFloat('{{ order.total }}');
                var commission = parseFloat($("input[name='commission']").val());
                if(ordertotal <= commission ){
                    alert('佣金金额必须小于订单金额!');
                    $('input[type="submit"]').attr('disabled',false);
                    return false;
                }
            }
            form.submit();
            return true;
        }
    });
    
    window.parent.dialog.size(450,180);
})

</script>