<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
    <div class="main_right">
        <div class="bt2">修改货源</div>
        {{ form("cargo/save", "method":"post","id":"addpur") }}
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">

                <tr>
                    <td class="cx_title">姓名：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="contact_man" name='contact_man' value="{{ data.contact_man ? data.contact_man : ''}}" />
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">电话：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="contact_phone" name='contact_phone' value="{{ data.contact_phone ? data.contact_phone : ''}}" />
                        <img src="http://yncmdg.b0.upaiyun.com/{{data.phone_img}}">
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">货物名:</td>
                    <td class="cx_content">
                        <input type="text" name='goods_name' class="txt"   id="goods_name" value="{{ data.goods_name ? data.goods_name : ''}}" />
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">货物种类:</td>
                    <td class="cx_content">
                        <select name="goods_type" id="goods_type">
                            <option value="">请选择</option>
                            {% for key , item in _GOODS_TYPE %}
                            <option value="{{ key }}"<?php echo isset($data->goods_type)&&$data->goods_type == "$key" ? 'selected' : '';?>>{{ item }}</option>
                            {% endfor %}
                        </select>

                    </td>
                </tr>

                <tr>
                    <td class="cx_title">重量:</td>
                    <td class="cx_content">
                        <input type="text" name='goods_weight' class="txt"   id="goods_weight" value="{{ data.goods_weight ? data.goods_weight : ''}}"/>/吨
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" >体积：</td>
                    <td class="cx_content" >
                        <input name="goods_size" id="goods_size" type="text" class="tex" value="{{ data.goods_size ? data.goods_size : ''}}">/方</td>
                </tr>
                <tr>
                    <td class="cx_title" >期望报价：</td>
                    <td  class="cx_content">
                        <input name="except_price" id="except_price" type="text" class="tex" value="{{ data.except_price ? data.except_price : ''}}">元/吨</td>
                </tr>
                <tr>
                    <td class="cx_title" >车体长度：</td>
                    <td  class="cx_content">
                        <input name="except_length" id="except_length" type="text" class="tex" value="{{ data.except_length ? data.except_length : ''}}">/米</td>
                </tr>
                <tr>
                    <td class="cx_title" >厢型：</td>
                    <td class="cx_content">
                   <select name="box_type" id="box_type">
                            <option value="">请选择</option>
                            {% for key , item in _BOX_TYPE %}
                            <option value="{{ key }}"<?php echo isset($data->box_type)&&$data->box_type == "$key" ? 'selected' : '';?>>{{ item }}</option>
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
                            <option value="{{ key }}"<?php echo isset($data->body_type)&&$data->body_type == "$key" ? 'selected' : '';?>>{{ item }}</option>
                            {% endfor %}
                    </select>
                </td>
                </tr>

                <tr>
                    <td class="cx_title">有效时间：</td>
                    <td class="cx_content">
                        <input type="text" id="expire_time" name="expire_time" 
                        value="{{ date("Y-m-d H:i:s ",data.expire_time ? data.expire_time : 0)}}"></td>
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">是否长期:</td>
                    <td class="cx_content">
                        <select name="is_longtime" id="is_longtime" class="sel w sel-province">
                             <option value="0" <?php echo isset($data->is_long)&&$data->is_long == 0 ? 'selected' : '';?>>否</option>
                            <option value="1" <?php echo isset($data->is_long)&&$data->is_long == 1 ? 'selected' : '';?>>是</option>
                        </select>
                    </td>
                </tr>
               <tr>
                    <td class="cx_title">结算方式：</td>
                    <td class="cx_content">
                       <input type="text" id="settle_type" name="settle_type" value="{{data.settle_type}}">
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">出发地:</td>
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
                    <td class="cx_title">目的地:</td>
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
                    <td class="cx_title">备注:</td>
                    <td class="cx_content">
                  <textarea name="demo" id="" cols="30" rows="10">{{ data.demo }}</textarea>
              </td>
                </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
            <input type="hidden" name='goods_id' value='{{ data.goods_id}}'>
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
    $("#expire_time").datetimepicker();
});
$("#addpur").validator({
     rules: {
          whd: [/^[a-zA-Z0-9]+$/, '只能输入字母或数字'], 
          par: [/^-?\d+\.?\d{0,2}$/, '保留两位小数'],
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
         expire_time : "有效时间:required;",
         is_longtime  :"是否长期:required;",
         start_pid:'出发地:required;',
         end_pid:'目的地:required',
     },
});

</script>
{{ partial('layouts/bottom') }}
</body>
</html>