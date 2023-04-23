{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">新增补贴</div>
        <div align="left" style="margin-top:20px;">
           {{ form("subsidy/create", "method":"post","id":"mysell") }}
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">用户手机号：</td>
                    <td class="cx_content">{{ text_field("usermobile",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">补贴金额：</td>
                    <td class="cx_content">{{ text_field("subsidy_total",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">相关订单：</td>
                    <td class="cx_content">{{ text_field("order_sn",'class':'txt') }}(选填)</td>
                </tr>
                <tr>
                    <td class="cx_title">备注：</td>
                    <td class="cx_content1">{{  text_area("content", "type" : "numeric",'class':'txt') }}</td>
                </tr>
                
            </table>
        <div align="center" style="margin-top:20px;">
            <input type="submit" value="创建" class="sub"/>
        </div>
    </div>
    <!-- main_right 结束  -->
</div>
<script>
$("#mysell").validator({
      rules: {
          whd: [/^[\u4E00-\u9FA5]|\w+$/, '只能输入中文或字母或数字'],
          //nimei  : [/^([1-9]\d{0,})+(\.([0-9]){1,2})?$/, '请输入正确的金额'],
          nimei  : [/^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+)|([0-9]+\.[0-9]{1,2}))$/, '请输入正确的金额']
          
      },
     fields:  {
          usermobile:"required;mobile;remote[/manage/subsidy/checkmobile]",
          subsidy_total:"required;nimei",
          order_sn:"remote[/manage/subsidy/checkorder , usermobile]",
          content:"required;",
     }
});

</script>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>