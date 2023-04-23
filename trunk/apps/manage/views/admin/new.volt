<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

{{ form("admin/create", "method":"post","id" : "addadmin") }}

{{ content() }}
<div class="main">
  <div class="main_right">
    <div class="bt2">添加管理员</div>
    <div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">管理员账号：</td>
                    <td class="cx_content">
                        {{ text_field("username") }}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">管理员密码：</td>
                    <td class="cx_content">
                         {{ password_field("password") }}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">密码确认：</td>
                    <td class="cx_content">
                        {{ password_field("confirmpassword") }}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">选择角色：</td>
                    <td class="cx_content">
                        <select  name="role_id">
                            {% for key,val in role %}
                            <option value="{{val.id}}" >{{val.rolename}}</option>
                            {% endfor %}
                        </select>
                     </td>
                </tr>
         </table>
         
    </div> 
    <div align="center" style="margin-top:20px;">
        <td></td>
         <input type="submit" value="确认添加" class="sub"/>
         </div>
  </div>
</form>
  <!-- main_right 结束  --> 
  
</div>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script>
jQuery(document).ready(function(){
    $('#addadmin').validator({
        display: function(el){
            return el.getAttribute('placeholder') || '';
        },
        fields: {
             'username': 'required;username;remote[/manage/admin/check]',
             'password': 'required;',
             'confirmpassword':'required;match(password)',
        }
    });
});
</script>
