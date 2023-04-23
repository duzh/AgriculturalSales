 <?php use Mdg\Models as M;?>
 <!-- main_right 开始  -->
  <div class="main_right">
    <div class="bt2">消息管理</div>
    <div class="chaxun">
      <form method="get" action='/manage/message/index'>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  width="12%" height="35" align="right">创建时间：</td>
          <td  width="25%" height="35" align="left">
            <input id="d421" class="Wdate"  style="width:90px; float:left;" class="Wdate" name="st"  type="text" 
            onfocus="WdatePicker({skin:'whyGreen',maxDate:'%y-%M-%d'})" value="{{start}}"/>      
            -
            <input id="d422" class="Wdate"  class="text"  class="Wdate" style="width:90px;" name="et"  type="text" onfocus="WdatePicker({skin:'whyGreen',maxDate:'%y-%M-%d+1'})" value="{{end}}"/>      
          </td>
          <td  width="12%" height="35" align="right">消息类型：</td>
          <td  width="20%" height="35" align="left">
            <select name='info_type'>
              <option value'' <?php if(isset($_GET['info_type']) && $_GET['info_type']=='') echo 'selected';?>>全部</option>
              <option value='1' <?php if(isset($_GET['info_type']) && $_GET['info_type']=='1') echo 'selected';?>>采购推荐</option>
              <option value='2' <?php if(isset($_GET['info_type']) && $_GET['info_type']=='2') echo 'selected';?>>供应推荐</option>
            </select>
          </td>
        </tr>
        <tr>
          <td  width="12%" height="35" align="right">消息状态：</td>
          <td  width="25%" height="35" align="left">
            <select name="statue">
              <option value='' <?php if(isset($_GET['statue']) && $_GET['statue']=='') echo 'selected';?>>全部</option>
              <option value='1' <?php if(isset($_GET['statue']) && $_GET['statue']=='1') echo 'selected';?>>已发送</option>
              <option value='2' <?php if(isset($_GET['statue']) && $_GET['statue']=='2') echo 'selected';?>>未发送</option>
            </select>
          </td>
        </tr>
      </table>
      <div class="btn">
        <input type="submit" value="确 定" class="sub4"/>
      </div>
    </form>
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
    </div>
    
    <div class="neirong" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <th>序号</th>
          <th>创建时间</th>
          <th>信息类型</th>
          <th>消息主题</th>
          <th>状态</th>
          <th>操作</th>
        </tr>
         {% if data['items'] is defined %}
          <?php $i=($current-1)*10+1 ?>
          {% for v in data['items']%}
        <tr align="center">
          <td><?php echo $i++ ;?></td>
          <td><?php echo date("Y-m-d H:i:s",$v['add_time'])?></td>
          <td>{{v['info_type']}}</td>
          <td>{{v['comment']}}</td>
          <td><?php echo M\Message::$_is_status[$v['status']]?></td>
          <td><a href="/manage/message/info/{{v['msg_id']}}">查 看</a> </td>
        </tr>
                   {% endfor %}
              {% endif %}
      </table>
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
    </div>
    <div class="fenye">
      <div class="fenye1"> {{data['pages']}}</div>
    </div>
  </div>
  <!-- main_right 结束  --> 
</div>
<!--中间结束   --> 

<!--底部开始   -->
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
<!--底部结束   -->
</body>
</html>
