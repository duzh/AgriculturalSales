{{ partial('layouts/member_header') }}
<div class="center-wrapper pb30">
    <div class="bread-crumbs w1185 mtauto">
        <span>{{ partial('layouts/ur_here') }}我卖出的订单</span>
    </div>
    <div class="w1185 mtauto clearfix">
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">

            <div class="esc-mysell f-oh">

                <div class="title">
                    <span>我卖出的订单</span>
                </div>
                <form action="/member/entrustorder/sellList">

                    <div class="mysell-search">

                        <div class="message pl90"> <font>订单成交时间：</font>
                            <div class="box">
                                <input id="d4311" class="Wdate input2" type="text" value='{{ stime }}' name="stime"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})"/> <i>至</i>
                                <input id="d4312" class="Wdate input2" type="text" value='{{ etime }}' name="etime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})"/>

                            </div>
                        </div>
                        <div class="message f-oh">
                            <label class="f-db f-fl clearfix"> <font>订单状态：</font>
                                <select name="state" class='select2'>
                                    <option value="0">全部状态</option>
                                    {% for key, val in _order_status %}
                                    <option value="{{ key }}" {% if (state == key) %} selected="selected" {% endif %} >{{ val }}</option>
                                    {% endfor %}
                                </select>
                            </label>

                        </div>
                        <input class="btn" type="submit" value="查询" />
                    </div>

                </form>

                <table cellpadding="0" cellspacing="0" width="890" class="list">
                    <tr height="32">
                        <th width="16"></th>
                        <!-- <th align="left" width="165">商品图片</th>
                    -->
                    <th width="264">供应商品信息</th>
                    <th width="225">采购商信息</th>
                    <th width="117">交易金额</th>
                    <th width="124">订单状态</th>
                    <th width="124">操作</th>
                </tr>
                {% for key , order in data['items'] %}

                <tr height="28">
                    <td></td>
                    <td colspan="5">
                        <div class="message clearfix">
                            <span class="f-fl">订单号：{{ order.order_detail_sn }}</span>
                            <span class="f-fl">订单类型：委托交易</span> <em class="f-fr">{{ date('Y-m-d H:i:s' , order.add_time )}}</em>
                        </div>
                    </td>
                </tr>

                <tr height="140">
                    <td></td>
                    <td>
                        <label>
                            <font>商品名称：</font>
                            {{ order.goods_name }}
                        </label>
                        <label>
                            <font>价格：</font> <i>{{ order.goods_price }}</i>
                            元／公斤
                        </label>
                    </td>
                    <td>
                        <label>
                            <font>姓名：</font>
                            {{ order.buy_name}}
                        </label>
                        <label>
                            <font>电话：</font>
                            {{ order.buy_phone}}
                        </label>
                        <label>
                            <font>购买数量：</font>
                            {{ order.goods_number }}
                            <?php echo isset($_goods_unit[$order->goods_unit]) ? $_goods_unit[$order->goods_unit] : '';?>
                        </label>
                        <label>
                            <font>成交价：</font>
                            <i>{{ order.goods_price }}</i>
                            元／<?php echo isset($_goods_unit[$order->goods_unit]) ? $_goods_unit[$order->goods_unit] : '';?>
                        </label>
                    </td>
                    <td align="center">
                        <label>
                            <i>{{ order.goods_amount }}</i>
                            元
                        </label>
                        <!-- <span class="dd">手机订单</span> -->
                    </td>
                    <td align="center">
                        {{ order.orderstatus }}
                    </td>
                    <td align="center">
                        <a href="/member/entrustorder/sellOrderInfo?oid={{ order.order_detail_id}}" target="_blank" >订单详情</a>
                    </td>
                </tr>
                {% endfor %}
            </table>
            <!-- 分页 -->
            <form action="/member/entrustorder/sellList">
            <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                {{ data['pages']}}
                <span>
                    <label>去</label>
                    <input type="text" name='p' value='{{ page }}'/>
                    <label>页</label>
                </span>
                <input class="btn" type="submit" value="确定" />
            </div>
            </form>

        </div>
    </div>
</div>
</div>
{{ partial('layouts/footer') }}
<script type="text/javascript">
    $(function () {
           $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
                defaultParentId : 0,
                    style : {"width" : 140}
               });
            });


    </script>