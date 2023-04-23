<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<form action="/member/dialog/saveconquo" method="post" id="confirmquo">
    <div class="dialog" style="width:500px;">
        <input type="hidden" name="quoid" value="{{ quoid }}" />
        <ul>
            <li>
                <span class="label">收货人手机号：</span> 
                <em>
                    <input type="text" name="purphone" value="{{ users.username }}" data-rule="required" data-target="#c_purphone" /> 
                    <i id="c_purphone"></i>
                </em> 
            </li>
            <li>
                <span class="label">采购数量：</span> 
                <em class="f-oh" style="width:392px;">
                    <input class="f-fl" style="margin-right:4px;" type="text" name="quantity" value=""  data-rule="required;integer[+];length[~8]" data-target="#c_quantity" /> 
                    <font class="f-fl" style="display:inline-block; line-height:37px; font-size:12px;">{{ goods_unit[purchase.goods_unit]}}</font> 
                    <i id="c_quantity"></i>
                </em> 
            </li>
            <li>
                <span class="label">收货人姓名：</span>
                <em>
                    <input type="text" name="purname" value="{{ users.ext.name }}"   data-target="#c_purname" />
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
                    <select class="selectAreas" name="areas" data-rule="select" data-target="#c_areas" >
                        <option value="0" >请选择</option>
                    </select>
                    <i id="c_areas"></i>
                    <input type="hidden" id="address" name="address" value="{{ users.ext.address }}" />
                </em>

            </li>
            <li>
                <span class="label">服务工程师：</span>
                <em class="f-oh" style="width:392px;">
                    <input class="f-fl" style="margin-right:4px;" type="text" placeholder='请输入工程师手机号'   name="engphone" data-rule="mobile" data-target="#c_engphone" value="" /> 
                    <font class="f-fl" style="display:inline-block; line-height:37px; font-size:12px;">(选填)</font>
                    <i id="c_engphone"></i>
                </em>
            </li>           
            <!-- <li>
                <em>
                    <i id="c_address"></i>
                </em>
            </li> -->
        </ul>
        <input class="submit_btn" type="submit" value="确认"></form>
</div>
</form>
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

$(function(){

    $('#confirmquo').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            xx:[/^1[3-9]\d{9}$/, "手机号格式不正确"],
            float  : [/^([0-9])+(\.([0-9]){1,2})?$/, '请输入数字']
        },
        fields:  {
         purname:"required;fuck;length[2~10]",
         purphone:"required;xx",
         engphone:'用户名:mobile;remote[/ajax/checkengphone]'
     }
    
    });
    window.parent.dialog.size(550,325);
})
</script>