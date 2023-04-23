<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /> 
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<form action="/member/dialog/savebuy" method="post" id="newbuy">
<div class="dialog" style="width: 500px;">
    
     <input type="hidden" name="address" value="{{ users.ext.address }}"> 
     <input type="hidden" name="sellid" value="{{ sell.id }}" />
     <input type="hidden" name="areas" id="area" value="{{users.areas}}"/>
    <ul>
        <li><span class="label">收货人手机号：</span>
		<em>
		<input type="text" name="purphone" data-rule="required;mobile" data-target="#c_purphone" value="{{ users.username }}" />
		<i id="c_purphone"></i>
		</em>
		</li>		
        <li>
			<span class="label">采购数量：</span>
			<em class="clearfix" style="width:390px;">
				<input type="text" style="color:#ccc; float:left;margin-right:4px;" name="quantity"  value="{% if minnumber == 0 %}数量不限{% else %}不小于{{minnumber}}{% endif %}" data-rule="required;integer[+];length[~8];remote[/member/ajax/checkquantity , sellid]" data-target="#c_quantity" onfocus="if(this.value == this.defaultValue) {this.value = '';this.style.color='#333'}" onblur="if(this.value == '') {this.value = this.defaultValue; this.style.color='#ccc'}"  />
				<font style="font-size:12px; display:inline-block; float:left; line-height:37px;">{{sell.goods_unit ? goods_unit[sell.goods_unit] : "不限" }}</font>
				<i id="c_quantity"></i>
			</em>
        </li>
        <li>
			<span class="label">收货人姓名：</span>
			<em>
				<input type="text" name="purname"  data-rule="required;fuck;length[2~10]" data-target="#c_purname" value="{{ users.ext.name }}" />
				<i id="c_purname"></i>
			</em>
		</li>
        <li>
            <span class="label">收货地址：</span>
            <em style="width:392px;">
                <select class="selectAreas">
                    <option>省</option>
                </select>
                <select class="selectAreas">
                    <option>市</option>
                </select>
                 <select class="selectAreas">
                    <option>县/区</option>
                </select>
                 <select class="selectAreas">
                    <option>请选择</option>
                </select>
                 <select class="selectAreas" name="areasid" data-rule="required;" data-target="#c_areas" >
                    <option value=''>请选择</option>
                </select>
                <i id="c_areas"></i>
            </em>
        </li>

        <li>
			<span class="label">服务工程师：</span>
			<em class="clearfix" style="width:390px;">
				<input style="float:left;margin-right:4px;" type="text" name="engphone" data-rule="mobile" data-target="#c_engphone" value="" /> 
                <font style="font-size:12px; display:inline-block; float:left; line-height:37px;">(选填)</font>
				<i id="c_engphone"></i>
			</em>
        </li>

    </ul>
   
    <input class="submit_btn"  type="submit"  value="确认" />
    
</div>
</form>
<script>
$('select[name=areasid]').change(function(){

    $("#area").val($(this).find('option:selected').val());

    //console.log( $(this).find('option:selected').text() + '|' + $(this).find('option:selected').val())
})
</script>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>
var api = frameElement.api, W = api.opener;

$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 120}
});
// var submitcount = 0;
$(function(){

    $('#newbuy').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字'],
            twofloat : [/^\d+(.\d{1,2})?$/, '小数点后保留两位']
        },
        fields: {
            'engphone': '用户名:mobile;remote[/ajax/checkengphone]'
        },
        beforeSubmit: function(form){
            $('.submit_btn').attr('disabled', true);
        }
    });
    $('input[name="quantity"]').on('valid.field', function(e, result, me){
        $('.submit_btn').removeAttr('disabled');
    });
    $('input[name="purphone"]').on('valid.field', function(e, result, me){
        $('.submit_btn').removeAttr('disabled');
    });
    $('input[name="purname"]').on('valid.field', function(e, result, me){
        $('.submit_btn').removeAttr('disabled');
    });
    $('input[name="areasid"]').on('valid.field', function(e, result, me){
        $('.submit_btn').removeAttr('disabled');
    });
    $('input[name="engphone"]').on('valid.field', function(e, result, me){
        $('.submit_btn').removeAttr('disabled');
    });
    window.parent.dialog.size(550,300);
    window.parent.setTitle('确认采购信息');
})
</script>