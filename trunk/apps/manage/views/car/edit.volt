<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
    <div class="main_right">
        <div class="bt2">修改车源</div>
        {{ form("car/save", "method":"post","id":"addpur") }}
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">姓名：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="user" name='contact_man' value="{{ data.contact_man ? data.contact_man : ''}}" />

                        <div id="showuser"></div>
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">手机号：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="user" name='contact_phone' value="{{ data.contact_phone ? data.contact_phone : ''}}" />

                        <div id="showuser"></div>
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">车牌号：</td>
                    <td class="cx_content">
                        <input type="text" name='car_licence' class="txt"   id="user" value="{{ data.car_licence ? data.car_licence : ''}}" />
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">厢型:
                     
                    </td>
                    <td class="cx_content">

                        <select name="truck_type_id" id="truck_type_id" class="sel w sel-province">
                            <option value="">请选择</option>

                            {% for key , item in _BOX_TYPE %}

                            <option value="{{ key }}"  <?php echo isset($ext['box_type'])&&$ext['box_type'] == "$key" ? 'selected' : '';?>>{{ item }}</option>
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
                            <option value="{{ key }}" <?php echo isset($ext['body_type'])&&$ext['body_type'] == "$key" ? 'selected' : '';?> >{{ item }}</option>
                            {% endfor %}
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" >长度：</td>
                    <td class="cx_content" >
                        <input name="length" id="length" type="text"  value='{{ ext['length'] ? ext['length'] : 0 }}'class="tex">米</td>
                </tr>
                <tr>
                    <td class="cx_title" >载重：</td>
                    <td  class="cx_content">
                        <input name="carry_weight" id="carry_weight" type="text"  value='{{ ext['carry_weight'] ? ext['carry_weight'] : 0 }}' class="tex">吨</td>
                </tr>
                <tr>
                    <td class="cx_title" >车龄：</td>
                    <td class="cx_content">
                        <input name="use_time" id="use_time" type="text" value='{{ ext['use_time'] ? ext['use_time'] : 0 }}' class="tex">年</td>
                </tr>
                <tr>
                    <td class="cx_title">发车时间：</td>
                    <td class="cx_content">
                       <input type="text" id="depart_time" name="depart_time" value='{{ ext['departtime'] ? ext['departtime'] : 0 }}' >
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">运行方式：</td>
                    <td class="cx_content">
                        <select name="transport_type" id="transport_type" class="sel w sel-province">
                              <option value="0" >否</option>
                              {% for key , item in _TRANSPORT_TYPE %}
                                <option value="{{ key }}" <?php echo isset($ext['transport_type'])&&$ext['transport_type'] == "$key" ? 'selected' : '';?> >{{ item }}</option>
                                {% endfor %}
                              
                          </select>

                    </td>
                </tr>

                <tr>
                    <td class="cx_title">是否长期：</td>
                    <td class="cx_content">
                        <select name="is_longtime" id="is_longtime" class="sel w sel-province">
                              <option value="0" <?php echo isset($ext['is_longtime'])&&$ext['is_longtime'] == 0 ? 'selected' : '';?>  >否</option>
                              <option value="1" <?php echo isset($ext['is_longtime'])&&$ext['is_longtime'] == 1 ? 'selected' : '';?> >是</option>
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
                        <input type="text" name='light_goods' value='{{ data.light_goods}}'>元 / 方
                    </td>
                </tr>


                <tr>
                    <td class="cx_title">重货：</td>
                    <td class="cx_content">
                        <input type="text" name='heavy_goods' value='{{ data.heavy_goods}}'>元 / 吨
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">备注：</td>
                    <td class="cx_content1">
                        <textarea name="demo" id="" cols="30" rows="10">{{ ext['demo'] }}</textarea>
                    </td>
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
            <input type="hidden" name='car_id' value='{{ data.car_id}}'>
            <input type="submit" value="确定" class="sub"/>
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
    {% if start_areas %}
    texts:[{{start_areas}}],
    {% endif %}
    style : {"width" : 140}
});
//  目的地
$(".endselectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if end_areas %}
    texts:[{{end_areas}}],
    {% endif %}
    style : {"width" : 140}
});
// 发车时间
$(function(){
    $("#depart_time").datetimepicker();
});
$("#addpur").validator({
     rules: {
          whd: [/^[a-zA-Z0-9]+$/, '只能输入字母或数字'], 
          par: [/^-?\d+\.?\d{0,2}$/, '保留两位小数'],
     },
     fields:  {
         contact_man:"required;chinese",
         contact_phone:"required;",
         car_no: "required;whd",
         truck_type_id : "checked;",
         body_type  : "checked;",
         length : "required;",
         carry_weight : "required;",
         use_time : "required;",
         depart_time : "required;",
         transport_type : "checked;",
         is_longtime : "checked;",
         start_pid  :"required;",
         end_pid:'required;',
         light_goods:'par;',
         heavy_goods:'par;',
         content:'required;',
         img_yz:"required;remote[/manage/car/checkimgcode ];",
     },
    
});

</script>
{{ partial('layouts/bottom') }}
</body>
</html>