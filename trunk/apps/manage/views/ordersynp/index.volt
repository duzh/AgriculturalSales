

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">云农宝支付记录</div>
       {{ form("ordersynp/index", "method":"get", "autocomplete" : "off") }}
         <div class="chaxun">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td  height="35" align="right">订单号：</td>
                      <td  height="35" align="left">
                        <input type="text" name='order_sn' value='<?php echo isset($_GET['order_sn']) ? $_GET['order_sn'] : ''; ?>'>
                        
                     </td>
                      <td  height="35" align="right">流水号：</td>
                      <td height="35" align="left">
                        <input type="text" name='num' value="<?php echo isset($_GET['num']) ? $_GET['num'] : ''; ?>">
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
                  <td width='6%'  class="bj">序号</td>
                  <td width='10%' class="bj">云农宝流水号</td>
                  <td width='18%' class="bj">订单号</td>
                  <td width='10%' class="bj">支付方式</td>
                  <td width='10%' class="bj">支付时间</td>
                  <td width='10%' class="bj">交易金额</td>

            </tr>
            {% for key , item in data['items'] %}
            <tr align="center">
              <td>{{ data['start'] +  1 + key  }}</td>
              <td>{{ item.serial_num}}</td>
              <td>{{ item.order_no}}</td>
              <td>

                {% if item.pay_type == '99' %} 
                余额付款
                {% endif %}
                {% if item.pay_type == 'ABC' %} 
                农行直连
                {% endif %}
                {% if item.pay_type == 'UMP' %} 
                联动优势
                {% endif %}

              </td>
              <td>{{ item.pay_date ? date('Y-m-d H:i:s', item.pay_date) : '' }}</td>
              <td>{{ item.order_amount}}</td>
            </tr>
            {% endfor %}
           
            
     </table>
</div>
{{ form("orders/index", "method":"get") }}
<div class="fenye">
    <div class="fenye1">
        <span>{{ data['pages'] }}</span>
        
    </div>
</div>
</form>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

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