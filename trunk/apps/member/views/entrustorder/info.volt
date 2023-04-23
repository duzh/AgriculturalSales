{{ partial('layouts/page_header') }}
<div class="center-wrapper pb30 f-oh">

    <!-- 委托交易 详情（付款） -->
    <div class="esc-order-pay w1190 mtauto f-oh mt20">
        <div class="esc-pay-process">

            <div class="title">委托交易</div>
            <div class="pay-step clearfix ">
                <div class="step step1 {% if order.order_status > 2 %}active{% endif %} ">
                    <div class="num">1</div>
                    <div class="con">创建交易</div>
                </div>
                <div class="step step2 {% if order.order_status  > 2 %}active{% endif %}">
                    <div class="num">2</div>
                    <div class="con">付款</div>
                </div>
                <div class="step step3 {% if order.order_status  > 3 or detailOrderMaxState > 5   %}active{% endif %} ">
                    <div class="num">3</div>
                    <div class="con">线下交割</div>
                </div>
                <div class="step step4 {% if order.order_status  > 3 or detailOrderMaxState > 5  %}active{% endif %} ">
                    <div class="num">4</div>
                    <div class="con">确认收货</div>
                </div>
                <div class="step step5 {% if order.order_status  == 6  %}active{% endif %} ">
                    <div class="num">5</div>
                    <div class="con">完成交易</div>
                </div>
            </div>

        </div>
        <div class="esc-pay-box">

            <div class="esc-pay-message">
                <table cellpadding="0" cellspacing="0" width="705" class="f-oh">
                    <tr height="30">
                        <td width="235"> <font>订单编号：</font> <strong>{{ order.order_sn }}</strong>
                        </td>
                        <td width="250"> <font>订单状态：</font> <strong>{{ order.orderstatus }}</strong>
                        </td>
                        <td width="220">
                            <font>采购时间：</font>
                            {{ date('Y-m-d H:i', order.add_time )}}
                        </td>
                    </tr>
                    <tr height="30">
                        <td>
                            <font>商品名称：</font>
                            {{ order.goods_name }}
                        </td>
                        <td>
                            <font>采购类别：</font>
                            {{ order.category}}
                        </td>
                        <td>
                            <font>商品单价：</font> <i>{{ order.goods_price }}</i>
                            元／
                            <?php echo isset($_goods_unit[$order->goods_unit]) ? $_goods_unit[$order->goods_unit] : '';?></td>
                    </tr>
                    <tr height="30">
                        <td colspan="3" class="f-oh" style="padding:10px 0;">
                            <font class="f-fl" style="display:inline-block; line-height:18px;">备&nbsp;&nbsp;注：</font>
                            <span style="display:inline-block; width:654px; float:left; line-height:18px; color:#333;">{{ order.demo }}</span>
                        </td>
                    </tr>
                </table>
                {% if order.order_status ==  3 %}
                <div class="btns">
                    <a class="btn1" href="/member/entrustorder/postPay/{{ order.order_id }}">付  款</a>
                    <a class="btn2" href="/member/entrustorder/orderShut/{{ order.order_id }}">关闭交易</a>
                </div>
                {% elseif order.order_status > 3 and  order.order_status < 6   %}
                    <div class="btns">
                    <a class="btn1" href="/member/entrustorder/orderComplete?osn={{ order.order_sn }}">统一收货</a>
                </div>
                {% endif %}
            </div>
            <div class="esc-pay-list">

                <table cellpadding="0" cellspacing="0" width="100%" class="list">
                    <tr height="40">
                        <th width="13%">子订单号</th>
                        <th width="10%">手机号</th>
                        <th width="10%">名称</th>
                        <th width="11%">采购数量</th>
                        <th width="11%">采购金额</th>
                        <th width="11%">收款银行</th>
                        <th width="10%">开户名</th>
                        <th width="10%">子订单状态</th>
                        <th width="14%">操作</th>
                    </tr>
                </table>

                <!-- 列表 -->
                {% for key , item in detailOrder %}
                <div class="esc-payTable">
                    <div class="box clearfix">
                        <span class="w1">{{ item.order_detail_sn}}</span>
                        <span class="w2">{{ item.sell_user_phone }}</span>
                        <span class="w3">{{ item.goods_name }}</span>
                        <span class="w4">
                            {{ item.goods_number}}
                            <?php echo isset($_goods_unit[$item->goods_unit]) ? $_goods_unit[$item->goods_unit] : '';?></span>
                        <span class="w5"> <i>{{ item.goods_price }}</i>
                            元
                        </span>
                        <span class="w6">{{ item.bankname }}</span>
                        <span class="w7">{{ item.user_bank_account }}</span>
                        <span class="w8">{{ item.orderstatus }}</span>
                        <span class="w9">
                            <a href="/member/entrustorder/sellOrderInfo?oid={{ item.order_detail_id }}" target="_blank" >查看详情</a>
                            {% if item.order_status == 4  %}
                            <a class="btn1" href="/member/entrustorder/orderDetailComplete/{{ item.order_detail_id }}">确认收货</a>
                            {% endif %}
                           

                        </span>
                    </div>
                </div>
                {% endfor %}
                <!-- 金额 -->
                <div class="esc-payPrice f-tar">
                    <span>
                        采购总量：
                            {{ order.goods_number }}
                        <?php echo isset($_goods_unit[$order->goods_unit]) ? $_goods_unit[$order->goods_unit] : '';?></span>
                    <span>
                        总采购金额：
                        <i>{{ order.goods_amount}}</i>
                        元
                    </span>
                </div>

            </div>

        </div>
    </div>

</div>

<!-- 底部 -->
{{ partial('layouts/footer') }}