<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
    <div class="main_right">
        <div class="bt2">新增货源</div>
        {{ form("cargo/create", "method":"post","id":"addpur") }}
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">

                <tr>
                    <td class="cx_title">姓名：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="contact_man" name='contact_man' value="" />
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">电话：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="contact_phone" name='contact_phone' value="" />
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">货物名：</td>
                    <td class="cx_content">
                        <input type="text" name='goods_name' class="txt"   id="goods_name" value="" />
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">货物种类：</td>
                    <td class="cx_content">
                        <select name="goods_type" id="goods_type">
                            <option value="">请选择</option>
                            {% for key , item in _GOODS_TYPE %}
                            <option value="{{ key }}">{{ item }}</option>
                            {% endfor %}
                        </select>

                    </td>
                </tr>

                <tr>
                    <td class="cx_title">重量：</td>
                    <td class="cx_content">
                        <input type="text" name='goods_weight' class="txt"   id="goods_weight" value="" />/吨

                    </td>
                </tr>
                <tr>
                    <td class="cx_title" >体积：</td>
                    <td class="cx_content" >
                        <input name="goods_size" id="goods_size" type="text" class="tex">/方</td>
                </tr>
                <tr>
                    <td class="cx_title" >期望报价：</td>
                    <td  class="cx_content">
                        <input name="except_price" id="except_price" type="text" class="tex">元/吨</td>
                </tr>
                <tr>
                    <td class="cx_title" >车体长度：</td>
                    <td  class="cx_content">
                        <input name="except_length" id="except_length" type="text" class="tex">/米</td>
                </tr>
                <tr>
                    <td class="cx_title" >厢型：</td>
                    <td class="cx_content">
                    <select name="box_type" id="box_type">
                            <option value="">请选择</option>
                            {% for key , item in _BOX_TYPE %}
                            <option value="{{ key }}">{{ item }}</option>
                            {% endfor %}
                    </select>
                </td>
                </tr>
                <tr>
                    <td class="cx_title">车体：</td>
                    <td class="cx_content">
                    <select name="body_type" id="body_type">
                            <option value="">请选择</option>
                            {% for key , item in _BODY_TYPE %}
                            <option value="{{ key }}">{{ item }}</option>
                            {% endfor %}
                    </select>
                </td>
                </tr>

                <tr>
                    <td class="cx_title">有效时间：</td>
                    <td class="cx_content">
                        <input type="text" id="expire_time" name="expire_time" ></td>

                    </td>
                </tr>

                <tr>
                    <td class="cx_title">是否长期：</td>
                    <td class="cx_content">
                        <select name="is_longtime" id="is_longtime" class="sel w sel-province">
                            <option value="0" >否</option>
                            <option value="1" selected >是</option>
                        </select>

                    </td>
                </tr>

                <tr>
                    <td class="cx_title">结算方式：</td>
                    <td class="cx_content">
                       <input type="text" id="settle_type" name="settle_type" >
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">出发地：</td>
                    <td class="cx_content">
                        <select name="start_pid" class='selectAreas' id="" data-target="#chufadi">
                            <option value="">请选择</option>
                        </select>
                        <select name="start_cid" class='selectAreas' id="">
                            <option value="">请选择</option>
                        </select>

                        <select name="start_did" class='selectAreas' id="">
                            <option value="">请选择</option>
                        </select>
                        <span id="chufadi"></span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">目的地：</td>
                    <td class="cx_content">
                        <select name="end_pid" class='endselectAreas' id="" data-target="#mudidi">
                            <option value="">请选择</option>
                        </select>
                        <select name="end_cid" class='endselectAreas' id="">
                            <option value="">请选择</option>
                        </select>
                        <select name="end_did" class='endselectAreas' id="">
                            <option value="">请选择</option>
                        </select>
                        <span id="mudidi"></span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">备注：</td>
                    <td class="cx_content1">{{ text_area("demo") }}</td>
                </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
            <input type="submit" value="发布" class="sub"/>
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
<script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
document.getElementById(show_div).style.zIndex = 20;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
// bgdiv.style.height = $(document).height();
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
</script>
<script>

$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   
    style : {"width" : 140}
});
//  目的地
$(".endselectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   
    style : {"width" : 140}
});
// 发车时间
$(function(){
    $("#expire_time").datetimepicker();
});
$("#addpur").validator({
     rules: {
          whd: [/^[a-zA-Z0-9]+$/, '只能输入字母或数字'], 
          par: [/^-?\d+\.?\d{0,2}$/, '请输入正确重量可保留两位小数'],
          data_time:[/^\d{4}\/\d{2,2}\/\d{2,2}\s\d{2,2}:\d{2,2}$/, "请输入正确的日期,例:yyyy-mm-dd"],
     },
     fields:  {
         contact_man:"姓名:required;chinese",
         contact_phone:"电话:required;mobile",
         goods_name: "货物名称:required;",
         goods_type : "货物种类:required;",
         goods_weight  : "重量:required;par",
         goods_size : "体积:required;par",
         except_price : "期望报价:required;par",
         except_length : "车体长度:required;par",
         box_type : "厢型:required;",
         body_type : "车体:required;",
         expire_time : "有效时间:required;data_time",
         is_longtime  :"是否长期:required;",
         start_pid:'出发地:required;',
         end_pid:'目的地:required',
     },
});

</script>
{{ partial('layouts/bottom') }}
</body>
</html>