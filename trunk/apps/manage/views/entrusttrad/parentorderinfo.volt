<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">父订单详情  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/manage/entrusttrad/index?p={{page}}'>返回</a></div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="4">订单信息</td>
                    </tr>
                    <tr>
                        <td align="right">订单编号：</td>
                        <td>{{ data.order_sn }}</td>
                        <td align="right">订单状态：</td>
                         <td>{{ orderstate[ data.order_status ] }}</td>
                    </tr>
                    <tr>
                        <td align="right">采购时间:</td>
                        <td>{{ date('Y-m-d H:i:s', data.add_time ) }}</td>
                        <td align="right">商品名称:</td>
                        <td>{{  data.goods_name  }}</td>
                    </tr>
                    <tr>
                        <td align="right">采购类别:</td>
                        <td> {{ categorynameone }} - {{ categorynametwo }}</td>
						 <td align="right">商品单价:</td>
                        <td>{{  data.goods_price }}</td>
                    </tr>
                  <tr>
                         <td align="right">备注:</td>
                        <td colspan="3">{{  data.demo }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
		
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
				
                    <tr align="center">
                        <td class="bj">子订单号</td>
						<td class="bj">手机号</td>
						<td class="bj">名称</td>
						<td class="bj">采购数量</td>
						<td class="bj">采购金额</td>
						<td class="bj">收款银行</td>
						<td class="bj">开户名</td>
						<td class="bj">子订单状态</td>
						<td class="bj">操作</td>
                    </tr>
				
					{% for detail in dataDetail %}  
                    <tr align="center">						
						<td >{{ detail['order_detail_sn'] }}</td>
						<td >{{ detail['sell_user_phone'] }}</td>
						<td >{{ detail['sell_user_name'] }}</td>
						<td >{{ detail['goods_number'] }}</td>
						<td >{{ detail['goods_amount'] }}</td> 
						<td >{{ detail['bankname']}}</td>
						<td >{{ detail['user_bank_account'] }}</td>
						<td >{{ orderstate[ detail['order_status'] ] }}</td>
						<td ><a href="/manage/entrusttrad/childorderinfo/{{ detail['order_detail_id']}}" >查看详情</a></td> 
                    </tr>
					{% endfor %}
					<tr align="right">
						<td colspan='9'>
						总采购量:{{ numbernum }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;总采购金额:{{ amountnum }} </td>						                    
                    </tr>
                </tbody>				
            </table>
        </div>
		<div class="dingd" id="tb">
            <form action="/manage/entrusttrad/updatestate/" method="post" id="myform">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td align="right">备注：</td>
                        <td colspan="5" align="left">
                            <div class="cx_content1">
                                <textarea name="content" id="content" cols="" rows=""></textarea>
                            </div>
                           
                        </td>
                          
                    </tr>
                    <tr height="50">
					
                        <td align="right" height="40">当前可操作：</td>
                        <td colspan="5" align="left">
							<input type='hidden' value='{{data.order_sn}}' name="order_sn" >	
							 <input type="hidden" name="type" id="type" >
                            {% if (data.order_status == 3) %}  
							 <input type="submit" onclick=" return changeState('closeorder');" value="关闭交易" class="sub">
                            <input type="submit" onclick=" return changeState('payorder');" value="付款" class="sub">
                            {% endif %}
							{% if (data.order_status == 5) %}                            
                             <input type="submit" onclick=" return changeState('delivery');" value="确认收货" class="sub">
                            {% endif %}
                    </tr>
                    <tr align="center">
                        <td>操作者</td>
                        <td>操作时间</td>
                        <td>当前状态</td>
                        <td>备注</td>
                    </tr>
                    {% for log in orderlog %} 
                    <tr align="center">
                        <td>{{ log.operationname }}({% if (log.type) %}管理员{% else %}会员{% endif %})</td>
                        <td>{{ date("Y-m-d H:i:s",log.addtime) }}</td>
                        <td> {{ orderstate[ log.state ] }} </td>
                        <td>{{ log.demo }}</td>
                    </tr>
                    {% endfor %}  
                </tbody>
            </table>
            </form>
        </div>
<script>

        function changeState(state){
            if($("#content").val()!=""){                              
                $("#type").val(state);
                $("#myform").submit(); 
            }else{
                alert("操作备注不能为空");
                return false;
            }    
        }
  
</script>
