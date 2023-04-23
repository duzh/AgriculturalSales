{{ partial('layouts/page_header') }}

<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}我的订单</span>
</div>
<div class="personal_center w960 mb20">
{{ partial('layouts/navs_left') }}
    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <ul class="center_list">
            <li class="active"><a href="/member/ordersbuy/index">我买的</a></li>
            <li ><a href="/member/orderssell/index">我卖的{% if ordercount %}({{ordercount}}){% endif %}</a></li>
        </ul>
        <div class="table_box">
            <!-- 块 start -->
            <div class="buy_table" style="display: block;">
                <div class="order_search">
                    <form action="/member/ordersbuy/index" method="get">
                    <span>订单成交时间：
                            <input id="d4311" class="Wdate" type="text" value='{{ stime }}' name="stime"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})"/>至 
                            <input id="d4312" class="Wdate" type="text" value='{{ etime }}' name="etime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})"/> 
                            </span>
                    <span>订单状态：<select name="state">
                        <option value="0">全部状态</option>
                        {% for key, val in orders_state %}
                        <option value="{{ key }}" {% if (state == key) %} selected="selected" {% endif %} >{{ val }}</option>
                        {% endfor %}
                    </select>
                </span>
                    <input class="btn" type="submit" value="">
                    </form>
                </div>
                <table class="f-fs12" >
                    <tbody><tr height="28" class="title">
                        <th width="474">采购商品信息</th>
                        <th width="144">订单状态</th>
                        <th width="140">操作</th>
                    </tr>
                    {% for key, order in data.items %}
                    <tr height="80" style="background: rgb(255, 255, 255);">
                        <td>
                            <p><span>订单号：{{ order.order_sn }}</span><span>下单时间：{{ date('Y-m-d H:i:s', order.addtime) }}</span></p>
                            <p>商品名称：{{ order.goods_name }}</p>
                            <p><span>单品价格：<strong>{% if order.state == 2 %}<?php $orderprice=Mdg\Models\Sell::getprice($order->sellid); if($orderprice){ echo $orderprice;}else{ echo $order->price;}?> {% else %}{{ order.price }}{% endif %}元/{{ goods_unit[order.goods_unit] }}</strong></span><span>购买数量：<strong>{{ order.quantity }}{{ goods_unit[order.goods_unit] }}</strong></span></p>
                        </td>
                        <td>
                            <?php echo isset($orders_state[$order->state]) ? $orders_state[$order->state] : '待确认'; ?></td>
                        <td>
                            <a class="f-db mb5" href="/member/ordersbuy/info/{{ order.id }}">订单详情</a>
                            {% if (order.state == 2) %}
                            <a class="f-db" href="javascript:cancelOrder({{ order.id }})">取消订单</a>
                            {% endif %}
                            {% if (order.state == 3) %}
                           
                            <a class="f-db" href="/member/ordersbuy/payorder/{{ order.id }}"  onclick="showDistplay({{ order.id}})" target="_blank">去付款</a>
                            <!-- <a class="f-db" href="/member/ordersbuy/payordernong/{{ order.id }}">农行支付</a> -->

                            {% endif %}
                            {% if (order.state == 5) %}
                             <a class="f-db" href="javascript:finishOrder({{ order.id }})">确认收货</a>
                            {% endif %}



                        </td>
                    </tr>
                    {% endfor %}
                </tbody></table>

            </div>
            <!-- 块 end -->
        </div>
        <!-- 块 start -->
        <div class="page">
            {{ pages }}
            <span>共{{ data.total_pages }}页</span>
        </div>
        <!-- 块 end -->
    </div>
    <!-- 右侧 end -->
</div>
<!-- 主体内容结束 -->

{{ partial('layouts/footer') }}

<div id='show' style='display:none'>
<!-- 弹框 start -->
<style>
.catchUser_layer{ width:100%; height:100%; background:#000; opacity:0.5; filter:alpha(opacity:50); position:fixed; z-index:100; left:0; top:0;}
.catchUser{ width:429px; height:219px; position:fixed; left:50%; top:50%; margin-top:-160px; margin-left:-215px; z-index:102;}
.catchUser .close_btn{ display:block; width:20px; height:20px; background:url(images/close_btn.png) center center no-repeat; position:absolute; right:10px; top:6px; text-indent:-10000em;}
</style>

<div class="catchUser_layer"></div>
<div class="catchUser">
<a class="close_btn" href="/member/ordersbuy">关闭</a>
<div class="title"></div>
<div class="catchTip" style='border-bottom:0px'>
    <p></p>
    <div class="btns clearfix">
        <a class="f-db f-fl" href="/member/ordersbuy?p=<?php echo isset($_GET['p']) ? $_GET['p'] : 1;?>">已完成支付</a>
        <a class="f-db f-fr"  id='memberInfo' href="/member/ordersbuy?p=<?php echo isset($_GET['p']) ? $_GET['p'] : 1;?>">支付遇到问题</a>
    </div>
</div>


</div>


<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/center_trHover.js"></script>
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<script>
/* 显示 */
function showDistplay(oid) {

    $('#memberInfo').attr("href" , "/member/ordersbuy/info/"+oid);
   $('#show').show();
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
    $.getJSON('/member/ordersbuy/cancel', { oid : oid }, function(data) {
        alert(data.msg);
        if(data.state) {
            window.location.reload();
        } 
    });
}
function finishOrder(oid){
    $.getJSON('/member/ordersbuy/finish', { oid : oid }, function(data) {
        alert(data.msg);
        if(data.state) {
            window.location.reload();
        } 
    });
}
</script>
