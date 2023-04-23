<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main" >
  <div class="main_right">
    <div class="bt2">委托交易列表</div>

    <div class="chaxun">
      {{ form("entrusttrad/index", "method":"get", "autocomplete" : "off") }}
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  align="right">订单状态:</td>
          <td  align="left">
            <select name="order_status" id="order_status" style="width:140px" >
              <option value="all">请选择</option>
			  {% for key,state in orderstate %}
              <option value="{{key}}" {% if key==statue %} selected {% endif %}>{{state}}</option>
              {% endfor %}
			  
            </select>            
          </td>
           <td  align="right">商品分类:</td>
           <td align="left">
            <select name="maxcategory" class="selectCate" style="width:140px">
             <option value="">选择分类</option>
            </select>
            <select name="category" class="selectCate" style="width:140px">
            <option value="">选择分类</option>
            </select>
           </td>
		   <td  align="right">订单号:</td>
		   <td  align="left"><input type='text' name="order_detail_sn" value="{{order_detail_sn}}"/></td>
		   <td  align="right">父订单号:</td>
		   <td  align="left"><input type='text' name="order_parent_sn" value="{{order_parent_sn}}"/></td>         
        </tr>
		<tr>
		   <td  align="right">供货商:</td>
		   <td  align="left"><input type='text' name="sell_user_name" value="{{sell_user_name}}"/></td>
		   <td  align="right">采购商:</td>
		   <td  align="left"><input type='text' name="buy_user_name"  value="{{buy_user_name}}"/></td> 
		   <td  align="right">银行交易流水:</td>
		   <td  align="left"><input type='text' name="bank_sn" value="{{bank_sn}}"/></td>
		   <td  align="right">支付方式:</td>
		   <td  align="left">
		   <select name="pay_type" style="width:130px">
            <option value="all" {% if pay_type == 'all' %} selected {% endif %}>请选择</option>
			<option value="0"	{% if pay_type == '0' %} selected {% endif %}>云农宝</option>
      <option value="1" {% if pay_type == '1' %} selected {% endif %}>农业银行</option>
			<option value="6"	{% if pay_type == '6' %} selected {% endif %}>后台支付</option>
            </select>
		   </td> 
        </tr>
        <tr>
			  <!-- <td  align="right">订单类型:</td>
			   <td  align="left">
			   <select name="order_type" style="width:130px">
				<option value="all">选择分类</option>
				<option value="0">云农宝</option>
				<option value="1">农业银行</option>
				</select>
			   </td>
			   <td  align="right">订单渠道:</td>
			   <td  align="left">
				<select name="order_canal" style="width:130px">
				<option value="all">选择分类</option>
				<option value="0">云农宝</option>
				<option value="1">农业银行</option>
				</select>
			   </td> -->
		   <td  align="right">农云宝交易流水:</td>
		   <td  align="left"><input type='text' name="ynp_sn" value="{{ynp_sn}}" /></td>
		   <td  align="right">添加时间:</td>
		   <td  align="left">
		    <input readonly="readonly"  type="text" class="Wdate" name="addstime" id="d4331" 
             onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{{addstime}}">-
            <input readonly="readonly" type="text" class="Wdate" name="addetime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{{addetime}}">
		   </td> 
		    <td  align="right">支付时间:</td>
		   <td  align="left">
		    <input readonly="readonly"  type="text" class="Wdate" name="paystime" id="d4331" 
             onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{{paystime}}">-
            <input readonly="readonly" type="text" class="Wdate" name="payetime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{{payetime}}">
		   </td> 
        </tr>
      </table>
      <div class="btn">{{ submit_button("确定",'class':'sub') }}</div>   
  </form>  
  <div class="neirong" id="tb" ><!--style="overflow-x:scroll;width:1350px"-->
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width='3%'  class="bj">序号</td>
        <td width='7%' class="bj">订单号</td>
        <td width='7%' class="bj">父订单号</td>
        <td width='7%' class="bj">订单类型</td>
        <td width='7%' class="bj">农云宝交易流水</td>
        <td width='7%' class="bj">银行流水</td>
        <td width='7%' class="bj">商品名称</td>
        <td width='7%' class="bj">供货商</td>
        <td width='7%' class="bj">采购商</td>
		<td width='7%' class="bj">下单时间</td>
		<td width='7%' class="bj">支付时间</td>
        <td width='7%' class="bj">订单状态</td>
		<td width='7%' class="bj">支付方式</td>
        <td width='7%' class="bj">订单金额</td>
		<td width='7%' class="bj">支付金额</td>
		<td width='7%' class="bj">操作</td>
      </tr>
      <?php $i=($current-1)*10+1 ?>
      {% if data is defined %}
      {% for key,val in data %}
      <tr align="center">
        <td><?php echo $i++ ;?></td>
        <td>{{ val['order_detail_sn'] }}</td>
        <td><a href="/manage/entrusttrad/parentorderinfo/{{ val['order_parent_sn'] }}/{{current}}">{{ val['order_parent_sn'] }}</a></td>
        <td>委托交易 </td>
        <td>{{ val['ynp_sn']}}</td>
        <td>{{ val['bank_sn'] }}</td>
        <td>{{ val['goods_name']}}</td>
        <td>{{ val['sell_user_name']}}</td>
        <td>{{ val['buy_user_name']}} </td>
		<td> {{  date("Y-m-d H:i:s",val['order_time']) }} </td>
		<td> {{  val['pay_time'] ? date("Y-m-d H:i:s",val['pay_time']) : '-' }} </td>
        <td>
		{{ orderstate[ val['order_status'] ] }} 
		</td>
		<td>{{ paytypearr[ val['pay_type' ] ] }}</td>
		<td>{{ val['goods_amount']}}</td>
		<td> {{ val['goods_amount']-val['subsidy_amount'] }} </td>
		<!-- <?php echo round($val['goods_amount']-$val['subsidy_amount'] , 2);  ?>-->
		<td> <a href="/manage/entrusttrad/childorderinfo/{{ val['order_detail_id'] }}/{{current}}">查看</a> </td>
		
      </tr>
      </tr>
	  
      {% endfor %}
      {% endif %}
      
    </table>
  </div>
  {{ form("crediblefarm/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ pages }}</span> <em>跳转到第
        <input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
          <?php unset($_GET['p']);
              foreach ($_GET as $key => $val) {

          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
</div>
 </div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright ? 2013-2014 ync365.com All rights reserved.</div>
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