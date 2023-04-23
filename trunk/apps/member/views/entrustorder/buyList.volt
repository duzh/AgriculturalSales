{{ partial('layouts/member_header') }}
<div class="center-wrapper pb30">
    <div class="bread-crumbs w1185 mtauto">
        <span>{{ partial('layouts/ur_here') }}委托买入订单</span>
    </div>
    <div class="w1185 mtauto clearfix">
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">
            <form action="/member/entrustorder/buyList">
            <div class="esc-bigList f-oh">
                <div class="title">
                    <span>委托交易</span>
                </div>
                <div class="esc-bigSearch">
                    <div class="box"> <em><font>商品名称：</font>
                            <input class="input1" type="text" name='goods_name' value='{{ goods_name }}' ></em> <em><font>采购类别：</font>
                            <select name="maxcategory" class="selectCate select1">
                                <option value="">选择分类</option>
                            </select>
                            <select name="category" class="selectCate select1">
                                <option value="">选择分类</option>
                            </select>
                        </em> 
                    </div>
                    <div class="box">
                        <em>
                            <font>商品单价：</font>
                            <input class="input2" type="text" name='minprice'  value='{{ minprice }}' >
                            <font>—</font>
                            <input class="input2" type="text" name='maxprice' value='{{ maxprice }}'>
                            <font>元</font></em> 
                        <em>
                            <font>采购时间：</font>
                            <input id="d4311" class="Wdate input2" type="text" value='{{ stime }}' name="stime"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})"/>
                            <font>—</font>
                        <input id="d4312" class="Wdate input2" type="text" value='{{ etime }}' name="etime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})"/>

                            </em>
                        <em>
                            <font>订单状态：</font>
                            <select name="state" class='select2'>
                                        <option value="0">全部状态</option>
                                        {% for key, val in _order_status %}
                                        <option value="{{ key }}" {% if (state == key) %} selected="selected" {% endif %} >{{ val }}</option>
                                        {% endfor %}
                                    </select>
                        </em>
                    </div>
                    <div class="btnBox">
                        <input type="submit" value="筛  选" />
                    </div>
                </div>
                </form>
                <table cellpadding="0" cellspacing="0" width="100%" class="list">
                    <tr height="40">
                        <th width="13%">订单号</th>
                        <th width="13%">商品名称</th>
                        <th width="10%">采购类别</th>
                        <th width="11%">采购时间</th>
                        <th width="11%">商品单价</th>
                        
                        <th width="11%">采购总金额</th>
                        <th width="10%">卖家数量</th>
                        <th width="10%">订单状态</th>
                        <th width="11%">操作</th>
                    </tr>
                </table>
                <!-- 列表 -->
                {% for key , order in data['items']%}
                <div class="esc-tableList">
                    <div class="box clearfix">
                        <span class="w1">{{ order.order_sn }}</span>
                        <span class="w2">{{ order.goods_name}}</span>
                        <span class="w3">{{ order.category }}</span>
                        <span class="w4">{{ date('Y-m-d' , order.add_time )}}</span>
                        <span class="w5"> <i>{{ order.goods_price }}</i>
                            元／<?php echo isset($_goods_unit[$order->goods_unit]) ? $_goods_unit[$order->goods_unit] : '';?>
                        </span>
                        <span class="w6"> <i>{{ order.goods_amount }}</i>
                            元
                        </span>
                        
                        <span class="w7">{{ order.sell_user_count }}家</span>
                        <span class="w8">{{ order.orderstatus }}</span>
                        <span class="w9">
                            <a href="/member/entrustorder/info/{{ order.order_id }}" target="_blank">详情</a>
                            {% if order.order_status == 0 %}
                            <a href="/member/entrustorder/info/{{ order.order_id }}">付款</a>
                            {% endif %}
                        </span>
                    </div>
                </div>
                {% endfor %}
                {% if data['total_count']>1 %}
                <form action="/member/entrustorder/buyList" method="get">
                <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                    {{ data['pages']}}

                    <span>
                        <label>去</label>
                        <input type="text" name='p' id="p" value='1'>
                        <label>页</label>
                    </span>
                    <input class="btn" type="submit" value="确定" onclick="go()"></div>

            </div>
            </form>
            {% endif %}
        </div>
    </div>
</div>
{{ partial('layouts/footer') }}
<script type="text/javascript">
function go(){
     var p=$("#p").val();
 var count = {{data['total_count']}};
 if(p>count){
    $("#p").val(count);
 }
}
    $(function () {
           $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
                defaultParentId : 0,
                {% if cate %}
                  texts : ["{{ cate }}"],
                {% endif %}
                style : {"width" : 140}
               });
            });


    </script>