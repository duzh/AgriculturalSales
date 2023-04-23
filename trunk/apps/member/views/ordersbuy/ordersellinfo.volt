{{ partial('layouts/page_header') }}


    <div class="center-wrapper pb30">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我买入的订单</span>
        </div>
        <div class="w1185 mtauto clearfix">
            <!-- 左侧 -->
            {{ partial('layouts/navs_left') }}
            <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="business-order">
                
                <div class="fk_btn">
            <span style="margin-right:0;"><em>订单号：{{ order.order_sn }}</em><em>下单时间：{{ date('Y-m-d H:i:s', order.addtime) }}</em><em>当前状态：{{ order_state[order.state]}}</em></span>
            {% if (order.state == 3 and !flag ) %}

            <a href="#" onclick="showDistplay({{order.id}})" target="_blank" style="float:left;">
                <input  type="button" value="去付款" style="width:50px; background:#eaa82c; margin-right:6px; cursor:pointer;">
            </a>
           <a href="javascript:cancelOrder({{ order.id }})" style="float:left;"><input  type="button" value="取消订单" style="width:60px; background:#eaa82c; cursor:pointer;"></a>
            {% endif %}
            {% if (order.state == 5) %}
            <input class="go_fk_btn" style="background:#eaa82c; float:right; margin-right:20px; cursor:pointer;" type="button" value="确认收货">
            {% endif %}
        </div>
        <!-- 块 start -->
        <div class="fk_message mb10">
            <h6 class="clear">商品信息</h6>
            <div class="box">
                <p class="f-fl">
                    商品名称：{{ order.goods_name }}<br>
                    规格描述：{{ spec ? spec : '' }}<br>
                    单品价格：<strong class="f-fs14 mr20">{% if order.state == 2 %}<?php $orderprice=Mdg\Models\Sell::getprice($order->sellid); if($orderprice){ echo $orderprice;}else{ echo $order->price;}?> {% else %}{{ order.price }}{% endif %}元/{{ goods_unit[order.goods_unit] }}</strong>购买数量：<strong class="f-fs14">{{ order.quantity }}{{ goods_unit[order.goods_unit] }}</strong><br>
                    {% if order.activity_id > 0  and order.except_shipping_type == 1 %}
                    送货方式：送货上门<br>
                    预计送货时间：{{date("Y-m-d",order.except_shipping_time)}}<a onclick="javascript:newWindows('changequantity', '修改预计送货时间', '/member/dialog/changeestime/{{ order.id }}/1');">修改</a><br>
                    送货信息：{{ order.purname }}{{order.purphone}}
                    {{order.address}}
                    {% endif %}

                    {% if order.activity_id > 0  and order.except_shipping_type == 2 %}
                    送货方式：自行采摘<br>
                    预约采购时间：{{date("Y-m-d",order.except_shipping_time)}}&nbsp;&nbsp;
                    <a onclick="javascript:newWindows('changequantity', '修改预约采购时间', '/member/dialog/changeestime/{{ order.id }}/2');">修改</a><br>
                    送货信息：{{ order.purname }}&nbsp;&nbsp;{{order.purphone}}
                    {{order.address}}
                    {% endif %}
                </p>
                <span class="f-fr">
                    货款<br>
                    <strong class="f-fs18">{{ order.total }}元</strong>
                </span>
            </div>
        </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <div class="fk_message">
            <h6 class="clear">供应商信息</h6>
            <div class="box">
                <p class="f-fl">
                    姓名：{{ order.sname }}<br>
                    电话：{{ order.sphone }}<br>
                    地址：{% if address %}{{address}}{% endif %}
                </p>
            </div>
        </div>
        <!-- 块 end -->
    </div>
                    

                </div>

            </div>
        </div>
    </div>

    <!-- 底部 -->
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
<a class="close_btn" href="/member/ordersbuy/info/{{ order.id }}">关闭</a>
<div class="title"></div>
<div class="catchTip" style='border-bottom:0px'>
    <div class="btns clearfix">
        <a class="f-db f-fl" href="/member/ordersbuy/info/{{ order.id }}">已完成支付</a>
        <a class="f-db f-fr" href="/member/ordersbuy/info/{{ order.id }}">支付遇到问题</a>
    </div>
</div>


</div>


<script>

/* 显示 */
function showDistplay(value) {
    window.open("/member/ordersbuy/payorder/"+value+"");
    $('#show').show();
}


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
    $('.go_fk_btn').click(function(){
        $.getJSON('/member/ordersbuy/finish', {oid : {{ order.id }}}, function(data) {
            alert(data.msg);
            if(data.state) {
                window.location.reload();
            }
        })
    })
});
function cancelOrder(oid) {

    $.getJSON('/member/ordersbuy/cancel', { oid : oid }, function(data) {
        alert(data.msg);
        if(data.state) {
            window.location.reload();
        } 
    });
}
</script>
   

</body>
</html>
