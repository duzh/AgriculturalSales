<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span><a href="/">首页</a>&nbsp;&gt;&nbsp;<a href="/member">个人中心 </a>&nbsp;&gt;&nbsp;<a href="/member/orderssell/index">我卖出的订单 </a>&nbsp;&gt;&nbsp;订单详情</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="orderDetial f-oh pb30">
                    <div class="title f-oh">
                        <span>订单详情</span>
                    </div>
                    <div class="statu f-oh" style="padding-right:20px;">
                        <span class="f-fl">订单号：{{ order.order_sn }}</span>
                        <span class="f-fl mr0">下单时间：{{ date('Y-m-d H:i:s', order.addtime) }}</span>
                        <span class="f-fr mr0">当前状态：{{ order_state[order.state]}}</span>
 
                    </div>
                    <div class="m-box">
                        <div class="m-title">商品信息</div>
                        <div class="message">
                            商品名称：{{ order.goods_name }}<br />
                            规格描述：{{ spec ? spec : '' }}<br />
                            <font>单品价格：<i>{% if order.state == 2 %}
                        <?php $orderprice=Mdg\Models\Sell::getprice($order->
                        sellid); if($orderprice){ echo $orderprice;}else{ echo $order->price;}?> {% else %}{{ order.price }}{% endif %}元/{{ goods_unit[order.goods_unit] }}</i></font><font>购买数量： <i>{{ order.quantity }}{{ goods_unit[order.goods_unit] }}</i></font><br />
                            服务工程师：{{ Engineer_phone }}
                        </div>
                        <div class="price">
                            应付总额：<i>{{ order.total }}元</i>
                            {% if order.commission_party == 1 %}
                            (含佣金：<i>{{ order.commission }}元</i>)
                            {% endif %}
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="m-box">
                        <div class="m-title">采购商信息</div>
                        <div class="message">
                            姓名：{{ order.purname }}<br />
                            电话：{{ order.purphone }}<br />
                            地址：{% if order.address %}{{order.address}}{% endif %}
                        </div>
                    </div>
                    <div class="btns f-oh">
                    {% if (order.state == 4 ) %}
                        <a href="javascript:void(0);" onclick="sendOrder({{ order.id }});">发货</a>
                    {% endif %}
                    {% if (order.state == 2) %}
                        <a class="go_fk_btn" href="javascript:newWindows('editprice', '设置价格', '/member/dialog/editprice/{{ order.id }}');" type="button" >设置价格</a>
                    {% endif %}
                    </div>
                </div>

            </div>
        <!-- 右侧end -->
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<script type="text/javascript" src="js/inputFocus.js"></script>
<script>
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

});
// jQuery(document).ready(function(){
//     $('.go_fk_btn').click(function(){
//         $.getJSON('/member/ordersbuy/send', {oid : {{ order.id }}}, function(data) {
//             alert(data.msg);
//             if(data.state) {
//              //    window.location.reload();
//             }
//         })
//     })
// });
function confirmOrder(oid) {
    $.getJSON('/member/orderssell/confirm', {oid : oid}, function(data) {
        alert(data.msg);
        if(data.state) {
            window.location.reload();
        }
    })
}
function sendOrder(oid) {
     newWindows('setdev', '发货', '/member/dialog/setdev&id='+oid);
}
</script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>