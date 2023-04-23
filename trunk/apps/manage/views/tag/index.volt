{{ content() }}
<div align="right"></div>

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">标签管理</div>
  
{{ form("/tag/index", "method":"get") }}


    <div class="chaxun">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  height="35" align="right">信息分类：</td>
          <td  height="35" align="left">
            <select name="category_one" class="selectCate">
              <option value="">选择分类</option>
            </select>
            <select name="category_two" class="selectCate">
              <option value="">选择分类</option>
            </select>
          </td>
          <td  height="35" align="right">会员电话：</td>
          <td height="35" align="left">
            <input type="text" name='user_name' value='<?php if(isset($_GET['user_name'])){ echo trim($_GET['user_name']);}?>'>
          </td>
        </tr>
        <tr>
          <td  width="15%" height="35" align="right">状态：</td>
          <td  width="35%" height="35" align="left">
            <select name="status" id="">
              <option value="all">请选择</option>
              <?php 
                      foreach ($_STATUS as $key => $val) {
                      $selected = '';
                      if(isset($_GET['status']) && trim($_GET['status']) == "$key"){
                          $selected = 'selected';
                      }
                  echo "
              <option value='{$key}' {$selected} >{$val}</option>
              ";
              }?>
            </select>
          </td>
          <td  height="35" align="right">添加时间：</td>
          <td height="35" align="left">
            <input readonly="readonly"  type="text" class="Wdate" name="starttime" id="d4331" 
                      onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}',dateFmt:'yyyy-MM-dd'})" value="<?php if(isset($_GET['starttime'])){ echo trim($_GET['starttime']);}?>">-
            <input readonly="readonly" type="text" class="Wdate" name="endtime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3',dateFmt:'yyyy-MM-dd'})"
                       value="<?php if(isset($_GET['endtime'])){ echo trim($_GET['endtime']);}?>"></td>
        </tr>
         <tr>
          <td  height="35" align="right">会员姓名：</td>
          <td height="35" align="left">
            <input type="text" name='name' value='<?php if(isset($_GET['name'])){ echo trim($_GET['name']);}?>'>
          </td>
        </tr>
      </table>
      <div class="btn">{{ submit_button("确定","class":'sub') }}</div>
    </div>
  </form>

    <div class="neirong" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td width='6%'  class="bj">申请时间</td>
          <td width='10%' class="bj">供应商品编号</td>
          <td width='18%' class="bj">商品名称</td>
          <td width='10%' class="bj">所属分类</td>
          <td width='10%' class="bj">会员</td>
          <td width='8%' class="bj">会员电话</td>
          <td width='8%' class="bj">状态</td>
          <td width='8%' class="bj">操作</td>
        </tr>
        {% for key, val in data['items'] %}
        <tr align="center" >
          <td>{{ val.addtime }}</td>
          <td>
            <?php echo Mdg\Models\Sell::getSellTosn($val->sell_id);?></td>
          <td>{{ val.goods_name }}</td>
          <td>
            <?php echo Mdg\Models\Category::selectBytocateName($val->category_three);?></td>
          <td>
            <?php echo Mdg\Models\Sell::selectBytouname($val->sell_id);?></td>
          <td>
            <?php echo Mdg\Models\Sell::selectBytomobile($val->sell_id);?></td>
          <td>{{ _STATUS[val.status]}}</td>
          <td>
            <!-- <a href="/manage/tag/get/{{val.tag_id}}">查看</a> -->
            <!-- <a href="/manage/tag/edit/{{val.tag_id}}">修改</a> -->
            <a href="/manage/tag/info/{{val.tag_id}}">标签详情</a>
          </td>
        </tr>
        {% endfor %}
      </table>
    </div>
    <div class="fenye">
      <div class="fenye1">
        <span>{{ data['pages'] }}</span>
      </div>
    </div>
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
    <?php if(isset($cate)&&$cate){
      echo "texts:[{$cate}],";
    }?>
    style : {"width" : 140}
   });
});
 $('.state').on('click', function(event) {
      event.preventDefault();
      obj = $(this);
      url = ($(this).attr('data-url'));
      $.get(url, function(data) {
       
       console.log(obj); 
        obj.text(data);
        location.reload(); 
      });
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