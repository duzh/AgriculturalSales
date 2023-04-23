<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">订单查看  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/manage/orders/index?p={{page}}'>返回</a></div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="4">订单详情</td>
                    </tr>
                    <tr>
                        <td align="right">订单号：</td>
                        <td>{{ order.order_sn }}</td>
                        <td align="right">购货人：</td>
                        <td>{{ order.purname }}</td>
                    </tr>
                    <tr>
                        <td align="right">订单状态：</td>
                        <td>{{ orderstate[order.state] }}</td>
                        <td align="right">下单时间：</td>
                        <td>{{ date('Y-m-d H:i:s', order.addtime) }}</td>
                    </tr>
                     <tr>
                        <td align="right">支付方式：</td>
                        <td>{% if order.state >= 4 %}{{pay_type[order.pay_type]}}{% endif %}</td>
                        <td align="right">付款时间：</td>
                        <td>{{pay_time}}</td>
                    </tr>
                    <tr>
                    {% if order.activity_id > 0  and order.except_shipping_type == 1 %}
                        <td align="right">配送方式：</td>
                        <td>送货上门</td>
                        <td align="right">发货时间：</td>
                        <td>{{date("Y-m-d",order.except_shipping_time)}}
                        {% if order.state == 3 or order.state == 4 %}<a onclick="javascript:newWindows('changequantity', '修改预计送货时间', '/manage/orders/changeestime/{{ order.id }}/1');">修改</a>{% endif %}
                        </td>
                    {% endif %}
                    {% if order.activity_id > 0  and order.except_shipping_type == 2 %}
                        <td align="right">配送方式：</td>
                        <td>自行采摘</td>
                        <td align="right">发货时间：</td>
                        <td>{{date("Y-m-d",order.except_shipping_time)}}
                         {% if order.state == 3 or order.state == 4 %}<a onclick="javascript:newWindows('changequantity', '修改预约采购时间', '/manage/orders/changeeetime/{{ order.id }}/2');">修改</a>{% endif %}
                        </td>

                    {% endif %}
                    {% if !order.activity_id %}
                        <td align="right">配送方式：</td>
                        <td>{{dev_name}}</td>
                        <td align="right">发货时间：</td>
                        <td>{{dev_time}}</td>
                    {% endif %}

                    </tr> 
                    <tr>
                         <td align="right">备注：</td>
                          <td>{{order.demo}}</td>
                         <td align="right">服务工程师:</td>
                         <td>{{Engineer_phone}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="3">供应商信息</td>
                    </tr>
                    <tr align="center">
                        <td width="33%">会员账号：{{ selluser ? selluser.username : '-' }}</td>
                        <td width="33%">姓名：{{ selluser ? selluser.ext.name : '-' }}</td>
                        <td width="33%">地址：{{ selluser ? selluser.ext.address : "-" }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="3">采购商信息</td>
                    </tr>
                    <tr align="center">
                        <td width="33%">会员账号：{{ puruser ? puruser.username : '-' }}</td>
                        <td width="33%">姓名：{{ puruser ? puruser.ext.name : '-'}}</td>
                        <td width="33%">地址：{{ puruser ? puruser.ext.areas_name : '-'}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="3">收货人信息</td>
                    </tr>
                    <tr align="center">
                        <?php $purname= Mdg\Models\UsersExt::getuserext($order->puserid);?>
                        <td width="33%">姓名：{{ order ? order.purname : '-'}}</td>
                        <td width="33%">电话：{{ order ? order.purphone : '-' }}</td>
                        <td width="33%">地址：{{ order ? order.address : '-'  }}</td>
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
                        <td>商品名称</td>
                        <td>价格 </td>
                        <td>采购数量</td>
                        <td>单位</td>
                        <td>小计</td>
                    </tr>
                    <tr align="center">
                        <td>{{ order.goods_name }}</td>
                        <td>{{ order.price }}元/{{ goods_unit[order.goods_unit] }}
                              {% if (order.state == 2) %}
                                <a class="btn f-fl" href="javascript:newWindows('editprice', '设置价格', '/manage/orders/editprice&id={{order.id}}');">设置价格</a>
                              {% endif %}
                        </td>
                        <td>{{ order.quantity }}</td>
                        <td>{{ goods_unit[order.goods_unit] }}</td>
                        <td>{{ order.total }}元</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="dingd" id="tb">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr align="center">
                    <td class="bj" colspan="5">佣金信息</td>
                </tr>
                <tr align="center">
                    <td>支付方</td>
                    <td>价格 </td>
                </tr>
                <tr align="center">
                    <td>
                        {% if order.commission_party == 1 %}
                        供应方
                        {% elseif order.commission_party == 2 %}
                        采购方
                        {% else %}
                        未设置
                        {% endif %}
                    </td>
                    <td>{{ order.commission }}元</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="dingd" id="tb">
            <form action="/manage/orders/updatestate/" method="post" id="myform">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr align="center">
                        <td class="bj" colspan="6">操作信息</td>
                    </tr>
                    <tr>
                        <td align="right">操作备注：</td>
                        <td colspan="5" align="left">
                            <div class="cx_content1">
                                <textarea name="content" id="content" cols="" rows=""></textarea>
                            </div>
                           
                        </td>
                         <input type='hidden' id='shezhi' value=''>
                    </tr>
                    <tr height="50">
                        <td align="right" height="40">当前可操作：</td>
                        <td colspan="5" align="left">

                            {% if (order.state != 1) %}

                            {% if (order.state == 2) %}
                            <input type="submit" onclick=" return changeState('cancel');" value="取消订单" class="sub">
                            <input type="submit" onclick=" return changeState('confirm');" value="卖方确定" class="sub">
                            {% endif %}

                            {% if (order.state == 3) %}
                            <input type="submit" onclick=" return changeState('cancel');" value="取消订单" class="sub">
                            <input type="submit" onclick=" return changeState('setCommission');" value="设置佣金" class="sub">
                            <input type="submit" onclick=" return changeState('pay');" value="买家付款" class="sub">
                            {% endif %}
                            {% if (order.state == 4) %}
                             <input type='hidden' value='{{order.id}}' id="order_id" >
                             <input type="submit" onclick=" return changeState('send');" value="发货" class="sub">
                            {% endif %}
                            {% if (order.state == 5) %}
                            <input type="submit" onclick=" return changeState('finish');" value="完成" class="sub">
                            {% endif %}
                            <input type="hidden" name="order_id" value="{{order.id}}">
                            <input type="hidden" name="type" id="type" >
                            {% else %}
                            此订单已取消
                            {% endif %}
                            {% if order.state == 6 %}
                            此订单已完成
                            {% endif %}
                    </tr>
                    <tr align="center">
                        <td>操作者</td>
                        <td>操作时间</td>
                        <td>订单状态</td>
                        <td>备注</td>
                    </tr>
                    {% for log in orderlog %}
                    <tr align="center">
                        <td>{{log.operationname}}({% if (log.type) %}管理员{% else %}会员{% endif %})</td>
                        <td>{{date("Y-m-d H:i:s",log.addtime)}}</td>
                        <td>{{orderstate[log.state]}}</td>
                        <td>{{log.demo}}</td>
                    </tr>
                    {% endfor %}  
                </tbody>
            </table>
            </form>
        </div>
    </div>
    <!-- main_right 结束  -->
</div>

<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
<script>

        function changeState(state){
           if($("#content").val()!=""){
              
                if(state=='send'){
                    var orderid=$("#order_id").val();
                    var content=$("#content").val();
                    var url='/manage/orders/setdev?id='+orderid+'&content='+content;
                    var newurl=encodeURI(url);
                    newWindows('setdev', '发货',newurl);
                    return false;
                }
                if(state=='confirm'){
                     var shezhi=$("#shezhi").val();
                     if(shezhi==''){
                        alert("请设置价格");
                        return false;
                     }
                }
               if(state == "setCommission"){
                   newWindows('editprice', '设置佣金', '/manage/orders/set_commission?id={{order.id}}&content='+encodeURIComponent($("#content").val()))
                   return false;
               }
                $("#type").val(state);
                $("#myform").submit(); 
               
            }else{

                alert("操作备注不能为空");
                return false;
            }    
        }
  
</script>