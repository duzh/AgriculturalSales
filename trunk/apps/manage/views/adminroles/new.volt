<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

{{ form("adminroles/create", "method":"post" ,"id":"myrole") }}
{{ content() }}
<div class="main">
  <div class="main_right">
    <div class="bt2">添加角色</div>
    <div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">角色姓名：</td>
                    <td class="cx_content">
                        {{ text_field("rolename", "size" : 30) }}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">角色描述：</td>
                    <td class="cx_content">
                        {{ text_field("description", "size" : 30) }}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">选择权限：</td>
                    <td class="cx_content">
                  {% for key, val in admin_role %}
                
                         <input  type="checkbox" onclick="checkAll('{{val[0]["controller"]}}',this.checked)">{{controllername[key]}}
                              <br/>一一一一一一一一一一|
                                   <div id="{{val[0]["controller"]}}">
                                    
                                    {% for m, nav in val %}
                                           <input type="checkbox" name="controller[]" value="{{ nav['permission_id']}}" id="remove_back" class="checkbox" />{{controllername[key]}}{{actionername[nav['action']]}}
                                    {% endfor %}
                                   </div>
                             <br/>
                  {% endfor %} 
                      </td>
                </tr>
         </table>
         
    </div> 
    <div align="center" style="margin-top:20px;">
         <input type="submit" value="确认添加" class="sub"/>
         </div>
  </div>
</form>
  <!-- main_right 结束  --> 
  
</div>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>
<script language='javascript'>  
function checkAll(div,strChecked)  
{  
    var divObj = document.getElementById(div);  
    var length = divObj.childNodes.length;  
    for(var i=0; i<length;  i++)  
    {  
        if(divObj.childNodes[i].type == 'checkbox')  
           divObj.childNodes[i].checked = strChecked;  
    }  
}  
</script> 
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript">
$("#myrole").validator({
     fields:  {
         rolename:"required;",
         description:"required;",
     },
    
});
</script>

