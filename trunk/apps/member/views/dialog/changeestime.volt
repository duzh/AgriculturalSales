<style>
ul li {line-height: 37px;}
</style>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog" style="width:425px; padding-bottom:3px;">
    <form action="/member/dialog/changestimepro" method="post" id="editprice">
             
    <div class="message">
      <ul>
       
            <li><span class="label">{{str}}：</span>
               
               
                   <input id="d411" class="Wdate"  type="text" name="except_shipping_time" onfocus="WdatePicker({skin:'whyGreen',minDate:'2015-08-15',maxDate:'2015-10-20'})"  value="{% if orders.except_shipping_time > 0 %}{{date("Y-m-d",orders.except_shipping_time)}}{% endif %}" />
                <i id="c_price"></i>
            
                
            </li>
    
            <li><span class="label">&nbsp;</span><em>
                <input type="hidden" name="order_id" value="{{ orders.id }}" />
                <input class="fu_btn" type="submit" value="确认修改" /></em>
            </li>
        </ul>
        
    </div>
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
            checkMax: function(element, param, field) {
                switch(param[0]) {
                    case 'scgt':
                        return parseFloat(element.value) > parseFloat($('input[name="'+param[1]+'"]').val()) || '最大价格大于最小价格';
                    case 'cz':
                        return parseFloat(element.value) < parseFloat($('input[name="'+param[1]+'"]').val()) || '最小价格大于最大价格';
                }
            }
        }
    });
    
   window.parent.dialog.size(425,193);
})

</script>
