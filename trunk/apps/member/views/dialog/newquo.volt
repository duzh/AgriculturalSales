<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/version2.4/css/line.css" />
<style>ul li {line-height: 37px;}</style>

<!-- 报价弹框 start -->
<div style="width:550px; height:710px; overflow:hidden;">
<form action="/member/dialog/savequo" method="post" id="newquo">
    <div class="dialog f-oh">
        <input type="hidden" name="purid" value="{{ purchase.id }}" />    
        <ul>
            <li style="margin-bottom:0;">
                <span class="label">商品名称：</span> <em>{{ purchase.title }}</em>
            </li>
            <li style="margin-bottom:0;">
                <span class="label">商品简介：</span> 
                <em style="width:390px; height:37px; overflow:hidden;">{{ purchase.pcontent.content}}</em>
            </li>
            <li style="margin-bottom:0;">
                <span class="label">采购数量：</span>
                <em>{{ purchase.quantity > 0 ? purchase.quantity : "不限/" }}{{ goods_unit[purchase.goods_unit] }}</em> 
            </li>
            <li style="margin-bottom:0;">
                <span class="label">采购人：</span>
                <em>{{ purchase.username }}</em>
            </li>
            <li style="margin-bottom:0;">
                <span class="label">联系方式：</span>
                <em id="tel">{{ purchase.mobile }}</em>
            </li>
            <li>
                <span class="label">信息：</span>
                <em>已有： <strong class="color1">{{ quototal }}</strong>
                    &nbsp; <font style="*font-size:12px;">家报价</font>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font style="*font-size:12px;">报价截止：</font> <strong class="color2">{{ date('Y-m-d H:i:s', purchase.endtime) }}</strong></em> 
            </li>
            <li>
                <span class="label">您的报价：</span>
                <em class="clearfix" style="width:406px;">
                    <input class="f-fl" style="margin-right:4px;" type="text" name="price" value="" data-rule="required;nimei;length[~8];range[0.01~]" data-target="#c_price" />
                    <font style="display:inline-block; float:left; line-height:37px;">元/{{ goods_unit[purchase.goods_unit] }}</font> 
                    <i id="c_price"></i>
                </em>
            </li>
            <li>
                <span class="label">规格描述：</span>
                <em>
                    <textarea name="spec" value="" data-rule="required;length_name;length[1~255]" data-target="#c_spec"></textarea>
                     <i id="c_spec"></i>
                </em>
            </li>
            <li>
                <span class="label">发货人姓名：</span>
                <em>
                    <input type="text" name="sellname" value="{{ users.ext.name }}" data-target="#c_sellname" />
                    <i id="c_sellname"></i>
                </em>
            </li>
            <li>
                <span class="label">供应地：</span>
                <em style="width:393px;">
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
                    <select class="selectAreas" name="sareas" data-rule="required;" data-target="#c_areas" >
                        <option value="">请选择</option>
                    </select>
                    <i id="c_areas"></i>
                    <input type="hidden" name="saddress" value="{{ users.ext.address }}" />
                </em>
            </li>

            <li>
                <span class="label">电话：</span>
                <em>
                    <input type="text" name="sphone" value="{{ users.username }}" data-rule="required;" data-target="#c_sphone" />
                    <i id="c_sphone"></i>
                </em>
            </li>
        </ul>
        <div class="btn">
            <input type="hidden" name="purid" value="{{ purchase.id }}" />
            <input class="submit_btn" type="submit" value="确认" />
            <span class="label">不合理的报价会被系统屏蔽</span>
        </div>   
    </div>
</form>
</div>
<!-- 报价弹框 end -->
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>
var api = frameElement.api, W = api.opener;

$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 100}
});

$(function(){
    $('#newquo').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^(0\.[0-9][1-9]*|[1-9]\d*(\.\d{1,2})?)$/, '请输入正确的数字'],
            xx:[/^1[3-9]\d{9}$/, "手机号格式不正确"],
            xxx : [/^\d+(\.\d{2})?$/, '小数点后保留两位']
        },
        fields:  {
         sellname:"required;fuck;length[2~10]",
         sphone:"required;xx"
        }
        // beforeSubmit: function(form){
        //     $('.submit_btn').attr('disabled', true);
        // }
    });
    $('#newquo').on('valid.form', function(e, form){

           $('.submit_btn').attr('disabled', true);
           //$('.submit_btn').removeAttr('disabled');
    });
    // $('#newquo').on('invalid.form', function(e, form, errors){
    //     //alert(4);
    //        // $('.submit_btn').attr('disabled', true);
    // });
  // window.parent.dialog.size(520,660);
   window.parent.setTitle('确定报价');
})
</script>