
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<form action="/manage/servicestation/index" method="get" autocomplete="off">
  <div class="main">
    <div class="main_right">
      <div class="bt2">{{ title }}</div>
      <div class="chaxun">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td height="35" align="right">申请状态：</td>
              <td height="35" align="left">
                <select name="shop_state">
                  <option value="all">全部</option>
                  <?php  
              foreach ($_shop_state as $key =>
                  $val) {
                      $selected = '';
                      if(isset($_GET['shop_state']) && trim($_GET['shop_state']) == "$key"){
                          $selected = 'selected';
                      }
                  echo "
                  <option value='{$key}' {$selected} >{$val}</option>
                  ";
              }?>
                </select>
              </td>
              <td height="35" align="right">身份：</td>
              <td height="35" align="left">
         
              <select name="business_type"  id="city" style="width: 140px;">
                  <option value="" >请选择</option>
                
                <?php  
                unset($_business_type[1]);
              foreach ($_business_type as $key =>
                $val) {
                      $selected = '';
                      if(isset($_GET['business_type']) && trim($_GET['business_type']) == "$key"){
                          $selected = 'selected';
                      }
                  echo "
                <option value='{$key}' {$selected} >{$val}</option>
                ";
              }?>

              </select>
             
            </td>
          </tr>
          <tr>
            <td width="15%" height="35" align="right">申请时间：</td>
            <td width="35%" height="35" align="left">
              <input readonly="readonly" type="text" class="Wdate" name="stime" id="d4331" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" value="<?php if(isset($_GET['stime'])){ echo $_GET['stime'];}?>">
              -
              <input readonly="readonly" type="text" class="Wdate" name="etime" id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})" value="<?php if(isset($_GET['etime'])){ echo $_GET['etime'];}?>"></td>
              
            <td height="35" align="right">服务站编号：</td>
            <td height="35" align="left"><input  type="text"  name="shop_no"   value="<?php if(isset($_GET['shop_no'])){ echo $_GET['shop_no'];}?>"> </td>

          </tr>
        </tbody>
      </table>
      <div class="btn">
        <input type="submit" value="确定" class="sub"></div>
    </div>

    <div class="neirong" id="tb">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
          <tr align="center" class="alt">
            <td width="10%" class="bj">序号</td>
            <td width="10%" class="bj">服务站编号</td>
            <td width="8%" class="bj">申请时间</td>
            <td width="10%" class="bj">用户类型</td>
            <td width="20%" class="bj">所在地区</td>
            <td width="8%" class="bj">电话</td>
            <td width="14%" class="bj">联系人</td>
            
            <td width="14%" class="bj">状态</td>

            <td width="8%" class="bj">操作</td>
          </tr>
          {% for key, item in data %}
          <tr align="center" class="">
            <td>{{ start + key   }}</td>
            <td>{{  item.shop_no   }}</td>
            <td>{{ date('Y-m-d H:i:s', item.add_time) }}</td>
            <td><?php 
            echo isset($_business_type[$item->user_type]) ? $_business_type[$item->user_type] : '';
            ?></td>
            <td>
              <?php 
                echo Mdg\Models\AreasFull::getAreasNametoid($item->province_id);
                echo Mdg\Models\AreasFull::getAreasNametoid($item->city_id);
                echo Mdg\Models\AreasFull::getAreasNametoid($item->county_id);
                echo Mdg\Models\AreasFull::getAreasNametoid($item->town_id);
                echo Mdg\Models\AreasFull::getAreasNametoid($item->village_id);
              ?>
            </td>
 
            
            <td>{{ item.contact_phone }}</td>
            <td>{{ item.contact_man }}</td>
            
            <td>{{ _shop_state[item.shop_status] }}</td>
            <td>
              <a href="/manage/servicestation/get/{{ item.shop_id }}">查看</a>
              <!-- <a href="/manage/servicestation/edit/{{ item.shop_id }}">编辑</a> -->

            </td>
          </tr>
          {% endfor %}
        </tbody>
      </table>
    </div>
    <div class='fenye'>
    <div class="fenye1">
      <span>{{ pages }}</span>

    </div>
  </div>
  </div>
</div>
<!-- main_right 结束  -->

</div>
</form>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>

<script> 
// $(function(){    
  // $(".selectAreas").ld({ajaxOptions : {"url" :
// "/ajax/getareas"},     defaultParentId : 0,          style : {"width" : 140}    }); });
// function remove_agreement( id)   {       if(confirm("您确定要删除吗?"))
// location.href='/manage/users/delete/'+id;

//  }
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

</body>