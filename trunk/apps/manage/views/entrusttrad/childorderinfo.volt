<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">子订单详情  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/manage/entrusttrad/index?p={{page}}'>返回</a></div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="4">订单信息</td>
                    </tr>
                    <tr>
                        <td align="right">父订单编号：</td>
                        <td>{{ data.order_parent_sn }}</td>
                        <td align="right">订单编号：</td>
                         <td>{{ data.order_detail_sn }}</td>
                    </tr>
                    <tr>
                        <td align="right">订单类型：</td>
                        <td>委托交易</td>
                        <td align="right">订单状态：</td>
                        <td>{{ orderstate[ data.order_status ] }} </td>
                    </tr>
                    <tr>
                        <td align="right">创建时间：</td>
                        <td>{{ date('Y-m-d H:i:s', data.order_time ) }}</td>
                        <td align="right"></td>
                        <td><!--{{ date('Y-m-d H:i:s', order.addtime) }}--></td>
                    </tr>
                  
                </tbody>
            </table>
        </div>
		<div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="4">买家信息</td>
                    </tr>
                    <tr >
                        <td align="right">名称：</td> 
						<td> {{ data.ext.buy_user_name }} </td>
                        <td align="right">电话：</td>   
						<td>{{ data.ext.buy_user_phone }}</td>  
                    </tr>
                </tbody>
            </table>
        </div>
		<div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="4">卖家信息</td>
                    </tr>
                    <tr>
                        <td align="right">名称：</td> 
                        <td> {{ data.sell_user_name }} </td>
						<td align="right">电话：</td>    
						<td> {{ data.sell_user_phone }} </td>						
                    </tr>
					 <tr >
                        <td align="right">收款银行：</td> 
						<td> {{ bankname }} </td>
                        <td align="right">卡号：</td>  
						<td> {{ data.user_bank_card }} </td>						
                    </tr>
					<tr >
                        <td align="right">账户名：</td> 
						<td> {{ data.user_bank_account }} </td>
                        <td align="right">银行所在地：</td> 
						<td> {{ data.user_bank_address }} </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
				<tr align="center">
                        <td class="bj" colspan="5">商品信息</td>
                    </tr>
                    <tr align="center">
                        <td class="bj">采购名称</td>
						<td class="bj">采购类别</td>
						<td class="bj">采购价格</td>
						<td class="bj">采购数量</td>
						<td class="bj">采购金额</td>
                    </tr>
                    <tr align="center">
						<td >{{ data.goods_name }}</td>
						<td >{{ categorynameone }} - {{ categorynametwo }}</td>
						<td >{{ data.goods_price }}</td>
						<td >{{ data.goods_number }}</td>
						<td >{{ data.goods_amount }}</td>                      
                    </tr>
					<tr align="right">
						<td colspan='5'>订单总额:{{ data.goods_amount }}</td>						                    
                    </tr>
                </tbody>				
            </table>
        </div>
		<div class="dingd" id="tb">
            <form action="/manage/entrusttrad/updatechildstate/" method="post" id="myform">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td align="right">备注：</td>
                        <td colspan="5" align="left">
                            <div class="cx_content1">
                                <textarea name="content" id="content" cols="" rows=""></textarea>
                            </div>
                           
                        </td>
                          <input type="hidden" name="order_id" value="{{data.order_detail_id}}">
                    </tr>
                    <tr height="50">
                        <td align="right" height="40">当前可操作：</td>
                        <td colspan="5" align="left">
							
                            {% if (data.order_status == 5) %}
                         <input type="submit" onclick=" return changeState();" value="确认收货" class="sub">
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
                        <td> {{ orderstate[ log.state ] }}   </td>
                        <td>{{ log.demo }}</td>
                    </tr>
                    {% endfor %}  
                </tbody>
            </table>
            </form>
        </div>
<script>

        function changeState(){
            if($("#content").val()!=""){                              
                $("#myform").submit(); 
            }else{
                alert("操作备注不能为空");
                return false;
            }    
        }
  
</script>