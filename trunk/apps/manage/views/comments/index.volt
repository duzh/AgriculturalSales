{{ content() }}

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

  <!-- main_right 开始  -->
  <div class="main_right">
    <div class="bt2">评论管理</div>
    <div class="chaxun">
      {{ form("/comments/index", "method":"get", "autocomplete" : "off") }}
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  height="35" align="right">评论时间：</td>
          <td  height="35" align="left">
                     {{ text_field("start_time") }} -
                     {{ text_field("end_time") }}
          <td  height="35" align="right">评论会员：</td>
          <td height="35" align="left">{{ text_field("name") }}</td>
        </tr>
        <tr>
          <td  width="15%" height="35" align="right">被评论店铺：</td>
          <td  width="35%" height="35" align="left">{{ text_field("shop_name") }}</td>
          <td  height="35" align="right">评论状态：</td>
          <td height="35" align="left">
            <select  name="type" >
               <option value="all" selected >全部</option>
              <?php  

              foreach ($_is_check as $key =>$val) {
                      $selected = '';
                      if(isset($_GET['type']) && trim($_GET['type']) == "$key"){
                          $selected = 'selected';
                      }
                  echo "<option value='{$key}' {$selected} >{$val}</option>";
              }?>

            </select></td>
        </tr>
      </table>
      <div class="btn">
        <input type="submit" value="查 询" class="sub"/>
      </div>
    </div>
    <div class="neirong" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr >
          <th>序号</th>
          <th>评论时间</th>
          <th>评论会员</th>
          <th>被评论店铺</th>
          <th>评论等级</th>
          <th>评论内容</th>
          <th>状态</th>
          <th>操作</th>
        </tr>

            {% if page.items is defined %}
            <?php $i=($current-1)*10+1 ?>
            {% for item in page.items %}        
        <tr align="center">
          <td width="8%"><span><?php echo $i++ ;?></span></td>
          <td width="13%">{{date("Y-m-d H:i:s", item.add_time) }}</td>
          <td width="10%">{{item.user_name}}</td>
          <td width="10%">{{item.shop_name}}</td>
          <td width="15%" class="txt">
                 服务态度：{{item.service}}星
            <br> 陪同程度：{{item.accompany}}星
            <br> 供货能力：{{item.supply}}星
            <br> 描述相符：{{item.description}}星
          </td>
          <td width="20%" class="txt">{{item.comment}}</td>
          <td width="10%"><?php echo isset(Mdg\Models\ShopComments::$_is_check[$item->is_check]) ? Mdg\Models\ShopComments::$_is_check[$item->is_check] : ''; ?></td>
          <td width="8%">

            {% if item.is_check == 0 %}
            <a href="/manage/Comments/is_checksave/{{item.id}}/1" >审核通过</a> 
            <a href="/manage/Comments/is_checksave/{{item.id}}/2">审核未通过</a> 
          </td>
            {% endif %}

        </tr>
            {% endfor %}
            {% endif %}

      </table>
    </div>
   <div class="fenye">
    <div class="fenye1">
        <span>{{ pages }}</span>
    </div>
</div>
  </div>
  <!-- main_right 结束  --> 

<!--中间结束   --> 

<!--底部开始   -->
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
<!--底部结束   -->

<script>
$(function(){
   $("#start_time").datetimepicker();
   $("#end_time").datetimepicker();
});


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
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" href="http://js.static.ync365.com/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.excheck-3.5.min.js"></script>
</body>
</html>
