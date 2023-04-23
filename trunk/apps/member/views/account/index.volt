<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span><a href="/">首页</a>&nbsp;&gt;&nbsp;<a href="/member">个人中心 </a>&nbsp;&gt;&nbsp;我的余额</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">

                <div class="my-balance f-pr f-oh">
                    <div class="m-title">我的余额</div>
                    <div class="price">
                        <i><?php echo isset($userData[0])&&$userData[0]>0 ? ($userData[0]) : '0.00'; ?></i>元
                    </div>
                    <div class="btns">
                        <!-- <a href="/member/account/recharge">充值</a> -->
                        <a href="/member/account/put">提现</a>
                    </div>
                </div>
                 <form action="/member/account/index" method="get">
                <div class="my-balance-list f-oh">
                    <div class="m-box f-oh">
                        <div class="message clearfix">
                            <font>交易时间：</font>
                            <select name="searchType" id="searchType">
                            <option value="1" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '1'){ echo 'selected';}?>>一个月内</option>
                            <option value="2" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '2'){ echo 'selected';}?>>一个月至三个月</option>
                            <option value="3" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '3'){ echo 'selected';}?>>三个月至一年</option>
                            <option value="4" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '4'){ echo 'selected';}?>>一年前</option>
                        </select>
                        </div>
                        <div class="message clearfix">
                            <font>状态：</font>
                            <select name="orderStatus" id="orderStatus">
                                <option value="5" <?php if(isset($_GET['orderStatus']) &&$_GET['orderStatus']== '5'){ echo 'selected';}?>>所有状态</option>
                                <option value="0" <?php if(isset($_GET['orderStatus'])&&($_GET['orderStatus']) == '0'){ echo 'selected';}?>>等待支付</option>
                                <option value="1" <?php if(isset($_GET['orderStatus'])&&intval($_GET['orderStatus']) == '1'){ echo 'selected';}?>>支付成功</option>
                                <option value="3" <?php if(isset($_GET['orderStatus'])&&intval($_GET['orderStatus']) == '3'){ echo 'selected';}?>>交易完成</option>
                            </select>
                        </div>
                        <div class="message clearfix">
                            <font>流水号：</font>
                            <input name="serialNum" id="serialNum" type="text" value="<?php if(isset($_GET['serialNum'])){ echo trim($_GET['serialNum']); }?>"/>
                        </div>
                        <input class="btn" type="submit" value="查  询" />
                        </form>
                    </div>
                    <div class="m-line"></div>
                    <ul class="my-balance-nav">
                        <li class="active"><a href="/member/account/index">支付记录</a></li>
                        <li class="border"><a href="/member/account/searchopstransactionbyselleruserphone">收款记录</a></li>
                    </ul>
                    
                    <div class="tabBox" style="display:block;">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr height="30">
                                <th width="98">
                                    <span class="m-left">序号</span>
                                </th>
                                <th width="104">交易类型</th>
                                <th width="121">创建时间</th>
                                <th width="200">交易流水号</th>
                                <th width="160">订单号</th>
                                <th width="125">金额／元</th>
                                <th width="120">
                                    <span class="m-right">状态</span>
                                </th>
                            </tr>
                        <?php if(isset($data->payLogList)) { ?>
                         {% for key, val in data.payLogList %}
                            <tr height="78">
                                <td>
                                    <span class="m-left">{{ key + 1 + start }}</span>
                                </td>
                                <td align="center">消费</td>
                                <td align="center">
                                    <span class="m-middle">
                                        {{ val.createDate}}
                                    </span>
                                </td>
                                <td align="center">{{val.serialNum}}</td>
                                <td align="center">{{val.orderNum}}</td>
                                <td align="center">
                                    <i>{{ val.amount}}</i>
                                </td>
                                <td>
                                    <span class="m-right"><?php echo isset($_status[$val->status]) ? $_status[$val->status] : ''; ?></span>
                                </td>
                            </tr>
                        {% endfor %}
                        <?php } ?>
                        </table>
                        <!-- 分页 -->
                        {% if total_count>1 %}
                        <div class="esc-page mt30 mb30 f-tac f-fr mr30">
                             {{ pages }}
                                <span>
                                <form action="/member/account/index" method="get">
                                    <label>去</label>
                                    <input type="text" name="p" id="p" onkeyup="value=value.replace(/[^\d]/g,'') " value="1" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
                                    <label>页</label>
                                </span>    
                                <input class="btn" type="submit" value="确定" onclick="go()"/>
                                </form>
                        </div>
                        {% endif %}
                    </div>
                </div>
            </div>
        <!--右侧end-->
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}

<script>
 function go(){
var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    $("#p").val(count);
 }
}      

</script>