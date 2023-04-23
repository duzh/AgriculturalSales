{{ form("purchase/save", "method":"post","id":"addpur") }}

{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
    <div class="main_right">
        <div class="bt2">添加采购信息</div>
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">采购商品名称：</td>
                    <td class="cx_content"><input type="text" name="title" value="{{ purchase.title }}" /></td>
                </tr>
                <tr>
                    <td class="cx_title">所属分类：</td>
                    <td class="cx_content">
                        <select name="" class="selectCate">
                            <option value="">选择分类</option>
                        </select>
                        <select name="category" class="selectCate">
                            <option value="">选择分类</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">采购数量：</td>
                    <td class="cx_content">
                        <input type="text" name="quantity" value="{{ purchase.quantity }}" />
                        <select name="goods_unit" id="goods_unit" style="width: 120px;">
                            {% for key, val in goods_unit %}
                            <option value="{{ key }}">{{ val }}</option>
                            {% endfor %}
                        </select>
                        <span class="msg-box" style="position:static;" for="quantity"></span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">报价截止时间：</td>
                    <td class="cx_content"><input type="text" name="endtime" value="{{ date('Y/m/d H:i:s', purchase.endtime) }}" id="endtime" class="txt" /></td>
                </tr>
                <tr>
                    <td class="cx_title">采购人姓名：</td>
                    <td class="cx_content">
                        <input type="text" name="username" class="txt" value="{{ purchase.username }}"></td>
                </tr>
                <tr>
                    <td class="cx_title">采购人电话：</td>
                    <td class="cx_content">
                        <input type="text" name="mobile" class="txt" value="{{ purchase.mobile }}"></td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">采购人地址：</td>
                    <td class="cx_content">
                        <div class="cx_content1">
                            <select name="province" class="selectAreas">
                                <option value="0" selected>省</option>
                            </select>
                            <select name="city" class="selectAreas" >
                                <option value="0">市</option>
                            </select>
                            <select name="town" class="selectAreas" >
                                <option value="">区/县</option>
                            </select>
                            <input type="text" name="address" value="{{ purchase.address }}" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">规格要求：</td>
                    <td >
                        <div class="cx_content1" ><textarea name="content" >{% if purchase.pcontent %}{{ purchase.pcontent.content }}{% endif %}</textarea></div>
                    </td>
                </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
            <input type="hidden" name="purid" value="{{ purchase.id }}" />
            <input type="submit" value="修改" class="sub"/>
        </div>
    </div>
    <!-- main_right 结束  -->

</div>
</form>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script>
$(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    {% if curCate %}
    texts : [{{ curCate }}],
    {% endif %}
    style : {"width" : 140}
});
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
    defaultParentId : 0,
    {% if curAreas %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 140}
});
$(function(){
    $("#endtime").datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });
});
$("#addpur").validator({
     rules: {
          
     },
     fields:  {
         title:"required;",
         category:"required;",
         quantity: "required;",
         content : "required;",
         endtime  : "required;",
         town : "required;",
         address : "required;",
     },
    
});
var dialog = null;
function newWindows(id,title,url) {
    var username=$("#user").val();

    if(username==""){
       alert("请输入");
    }else{
        dialog = $.dialog({
        id    : id,
        title : title,
        min   : false,
        max   : false,
        lock  : true,
        width : 600,
        content: 'url:'+url+"/"+username
       });
    }  
}
function closeDialog(){
    dialog.close();
}

</script>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>