<style>
ul li {line-height: 37px;}
.c_input {width: 100px;height: 35px;line-height: 35px;border: solid 1px rgb(153, 153, 153);padding-left: 4px;}
</style>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<div class="dialog">
    <form action="/member/dialog/saveeditquo" method="post" id="editquo" style="width:571px; height:405px;" >
    <div class="message">
        <ul>
            <li><span class="label">商品名称：</span>{{ quotation.purchase.title }}</li>
            <li><span class="label">商品规格：</span>{{ quotation.purchase.pcontent.content }}</li>
            <li><span class="label">采购人：</span>{{ quotation.purname }}</li>
            <li><span class="label">采购数量：</span><strong>{{ quotation.purchase.quantity }}{{ goods_unit[quotation.purchase.goods_unit] }}</strong></li>
            <li><span class="label">设置价格：</span><input name="price" value="{{ quotation.price }}" data-rule="required;nimei" data-target="#c_price" type="text" class="c_input" />&nbsp;元/{{ goods_unit[quotation.purchase.goods_unit] }}<i  id="c_price"></i></li>
            <li><span class="label">&nbsp;</span><input type="hidden" value="{{ quotation.id }}" name="quoid" /><input class="fu_btn" style="width:50px;" type="submit" value="确认"></li>
        </ul>
    </div>
    </form>
</div>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>
    
var api = frameElement.api, W = api.opener;

$(function(){
    var options = {
        success: function(data,state,xhr,obj) {
            data = $.parseJSON(data);
            $(obj).find('input[type=submit]').removeAttr('disabled');
            alert(data.msg);
            if(data.state) {
                window.parent.location.reload();
                window.parent.closeDialog();
            } 
        }
    };


    $('#editquo').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字']
        }
    });

    window.parent.dialog.size(450,300);
    
})
</script>