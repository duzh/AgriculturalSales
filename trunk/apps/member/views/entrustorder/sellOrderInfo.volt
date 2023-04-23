{{ partial('layouts/page_header') }}
<div class="center-wrapper pb30 f-oh">
    <div class="bread-crumbs w1185 mtauto">
        <span>{{ partial('layouts/ur_here') }}委托订单详细</span>
    </div>
    <!-- 委托交易 订单详情 -->
    <div class="esc-order mtauto f-oh">

        <div class="title">订单详情</div>
        <div class="esc-orderInfo">

            <div class="m-title">订单信息</div>
            <table cellpadding="0" cellspacing="0" class="info">
                <tr height="30">
                    <th align="right" width="72">父订单编号：</th>
                    <td align="left" width="152">{{ pinfo.order_sn }}</td>
                    <th align="right" width="72">订单编号：</th>
                    <td align="left" width="152">{{ order.order_detail_sn }}</td>
                </tr>
                <tr height="30">
                    <th align="right">订单类型：</th>
                    <td align="left">委托交易</td>
                    <th align="right">订单状态：</th>
                    <td align="left">{{ order.orderstatus }}</td>
                </tr>
                <tr height="30">
                    <th align="right">创建时间：</th>
                    <td align="left">{{ date('Y-m-d H:i:s', order.add_time )}}</td>
                    <th align="right"></th>
                    <td align="left"></td>
                </tr>
            </table>
            

            <div class="m-line"></div>
            <div class="m-title">买家信息</div>
            <table cellpadding="0" cellspacing="0" class="info">
                <tr height="30">
                    <th align="right">名称：</th>
                    <td align="left">{{ pinfo.buy_user_name }}</td>
                </tr>
                <tr height="30">
                    <th align="right">电话：</th>
                    <td align="left">{{ pinfo.buy_user_phone }}</td>
                </tr>
            </table>
            <div class="m-line"></div>
            <div class="m-title">卖家信息</div>
            <table cellpadding="0" cellspacing="0" class="info">
                <tr height="30">
                    <th align="right" width="72">名称：</th>
                    <td align="left">{{ order.sell_user_name}}</td>
                </tr>
                <tr height="30">
                    <th align="right" width="72">电话：</th>
                    <td align="left">{{ order.sell_user_phone}}</td>
                </tr>
                <tr height="30">
                    <th align="right" width="72">收款银行：</th>
                    <td align="left">{{ order.bankname}}</td>
                </tr>
                <tr height="30">
                    <th align="right" width="72">卡号：</th>
                    <td align="left">{{ order.user_bank_card }}</td>
                </tr>
                <tr height="30">
                    <th align="right" width="72">账户名：</th>
                    <td align="left">{{ order.user_bank_account }}</td>
                </tr>
                <tr height="30">
                    <th align="right" width="72">银行所在地：</th>
                    <td align="left">{{ order.user_bank_address }}</td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" width="100%" class="list">
                <tr height="38">
                    <th width="20%">采购名称</th>
                    <th width="20%">采购类别</th>
                    <th width="20%">采购价格</th>
                    <th width="20%">采购数量</th>
                    <th width="20%">采购金额</th>
                </tr>
                <tr height="38">
                    <td align="center">{{ order.goods_name }}</td>
                    <td align="center">{{ order.category }}</td>
                    <td align="center"> <i>{{ order.goods_price }}</i>
                        元／<?php echo isset($_goods_unit[$order->goods_unit]) ? $_goods_unit[$order->goods_unit] : '';?> 
                    </td>
                    <td align="center">
                        {{ order.goods_number }}
                        <?php echo isset($_goods_unit[$order->goods_unit]) ? $_goods_unit[$order->goods_unit] : '';?>
                </td>
                    <td align="center"> <i>{{ order.order_amount }}</i>
                        元
                    </td>
                </tr>
            </table>

        </div>
        <div class="esc-orderPrice"> <font>订单总金额：{{ order.order_amount  }}元</font> 
            <!-- <font>－使用补贴： {{ order.subsidy_amount  }}元</font>  -->
            <strong>应付总金额：
                <i>{{ order.goods_amount  }}</i>
                元</strong> 
        </div>

    </div>

</div>
{{ partial('layouts/footer') }}