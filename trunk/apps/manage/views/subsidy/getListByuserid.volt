<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">交易明细</div>
    {{ form("subsidy/getListByuserid", "method":"get", "autocomplete" : "off") }}
    <div class="chaxun">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr>
          <td  width="15%" height="35" align="right">电话：</td>
          <td  width="35%" height="35" align="left">
            <input  type="text"  name="mobile" value="{{ mobile}}"></td>
          <td  height="35" align="right">用户ID：</td>
          <td height="35" align="left">
            <input  type="text"  name="user_id" value="{{ user_id }}"></td>
        </tr>

        <tr>
          <td  width="15%" height="35" align="right">订单号：</td>
          <td  width="35%" height="35" align="left">
            <input  type="text"  name="order_sn" value="{{  order_sn }}"></td>
          <td  height="35" align="right">使用渠道：</td>
          <td height="35" align="left">
            <select name="pay_way" id="">
              <option value="all">请选择</option>
            <?php  
              foreach ($_pay_way as $key =>$val) {
                      $selected = '';
                       
                    if( trim($pay_way) == "$key"){
                        $selected = 'selected';
                    }
                  echo " <option value='{$key}' {$selected} >{$val}</option> ";
              }?>
              </select>
          </td>
        </tr>

       
      </table>
      <div class="btn">
        
        <input type="submit" value="确 定" class="sub"/>
      </div>
    </div>
  </form>
  <div class="neirong" id="tb">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width='6%'  class="bj">订单号</td>
        <td width='10%' class="bj">使用渠道</td>
        <td width='10%' class="bj">支付金额</td>
        <td width='10%' class="bj">支付时间</td>
        <td width='8%' class="bj">用户ID</td>
        <td width='5%' class="bj">姓名</td>
        <td width='10%' class="bj">电话</td>
    

      </tr>
      {% for key ,item in data['items']%}
        <tr align="center">
          <?php $ordercount= Mdg\Models\SubsidyPay::checkis_close($item->order_no);?>
          <td> {{ item.order_no }}<br/>{% if ordercount %}交易取消,已退款{% endif %}</td>
          <td> <?php echo isset($_pay_way[$item->pay_way]) ?  $_pay_way[$item->pay_way] : ''?></td> </td>
          <td> {{ item.pay_amount }} </td>
          <td> {{ date('Y-m-d H:i:s' , item.pay_time) }} </td>
          <td> {{ item.user_id }} </td>
          <td> {{ item.user_name }} </td>
          <td> {{ item.user_phone }} </td>
        </tr>
      {% endfor %}
    </table>
  </div>
  {{ form("subsidy/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ data['pages'] }}</span> <em>跳转到第
        <input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
      <?php unset($_GET['p']);
              foreach ($_GET as $key =>
      $val) {

          echo "
      <input type='hidden' name='{$key}' value='{$val}'>
      ";
          }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>

<script>
$(function(){
 
   $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    {% if cat_name %}
    texts : [{{ cat_name }}],
    {% endif %}
    style : {"width" : 140}
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
</body>
</html>