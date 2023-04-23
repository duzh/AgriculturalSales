<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
    <div class="main_right">
        <div class="bt2">新增车源</div>
        {{ form("car/create", "method":"post","id":"addpur") }}
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">姓名：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="contact_man" name='contact_man' value="" />
                        
                    </td>
                </tr>


                <tr>
                    <td class="cx_title">手机号：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="contact_phone" name='contact_phone' value="" />
                        
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">车牌号：</td>
                    <td class="cx_content">
                        <input type="text" name='car_no' class="txt"   id="car_no" value="" />
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">厢型：</td>
                    <td class="cx_content">
                        <select name="truck_type_id" id="truck_type_id">
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
                        <select name="body_type" id="">
                            <option value="">请选择</option>
                            {% for key , item in _BODY_TYPE %}
                            <option value="{{ key }}">{{ item }}</option>
                            {% endfor %}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" >长度：</td>
                    <td class="cx_content" >
                        <input name="length" id="length" type="text" class="tex">米</td>
                </tr>
                <tr>
                    <td class="cx_title" >载重：</td>
                    <td  class="cx_content">
                        <input name="carry_weight" id="carry_weight" type="text" class="tex">吨</td>
                </tr>
                <tr>
                    <td class="cx_title" >车龄：</td>
                    <td class="cx_content">
                        <input name="use_time" id="use_time" type="text" class="tex">年</td>
                </tr>
                <tr>
                    <td class="cx_title">发车时间：</td>
                    <td class="cx_content">
                       <input type="text" id="depart_time" name="depart_time" >
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">运行方式：</td>
                    <td class="cx_content">
                        <select name="transport_type" id="transport_type" class="sel w sel-province">
                              <option value="" >请选择</option>
                              {% for key , item in _TRANSPORT_TYPE %}
                                <option value="{{ key }}">{{ item }}</option>
                                {% endfor %}
                              
                          </select>

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
                    <td class="cx_title">轻货：</td>
                    <td class="cx_content">
                        <input type="text" name='light_goods' value=''>元 / 方
                    </td>
                </tr>


                <tr>
                    <td class="cx_title">重货：</td>
                    <td class="cx_content">
                        <input type="text" name='heavy_goods' value=''>元 / 吨
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">备注：</td>
                    <td class="cx_content1">{{ text_area("content") }}</td>
                </tr>
                <tr>
                    <td class="cx_title">验证码:</td>
                    <td class="cx_content1">
                        <input class="f-fl" type="text" name="img_yz" id='img_yz' data-target="#yzTips" />
                            <img class="f-fl" src="/member/code/index"  id='codeimg' onclick="javascript:this.src='/member/code/index?tm='+Math.random();" />
                            <a class="f-fl" href="javascript:;" onclick="fun()" >看不清换一张</a> 
                            <i class="pa" id="yzTips"></i>
                    </td>
                </tr>

            </table>

        </div>
        <div align="center" style="margin-top:20px;">
            <input type="submit" value="添加" class="sub"/>
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
    $("#depart_time").datetimepicker();
});

$("#addpur").validator({
     rules: {
          whd: [/^[a-zA-Z0-9]+$/, '只能输入字母或数字'], 
          par: [/^-?\d+\.?\d{0,2}$/, '请输入正确单位,可保留两位小数'],
          parq: [/^-?\d+\.?\d{0,2}$/, '请输入正确轻货价格,可保留两位小数'],
          parz: [/^-?\d+\.?\d{0,2}$/, '请输入正确重货价格,可保留两位小数'],
          cheNum : [ /^[\u4E00-\u9FA5][\da-zA-Z]{6}$/ , '请输入正确的车牌号,例:京BXXXXX'],
          data_time:[/^\d{4}\/\d{2,2}\/\d{2,2}\s\d{2,2}:\d{2,2}$/, "请输入正确的日期,例:yyyy-mm-dd"],
     },
     fields:  {
         contact_man:"姓名:required;chinese",
         contact_phone:"手机号:required;mobile",
         car_no: "车牌号:required;cheNum",
         truck_type_id : "箱型:required;checked;",
         body_type  : "车体:required;checked;",
         length : "长度:required;par",
         carry_weight : "载重:required;par",
         use_time : "车龄:required;par",
         depart_time : "发车时间:required;data_time",
         transport_type : "运行方式:required;checked;",
         is_longtime : "是否长期:required;checked;",
         start_pid  :"出发地:required;",
         end_pid:'目的地:required;',
         light_goods:'轻货:parq;',
         heavy_goods:'重货:parz;',
         img_yz:"验证码:required;remote[/wuliu/car/checkimgcode ];",
     },
});
</script>
{{ partial('layouts/bottom') }}
</body>
</html>