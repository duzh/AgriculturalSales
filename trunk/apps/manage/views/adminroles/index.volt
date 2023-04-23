{{ content() }}

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">角色列表</div>
        {{ form("/adminroles/index", "method":"get", "autocomplete" : "off") }}
        <div class="chaxun">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  height="35" align="right">角色名称：</td>
                    <td  height="35" align="left">
            <input type="text" name='rolename' id='rolename' value='<?php if(isset($_GET['rolename'])){ echo $_GET['rolename'];}?>'>

                    </td>
                    <td  height="35" align="right">角色描述：</td>
                    <td height="35" align="left">
            <input type="text" name='description' id='description' value='<?php if(isset($_GET['description'])){ echo $_GET['description'];}?>'>
                        
                    </td>
                </tr>
            </table>
            <div class="btn">{{ submit_button("确定","class":'sub') }}</div>
        </div>
    </form>
    <div class="neirong" id="tb">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
                <td width='6%'  class="bj">管理员编号</td>
                <td width='10%' class="bj">角色名称</td>
                <td width='20%' class="bj">角色描述</td>
                <td width='10%' class="bj">操作</td>
           </tr>
             <?php $i=($data->current-1)*10+1 ?>
              {% if data.items is defined %}
                {% for admin_role in data.items %}
                    <tr align="center">
                         <td><?php echo $i++ ;?></td>
          
                        <td>{{ admin_role.rolename }}</td>
                        <td>{{ admin_role.description }}</td>
                        <td>{{ link_to("adminroles/edit/"~admin_role.id, "修改") }}
                          <a href='javascript:remove_agreement({{ admin_role.id }})' > 删除 </a>
                        </td>
                   
                    </tr>
                {% endfor %}
                {% endif %}
    </table>
</div>
{{ form("/adminroles/index", "method":"get") }}
<div class="fenye">
    <div class="fenye1">
        <span>{{ pages }}</span>
        <em>跳转到第<input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?> />页</em>
        <?php unset($_GET['p']);
                  foreach ($_GET as $key => $val) {

              echo "<input type='hidden' name='{$key}' value='{$val}'>";
              }?>
         <input class="sure_btn"  type='submit' value='确定'>
    </div>
</div>
</form>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />

<script>
$(function(){
   $("#stime").datetimepicker();
   $("#etime").datetimepicker();
   $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    style : {"width" : 140}
   });
});
function remove_agreement( id) 
 {  
    if(confirm("您确定要删除吗?"))
    location.href='/manage/adminroles/delete/'+id;

 }
$(document).ready(function () {        
    //按钮样式切换
    $(".neirong tr").mouseover(function(){    
    //如果鼠标移到class为stripe的表格的tr上时，执行函数    
    $(this).addClass("over");}).mouseout(function(){    
    //给这行添加class值为over，并且当鼠标一出该行时执行函数    
    $(this).removeClass("over");}) //移除该行的class    
    $(".neirong tr:even").addClass("alt");    
    //给class为stripe的表格的偶数行添加class值为alt 
});

$(document).ready(function () {        
    //按钮样式切换
    $(".btn>input").hover(
    function () {
    $(this).removeClass("sub").addClass("sub1");
    },
    function () {
    $(this).removeClass("sub1").addClass("sub"); 
    }
    );
});
</script>
