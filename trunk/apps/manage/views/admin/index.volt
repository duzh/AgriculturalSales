{{ content() }}

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">管理员列表</div>
        {{ form("/admin/index", "method":"get", "autocomplete" : "off") }}
        <div class="chaxun">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  height="35" align="right">管理员姓名：</td>
                    <td  height="35" align="left">
            <input type="text" name='username' id='username' value='<?php if(isset($_GET['username'])){ echo $_GET['username'];}?>'>

                    </td>
                    <td  height="35" align="right">添加时间：</td>
                    <td height="35" align="left">
            <input type="text" name='start_time' id='start_time' value='<?php if(isset($_GET['start_time'])){ echo $_GET['start_time'];}?>'>

                       -
            <input type="text" name='end_time' id='end_time' value='<?php if(isset($_GET['end_time'])){ echo $_GET['end_time'];}?>'>
                        
   
                    </td>
                </tr>
            </table>
            <div class="btn">{{ submit_button("确定","class":'sub') }}</div>
        </div>
    </form>
    <div class="neirong" id="tb">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
                <td width='6%'  class="bj">管理员id</td>
                <td width='10%' class="bj">管理员名称</td>
                <td width='20%' class="bj">最后登录时间</td>
                <td width='10%' class="bj">最后登录ip</td>
                <td width='6%'  class="bj">登陆次数</td>
                <td width='6%'  class="bj">所属角色</td>
                <td width='10%' class="bj">添加时间</td>
              
                <td width='10%' class="bj">操作</td>
            </tr>
            <?php $i=($data->current-1)*10+1 ?>
            {% if data.items is defined %}
            {% for admin in data.items %}
                <tr  align="center" >
                    <td><?php echo $i++ ;?></td>
                    <td>{{ admin.username }}</td>   
                    <td>{{ admin.lastlogintime }}</td>
                    <td>{{ admin.lastloginip }}</td>
                    <td>{{ admin.logincount }}</td>
                    <td><?php  echo Mdg\Models\AdminRoles::rolename($admin->role_id);?></td>
                    <td>{{ admin.createtime }}</td>
                    <td>{{ link_to("admin/edit/"~admin.id, "编辑") }} <a href='javascript:remove_agreement({{ admin.id }})' > 删除 </a></td>
                </tr>
            {% endfor %}
            {% endif %}
    </table>
</div>
{{ form("/admin/index", "method":"get") }}
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
<link rel="stylesheet" href="http://js.static.ync365.com/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.excheck-3.5.min.js"></script>
<script>
$(function(){
   $("#start_time").datetimepicker();
   $("#end_time").datetimepicker();
   $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    style : {"width" : 140}
   });
});
 function remove_agreement(id) 
 {  
    if(confirm("您确定要删除吗?"))
    location.href='/manage/admin/delete/'+id;

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
