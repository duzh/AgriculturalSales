<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <!-- 左侧 -->
        
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">

                <div class="post-offer f-oh pb30">
                    <div class="title f-oh">
                        <span>发布采购</span>
                    </div>
                    <form action="/member/dialog/savepur" method="post" id="newpur">
                        <div class="m-title">基本信息</div>
                        <div class="message clearfix">
                            <font>
                                <i>*</i>采购产品：
                            </font>
                            <div class="inputBox inputBox1 f-pr">
                                <input data-rule="采购产品:required;length_name;length[5~30]" name="title"  class="input1" type="text">
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>
                                <i>*</i>产品分类：
                            </font>
                            <div class="selectBox selectBox1 f-pr">
                                <select class="select1 selcate " name="maxcategory">
                                    <option value="">选择分类</option>
                                </select>
                                <select class="select1 selcate " name="category" data-rule="required" >
                                    <option value="">选择分类</option>
                                </select>
                            </div>
                        </div>
                        <div class="line"></div>
                        <div class="m-title">详细信息</div>
                        <div class="message clearfix">
                            <font>
                                <i>*</i>采购数量：
                            </font>
                            <div class="inputBox inputBox5 f-pr clearfix">
                                <input name="quantity" class="input1 f-fl" type="text"  data-rule="采购数量:required;float;length[~8]" >
                                <select class="select2" name="goods_unit" style="margin-left:4px;">
                                    {% for key, val in goods_unit %}
                                        {% if key > 0 %}
                                        <option value="{{ key }}">{{ val }}</option>
                                        {% endif %}
                                    {% endfor %}
                                </select>
                                <i class="range f-fl">（0为不限）</i>
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>
                                <i>*</i>报价截止：
                            </font>
                            <div class="inputBox inputBox1 f-pr">
                                <input  class="input1" type="text" name="endtime" id="d_endtime" readonly="true" value='{{ date('Y-m-d') }}' 
                                data-rule="报价截止:required;">
                            </div>
                        </div>
                        <div class="message clearfix">
                            <font>
                                <i>*</i>规格要求：
                            </font>
                            <div class="areaBox f-pr">
                                <textarea name="content" data-rule="规格要求:required;length_name;length[1~500]" placeholder="写下您对商品的规格要求，收到后我们会立即给你回电确认，剩下的交给我们吧"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="member_info" value="1">
                        <input class="post-btn" type="submit" value="确认发布">
                    </form>
                </div>

        </div>
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>
<script>
//var api = frameElement.api, W = api.opener;
$(".selcate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    style : {"width" : 250}
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
