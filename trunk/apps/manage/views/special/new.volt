

{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">新增专线</div>
        <div align="left" style="margin-top:20px;">
           {{ form("special/create", "method":"post","id":"mysell") }}
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td  colspan="2" class="cx_title1">货物出发地</td>
                </tr>
                <tr>
                    <td class="cx_title">出发地：</td>
                    <td class="cx_content">
                          <select class="startAreas" name="start_pname" data-target="#chufadi">
                              <option value=" " selected>省</option>
                          </select>
                          <select class="startAreas" name="start_cname">
                              <option value="0">市</option>
                          </select>
                          <select name="startAreas" class="startAreas">
                              <option value="">区/县</option>
                          </select>
                          <span id="chufadi"></span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">详细地址：</td>
                    <td class="cx_content">{{ text_field("address",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">联系人：</td>
                    <td class="cx_content">{{ text_field("contact_man",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">手机：</td>
                    <td class="cx_content">{{ text_field("mobile_phone",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">固定电话：</td>
                    <td class="cx_content">{{ text_field("fix_phone",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">网点名：</td>
                    <td class="cx_content">{{ text_field("net_name",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">公司名：</td>
                    <td class="cx_content">{{ text_field("company_name",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">QQ：</td>
                    <td class="cx_content">{{ text_field("qq",'class':'txt') }}</td>
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
                          <select class="endAreas" name="end_pname" data-target="#mudidi">
                              <option value="" selected>省</option>
                          </select>
                          <select class="endAreas">
                              <option value="0">市</option>
                          </select>
                          <select name="endAreas" class="endAreas">
                              <option value="">区/县</option>
                          </select>
                          <span id="mudidi"></span>
                     </td>
                         
                </tr>
                <tr>
                    <td class="cx_title">详细地址：</td>
                    <td class="cx_content">{{ text_field("endaddress",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">联系人：</td>
                    <td class="cx_content">{{ text_field("endcontact_man",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">手机：</td>
                    <td class="cx_content">{{ text_field("endmobile_phone",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">固定电话：</td>
                    <td class="cx_content">{{ text_field("endfix_phone",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">网点名：</td>
                    <td class="cx_content">{{ text_field("endnet_name",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">公司名：</td>
                    <td class="cx_content">{{ text_field("endcompany_name",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">QQ：</td>
                    <td class="cx_content">{{ text_field("endqq",'class':'txt') }}</td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cx_title">运行方式:</td>
                    <td class="cx_content">
                       <select name="type" class="selectAreas" id="province">
                              <option value="0" selected>单程</option>
                              <option value="1" selected>往返</option>
                       </select>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">重货价：</td>
                     <td class="cx_content">{{ text_field("light_price",'class':'txt') }}</td>
                </tr>
                <tr>
                   <td class="cx_title">轻货价：</td>
                    <td class="cx_content">{{ text_field("heavy_price",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">备注：</td>
                    <td >
                        <div class="cx_content1" >{{  text_area("content", "type" : "numeric",'class':'txt') }}</div>
                    </td>
                </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
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
    style : {"width" : 140}
});
$(".endAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    style : {"width" : 140}
});
</script>
<script>
$("#mysell").validator({
      rules: {
          whd: [/^[\u4E00-\u9FA5]|\w+$/, '只能输入中文或字母或数字'],
          nimei  : [/^([0-9])+(\.([0-9]){1,2})?$/, '请输入数字'],
      },
     fields:  {
          start_pname:"required;",
          contact_man:"required;whd",
          mobile_phone:"required;mobile",
          net_name:"required;whd",
          company_name:"required;whd",
          end_pname:"required;",
          endcontact_man:"required;whd",
          endmobile_phone:"required;mobile",
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