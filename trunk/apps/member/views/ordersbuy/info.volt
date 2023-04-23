<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span><a href="/">首页</a>&nbsp;&gt;&nbsp;<a href="/member">个人中心 </a>&nbsp;&gt;&nbsp;<a href="/member/ordersbuy/index">我买入的订单</a>&nbsp;&gt;&nbsp;订单详情</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="orderDetial f-oh pb30">
                    <div class="title f-oh">
                        <span>订单详情</span>
                    </div>
                    <div class="statu">
                        <span>订单号：{{ order.order_sn }}</span>
                        <span>下单时间：{{ date('Y-m-d H:i:s', order.addtime) }}</span>
                        <span class="mr0">当前状态：{{ order_state[order.state]}}</span>
                    </div>
                    <div class="m-box">
                        <div class="m-title">商品信息</div>
                        <div class="message">
                            商品名称：{{ order.goods_name }}<br />
                            规格描述：{{ spec ? spec : '' }}<br />
                            <font>单品价格：<i>{% if order.state == 2 %}<?php $orderprice=Mdg\Models\Sell::getprice($order->sellid); if($orderprice){ echo $orderprice;}else{ echo $order->price;}?> {% else %}{{ order.price }}{% endif %} 元/{{ goods_unit[order.goods_unit] }}</i></font><font>购买数量： <i>{{ order.quantity }}{{ goods_unit[order.goods_unit] }}</i></font><br />
                            服务工程师：{{ Engineer_phone }}
                        </div>
                        <div class="price">
                            应付总额：<i>{{ order.total }}元</i>
                            {% if order.commission_party == 2 %}
                            (含佣金：<i>{{ order.commission }}元</i>)
                            {% endif %}
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="m-box">
                        <div class="m-title">供应商信息</div>
                        <div class="message">
                            姓名：{{ order.sname }}<br />
                            电话：{{ order.sphone }}<br />
                            地址：{% if address %}{{address}}{% endif %}
                        </div>
                    </div>
                    {% if !isorder   %}
                        <div class="btns f-oh"> <!--and !flag-->
                        {% if (order.state == 3 ) %}
                            <!--<a href="/member/ordersbuy/payorder/{{ order.id}}" onclick="showDistplay({{order.id}})" target="_blank">去 付 款</a>-->
                            <a href="/member/ordersbuy/payorderpro?gate_id=1&order_id={{ order.id}}" onclick="showDistplay({{order.id}})" target="_blank">去 付 款</a>
                            <a href="javascript:cancelOrder({{ order.id }});">取 消 订 单</a>
                        {% endif %}
                        {% if (order.state == 5) %}
                            <a class="go_fk_btn" href="javascript:void(0);" type="button" >确认收货</a>
                        {% endif %}
                        </div>
                    {% endif %}
                </div>

            </div>
        <!-- 右侧end -->
    </div>
</div>
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
</div>


<script>

/* 显示 */
function showDistplay(value) {

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
<!--底部-->
{{ partial('layouts/footer') }}}