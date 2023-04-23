<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />
<!-- 发布采购弹框 start -->
<div class="dialog" style="width:568px;">
    <form action="/member/dialog/savepur" method="post" id="newpur">
    <!-- <h6>发布采购- 帮助您找到物美价廉的商品</h6> -->
    <ul>
        <li><span class="label">采购商品名称：</span><em><input type="text" data-rule="required;length_name;length[5~30]" name="title"  data-target="#c_title" /><i id="c_title"></i></em></li>
       <li>
            <span class="label">所属分类：</span>
            <em>
                <select class="selcate" name="maxcategory">
                    <option>请选择分类</option>
                </select>
                <select  class="selcate" name="category" data-rule="select"  data-target="#c_category" >
                    <option>请选择分类</option>
                </select>
                <i  id="c_category"></i>
            </em>
        </li>
        <li>
            <span class="label">采购数量：</span>
            <em>
                <input type="text" name="quantity" value="" data-rule="required;float;length[~8]" data-target="#c_quantity" />
                <select class="s1" name="goods_unit">
                    {% for key, val in goods_unit %}
                    {% if key > 0 %}
                    <option value="{{ key }}">{{ val }}</option>
                    {% endif %}
                    {% endfor %}
                </select>
                (0为不限)
                <i  id="c_quantity"></i>
            </em>
        </li>
        <li>
            <span class="label">报价截止：</span>
            <em><input type="text" name="endtime" id="d_endtime" readonly="true" value='{{ date('Y-m-d') }}' data-rule="required;" data-target="#c_d_endtime" /><i  id="c_d_endtime"></i></em>
        </li>
        <li>
            <span class="label">规格要求：</span>
            <em><textarea cols="50" rows="5" name="content" data-rule="required;length_name;length[1~500]" data-target="#c_content" placeholder="写下您对商品的规格要求，收到后我们会立即给你回电确认，剩下的交给我们吧"></textarea><i  id="c_content"></i></em>
        </li>
    </ul>
    <p class="f-ff0">委托找货 > 选择报价 > 完成交易</p>
    <input class="submit_btn" type="submit" value="立即帮我找找" />
    </form>
</div>
<!-- 发布采购弹框 end -->


<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>
var api = frameElement.api, W = api.opener;

$(".selcate").ld({ajaxOptions : {"url" : "/ajax/getcate"},

    defaultParentId : 0,
    style : {"width" : 140}
});

$(document).ready(function() {
    var options = {
        success: function(data,state,xhr,obj) {
            data = $.parseJSON(data);
            $(obj).find('input[type=submit]').removeAttr('disabled');
            alert(data.msg);
            if(data.state) {
                window.parent.closeDialog();
            } 
        }
    };

    window.parent.setTitle('发布采购- 帮助您找到物美价廉的商品');


    $('#newpur').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            float  : [/^([0-9])+(\.([0-9]){1,2})?$/, '请输入数字']
        }
    });

    //window.parent.dialog.size(450,400);
    $("#d_endtime").datepicker({
      changeMonth: true,
      changeYear: true,
      minDate: 0
    });
});



</script>