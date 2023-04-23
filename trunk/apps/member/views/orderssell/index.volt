<!--头部-->
{{ partial('layouts/member_header') }}
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span><a href="/">首页</a>&nbsp;&gt;&nbsp;<a href="/member">个人中心 </a>&nbsp;&gt;&nbsp;我卖出的订单</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="my-buyOrder f-oh pb30">
                    <div class="title f-oh">
                        <span class="f-fl">我卖出的订单</span>
                        {% if !is_brokeruser.is_broker %}
                        <a class="f-fr" href="/member/orderssell/sellList"  style="color:#2d64b3;font-weight:normal;font-size:12px;">委托订单</a>
                        {% endif %}
                    </div>
                    <form action="/member/orderssell/index" method="get">
                    <div class="m-box clearfix">
                        <div class="message clearfix">
                            <font>订单成交时间：</font>
                            <input id="d4311" class="Wdate" type="text" value='{{ stime }}' name="stime"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})">
                            <i>至</i>
                            <input id="d4312" class="Wdate" type="text" value='{{ etime }}' name="etime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})">
                        </div>
                        <div class="message clearfix">
                            <font>订单状态：</font>
                            <select name="state">
                                <option value="0">全部状态</option>
                                {% for key, val in orders_state %}
                                <option value="{{ key }}" {% if (state == key) %} selected="selected" {% endif %} >{{ val }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <input class="btn" type="submit" value="搜  索">
                    </div>
                    <table cellpadding="0" cellspacing="0" width="100%" class="list">
                        <tr height="31">
                            <th width="480">
                                <div class="m-left">供应商品信息</div>
                            </th>
                            <th width="160">订单状态</th>
                            <th width="284">
                                <span class="m-right">操作</span>
                            </th>
                        </tr>
                        {% for key, order in data.items %}
                        <tr height="28">
                            <td colspan="3">
                                <div class="m-box clearfix">
                                    <span class="num f-fl">订单号：{{ order.order_sn }}</span>
                                    <span class="sj f-fr">下单时间：{{ date('Y-m-d H:i:s', order.addtime) }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr height="139">
                            <td>
                                <div class="m-left">
                                    <dl class="clearfix">
                                        <dt class="f-fl">
                                            <img src="<?php $sell=Mdg\Models\Sell::getSellThumb($order->sellid);echo $sell ? $sell : 'http://static.ync365.com/mdg/images/detial_b_img.jpg'; ?>" height="120" width="120" alt="">
                                        </dt>
                                        <dd class="f-fl">
                                            商品名称：<a style="display:inline-block;" href="/sell/info/{{order.sellid}}" >{{ order.goods_name }}</a><br />
                                            单品价格：<i>{% if order.state == 2 %}<?php $orderprice=Mdg\Models\Sell::getprice($order->sellid); if($orderprice){ echo $orderprice;}else{ echo $order->price;}?> {% else %}{{ order.price }}{% endif %}</i>元／{{ goods_unit[order.goods_unit] }}<br />
                                            购买数量：{{ order.quantity }}{{ goods_unit[order.goods_unit] }}
                                        </dd>
                                    </dl>
                                </div>
                            </td>
                            <td align="center">{{ orders_state[order.state] }}</td>
                            <td>
                                <span class="m-right">
                                    <a href="/member/orderssell/info/{{ order.id }}">订单详情</a>
                                    {% if (order.state == 2) %}
                                    <a class="f-db mb5 sz_price" href="javascript:newWindows('editprice', '设置价格', '/member/dialog/editprice/{{ order.id }}');">设置价格</a>
                                    <a class="f-db" href="javascript:cancelOrder({{ order.id }});">取消交易</a>
                                    {% endif %}
                                    {% if(order.state == 4)%}
                                    <a class="f-db mb5" href="javascript:sendOrder({{ order.id }});">去发货</a>
                                    {% endif %}
                                </span>
                            </td>
                        </tr>
                         {% endfor %}
                    </table>
                    <!-- 分页 -->
                    {% if total_count>1 %}
                    <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                        {{ pages }}
                        <span>
                        <label>去</label>
                        <input type="text" name='p' id="p" value="1" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">
                        <input type="hidden" name="total" value="{{total_count}}">
                        <label>页</label>
                        </span>
                        <input class="btn" type="submit" value="确定" onclikc="go()">
                    </div>
                    {% endif %}
                    </form> 
                </div>

            </div>
        <!--右侧 end-->
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}

<!-- 弹框 start -->
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/center_trHover.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<script>
function go(){
var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}
var type = 0;
function btnHover(obj){
    obj.hover(function(){
        $(this).addClass('hover');
    }, function(){
        $(this).removeClass('hover');
    });
};
jQuery(document).ready(function(){
    var searchBtn = $('.top_search .btn');
    btnHover(searchBtn);
    
    var focusInput = $('input[type="text"]');
    inputFb(focusInput);

    $("#stime").datepicker();
    $("#etime").datepicker();
});

function cancelOrder(oid) {
    $.getJSON('/member/orderssell/cancel', { oid : oid }, function(data) {
        alert(data.msg);
        if(data.state) {
            window.location.reload();
        } 
    });
}
function sendOrder(oid) {
    
     newWindows('setdev', '发货', '/member/dialog/setdev&id='+oid);

}

</script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>