 

{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">编辑专线</div>
        <div align="left" style="margin-top:20px;">
           {{ form("special/save", "method":"post","id":"mysell") }}
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td  colspan="2" class="cx_title1">货物出发地</td>
                </tr>
                <tr>
                    <td class="cx_title">出发地：</td>
                    <td class="cx_content">
                          <select name="startprovince" class="startAreas" data-target="#startprovincetip">
                              <option value=" " selected>省</option>
                          </select>
                          <select name="startcity" class="startAreas" >
                              <option value="0">市</option>
                          </select>
                          <select name="startdistrict" class="startAreas" >
                              <option value="0">区/县</option>
                          </select>
                          <i id="startprovincetip"></i>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">详细地址：</td>
                    <td class="cx_content">{{ text_field("startaddress",'class':'txt',"value":start["address"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">联系人：</td>
                    <td class="cx_content">{{ text_field("contact_man",'class':'txt',"value":start["contact_man"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">手机：</td>
                    <td class="cx_content">{{ text_field("mobile_phone",'class':'txt',"value":start["mobile_phone"]) }}</td>
                </tr>
                {%  if start["mobile_phone"] == '' %}
                <tr>
                    <td class="cx_title">手机图片：</td>
                    <td class="cx_content">{{start["phone_img"]}}</td>
                </tr>
                {% endif %}
                <tr>
                    <td class="cx_title">固定电话：</td>
                    <td class="cx_content">{{ text_field("fix_phone",'class':'txt',"value":start["fix_phone"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">网点名：</td>
                    <td class="cx_content">{{ text_field("net_name",'class':'txt',"value":start["net_name"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">公司名：</td>
                    <td class="cx_content">{{ text_field("company_name",'class':'txt',"value":start["company_name"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">QQ：</td>
                    <td class="cx_content">{{ text_field("qq",'class':'txt',"value":start["qq"]) }}</td>
                </tr>
            </table>
        <div class="cx">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  colspan="2" class="cx_title1">货物目的地</td>
                </tr>
                <tr>
                    <td class="cx_title">目的地：</td>
                    <td class="cx_content">
                          <select name="endprovince" class="endAreas"  data-target="#endprovincetip" >
                              <option value=" " selected>省</option>
                          </select>
                          <select name="endcity" class="endAreas" >
                              <option value="0">市</option>
                          </select>
                          <select name="enddistrict" class="endAreas" >
                              <option value="0">区/县</option>
                          </select>
                          <i id="endprovincetip"></i>
                     </td>
                         
                </tr>
                <tr>
                    <td class="cx_title">详细地址：</td>
                    <td class="cx_content">{{ text_field("endaddress",'class':'txt',"value":end["address"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">联系人：</td>
                    <td class="cx_content">{{ text_field("endcontact_man",'class':'txt',"value":end["contact_man"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">手机：</td>
                    <td class="cx_content">{{ text_field("endmobile_phone",'class':'txt',"value":end["mobile_phone"]) }}</td>
                </tr>
                {%  if end["mobile_phone"] == '' %}
                <tr>
                    <td class="cx_title">手机图片：</td>
                    <td class="cx_content">{{end["phone_img"]}}</td>
                </tr>
                {% endif %}
                <tr>
                    <td class="cx_title">固定电话：</td>
                    <td class="cx_content">{{ text_field("endfix_phone",'class':'txt',"value":end["fix_phone"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">网点名：</td>
                    <td class="cx_content">{{ text_field("endnet_name",'class':'txt',"value":end["net_name"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">公司名：</td>
                    <td class="cx_content">{{ text_field("endcompany_name",'class':'txt',"value":end["company_name"]) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">QQ：</td>
                    <td class="cx_content">{{ text_field("endqq",'class':'txt',"value":end["qq"] ) }}</td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cx_title">运行方式:</td>
                    <td class="cx_content">
                       <select name="type" class="selectAreas" id="province">
                              <option value="0" {% if data.type == 0 %}selected='selected'{% endif %}>单程</option>
                              <option value="1" {% if data.type == 1 %}selected='selected'{% endif %}>往返</option>
                       </select>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">重货价：</td>
                     <td class="cx_content">{{ text_field("light_price",'class':'txt',"value":data.light_price) }}</td>
                </tr>
                <tr>
                   <td class="cx_title">轻货价：</td>
                    <td class="cx_content">{{ text_field("heavy_price",'class':'txt',"value":data.heavy_price) }}</td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">备注：</td>
                    <td >
                        <div class="cx_content1" >{{  text_area("content", "type" : "numeric",'class':'txt',"value":data.demo) }}</div>
                    </td>
                </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
        	<input type="hidden" value="{{data.sc_id}}" name="sc_id">
            <input type="submit" value="发布" class="sub"/>
        </div>
    </div>
    <!-- main_right 结束  -->
</div>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script>
$(".startAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (startAreas) %}
    texts : [{{ startAreas }}],
    {% endif %}
    style : {"width" : 140}
});
$(".endAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   {% if (endAreas) %}
    texts : [{{ endAreas }}],
    {% endif %}
    style : {"width" : 140}
});
</script>
<script>
$("#mysell").validator({
      rules: {
          whd: [/^[\u4E00-\u9FA5]|\w+$/, '只能输入中文或字母或数字'],
          nimei  : [/^([0-9])+(\.([0-9]){1,2})?$/, '请输入数字']
      },
     fields:  {
          startprovince:"required;",
          contact_man:"required;whd",
          mobile_phone:"required;mobile",
          fix_phone:"required;",
          net_name:"required;whd",
          company_name:"required;whd",
          endprovince:"required;",
          endcontact_man:"required;whd",
          endmobile_phone:"required;mobile",
          endfix_phone:"required;",
          endnet_name:"required;whd",
          endcompany_name:"required;whd",
          type:"required;",
          light_price:"required;nimei;length[1~8]",
          heavy_price:"required;nimei;length[1~8]"
     }
});

</script>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>