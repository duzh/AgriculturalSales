

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">订单列表</div>
       {{ form("orders/index", "method":"get", "autocomplete" : "off") }}
         <div class="chaxun">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td  height="35" align="right">订单状态：</td>
                      <td  height="35" align="left">
                        <select name="state">
                            <option value="">全部状态</option>
                            {% for key,state in orderstate %}
                             <option value="{{key}}" {% if key==statue %} selected {% endif %}>{{state}}</option>
                            {% endfor %}
                        </select>    
                     </td>
                      <td  height="35" align="right">商品分类：</td>
                      <td height="35" align="left">
                             <select name="maxcategory" class="selectCate">
                                <option value="">选择分类</option>
                            </select>
                            <select name="category" class="selectCate">
                                <option value="">选择分类</option>
                            </select>
                      </td>
                    </tr>
                    <tr>
                      <td  width="15%" height="35" align="right">供货商：</td>
                      <td  width="35%" height="35" align="left">{{ text_field("sellname", "type" : "numeric","class" : "t1","value":sellname) }}</td>
                      <td  width="15%" height="35" align="right">采购商：</td>
                      <td  width="35%" height="35" align="left">{{ text_field("purname","class" : "t1","value":purname) }}</td>
                     
                    </tr>
                     <tr>
                      <td  width="15%" height="35" align="right">订单号：</td>
                      <td  width="35%" height="35" align="left">{{ text_field("order_sn", "type" : "numeric","value":order_sn) }}</td>
                      <td  height="35" align="right">添加时间：</td>
                      <td height="35" align="left"> 
                      <input readonly="readonly"  type="text" class="Wdate" name="stime" id="d4331" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" value="{{stime}}">-
                      <input readonly="readonly" type="text" class="Wdate" name="etime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})"
                       value="{{etime}}">
                       </td>
                    </tr>

                    <tr>
                      <td  width="15%" height="35" align="right">云农宝流水号：</td>
                      <td  width="35%" height="35" align="left">
                        <input type="text" name='ynp_no' value='{{ ynp_no }}'></td>
                      <td  height="35" align="right">银行流水号：</td>
                      <td height="35" align="left"> 
                        <input type="text" name='bank_no' value='{{ bank_no }}'>
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
                  <td width='10%' class="bj">订单号</td>
                  <td width='10%' class="bj">云农宝交易流水号</td>
                  <td width='10%' class="bj">银行流水号</td>
                  <td width='8%' class="bj">商品名称</td>
                  <td width='5%' class="bj">供货商</td>
                  <td width='10%' class="bj">采购商</td>
                  <td width='12%' class="bj">下单时间</td>
                  <td width='8%' class="bj">订单状态</td>
                  <td width='8%' class="bj">支付方式</td>
                  <td width='8%' class="bj">订单金额</td>
                  <td width='10%' class="bj">操作</td>
            </tr>
            <?php $i=($current-1)*10+1 ?>
            {% if data  is defined %}
            {% for order in data %}
                <tr align="center">
                    <td><?php echo $i++ ;?></td>
                    <td>{{ order.order_sn }}</td>
                    <td>{{ order.ynp_sn }}</td>
                    <td>{{ order.bank_sn }}</td>
                    <td>{{ order.goods_name }}</td>
                    <td><?php $sellname= Mdg\Models\UsersExt::getuserext($order->suserid); echo $sellname->name ?></td>
                    <td><?php $purname= Mdg\Models\UsersExt::getuserext($order->puserid); echo $purname->name ?></td>
                    <td>{{ date("Y-m-d H:i:s",order.addtime) }}</td>
                    <td>{{ orderstate[order.state] }}</td>
                    <td><?php echo isset($pay_type[$order->pay_type]) ? $pay_type[$order->pay_type] : '网银直连'; ?></td>
                    <td>{{ order.total }}</td>
                    <td><a href="/manage/orders/info/{{order.id}}/{{current}}">查看</a></td>
                </tr>
            {% endfor %}
            {% endif %}
     </table>
</div>
{{ form("orders/index", "method":"get") }}
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