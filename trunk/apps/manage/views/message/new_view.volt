  {{ content() }}
  <!-- main_right 开始  -->  
  <div class="main_right">
    <div class="bt2">选择发送目标</div>
    <div class="chaxun">
       <form method="get" action="/manage/message/new_view" id="demo1">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td  width="12%" height="35" align="right">选择目前用户：</td>
            <td  width="20%" height="35" align="left">
              <select name='user_type' onchange="fun(this.value)">
                <option value='0' <?php if(isset($user_type)&& $user_type=='0'){echo 'selected';}?>>采购商</option>
                <option value='1' <?php if(isset($user_type)&& $user_type=='1'){echo 'selected';}?>>供应商</option>
              </select>
              <select name="province" class="selectAreas" id="province">
                <option value="" selected>请选择</option>
              </select>
              <select name="city" class="selectAreas" id="city_id">
                <option value="">请选择</option>
              </select>
              <select name="county" class="selectAreas" id="town"   >
                <option value="">请选择</option>
              </select>
            </td>
            <td  width="12%" height="35" align="right" <?php if(isset($user_type) && $user_type==0){echo 'style="visibility:hidden"';}?> id='userTYPE1'>用户身份：</td>
            <td  width="25%" height="35" align="left" <?php if(isset($user_type) && $user_type==0){echo 'style="visibility:hidden"';}?> id="userTYPE2">
              <select name="user_identity">

                <option value='0' <?php if(isset($user_identity) && $user_identity==0){echo "selected";}?>>普通用户</option>
                <option value='1'  <?php if(isset($user_identity) && $user_identity==1){echo "selected";}?>>服务站</option>
 <!--                <option value='2'  <?php if(isset($user_identity) && $user_identity==2){echo "selected";}?>>可信农户</option> -->
              </select>
            </td>
 
          </tr>
          <tr>
            <td  width="12%" height="35" align="right">主营</td>
            <td  width="25%" height="35" align="left">
              <input name="goods" type="text" value="<?php if(isset($goods)){echo $goods;}?>"/>  
            </td>
          </tr>
        </table>
        <div class="btn">
          <input type="submit" value="搜 索" class="sub4"/>  
        </div>
      </form>
     </div>



    
      <div class="neirong" id="tb">
        <form method="get" action="/manage/message/newsave" id="demo">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr align="center">
            <th>序号</th>
            <th>姓名</th>
            <th>联系方式</th>
            <th>主营</th>
            <th>所在地区</th>
            <th>用户身份</th>
          </tr>
          {% if data['items'] is defined %}
          <?php $i=($current-1)*10+1 ?>
          {% for v in data['items']%}
          <tr align="center">
            <td>
              <input name="type_id[]" type="checkbox" value="{{v['check_id']}}" data-rule="checked" data-target="#product"/> 
              <input type='hidden' value="{{v['type']}}" name='type[]'> 
              <?php echo $i++ ;?>
            </td>
            <td>{{v['name']}}</td>
            <td>{{v['phone']}}</td>
            <td>{{v['goods']}}</td>
            <td>{{v['area']}}</td>
            <td>{{v['type']}}</td>
          </tr>
              {% endfor %}
              {% endif %}
              {% if !data['items'] %}
              <input name="type_hidden" type="hidden" value="" data-rule="required" data-target="#product"/> 
              {% endif %}
        </table>
         <div class="btn">
          <input type="submit" value="发送推荐" class="sub4" name='fasong'/>  
          <input type="button" value="返回" class="sub4" onclick='func()'/>  
    <input type="submit" value="仅保存" class="sub4" name="fasong"/>  
          <span id="product"></span>
        </div>
        </form>
      </div>
      

  </div>

  <!-- main_right 结束  --> 
  <!--中间结束   -->  

  <!--底部开始   -->  
  <div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
  <!--底部结束   --> 
</body>
</html>
   <script>
$(document).ready(function () {        
            //按钮样式切换
            $(".btn>input").hover(
              function () {
                  $(this).removeClass("sub4").addClass("sub5");
              },
              function () {
                 $(this).removeClass("sub5").addClass("sub4"); 
              }
            );

        });
</script>
 <script>
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
</script>
  <script>

  function fun(value){
    if(value==1){
      var ui = document.getElementById("userTYPE1"); 
      var ui1 = document.getElementById("userTYPE2"); 
      ui.style.visibility="visible";
      ui1.style.visibility="visible";
    }else{
      var ui = document.getElementById("userTYPE1"); 
      var ui1 = document.getElementById("userTYPE2"); 
      ui.style.visibility="hidden"; 
      ui1.style.visibility="hidden"; 


    }
  }
function func(){
  window.location.href='/manage/message/new/1';
  return false;
}
var $ld5 = $(".selectAreas");
$ld5.ld({
    ajaxOptions: {
        "url": "/ajax/getareas"
    },
    texts :[{{vvv}}],
    defaultParentId: 0,
    style: {
        "width": 100
    }
})


/*$("#demo").validator({
    fields: {
        'type_id[]': "checked"
    }
});*/
</script>