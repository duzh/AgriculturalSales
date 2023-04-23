{{ partial('layouts/page_header') }}
<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}个人信息</span>
</div>
<div class="personal_center w960 mb20">
    {{ partial('layouts/navs_left') }}
    <!-- 右侧 start -->
    <!-- 右侧 start -->
    <div class="center_right f-fr">
        <div class="my_banlance">

            <div class="money clearfix">
                <div class="left f-fl mr50">
                    我的余额
                    <br /> <strong><?php echo isset($userData[0])&&$userData[0]>0 ? ($userData[0]) : '0.00'; ?></strong>
                    元
                    
                </div>
                <?php if(isset($userData[1]) && $userData[1] > 0 ) {?>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <div class="left f-fl">
                    冻结金额
                    <br /> <strong><?php echo isset($userData[1])&&$userData[1]>0 ? ($userData[1]) : '0.00'; ?></strong>
                    元
                    
                </div>
                <?php } ?>
                <div class="btns f-fr clearfix">
                    <a class="cz f-fl" href="/member/account/recharge" target="_blank">充值</a>
                    <!-- <a class="tx f-fr" href="/member/account/put">提现</a> -->
                </div>
            </div>

            <form action="/member/account/index" method="get">
                <div class="chaxun clearfix">
                    <span> <font>交易时间：</font>
                        <select name="searchType" id="searchType">
                            <option value="1" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '1'){ echo 'selected';}?>>一个月内</option>
                            <option value="2" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '2'){ echo 'selected';}?>>一个月至三个月</option>
                            <option value="3" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '3'){ echo 'selected';}?>>三个月至一年</option>
                            <option value="4" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '4'){ echo 'selected';}?>>一年前</option>
                        </select>
                    </span>
                    <span> <font>交易类型：</font>
                        <select name="orderStatus" id="orderStatus">
                            <option value="5" <?php if(isset($_GET['orderStatus']) &&$_GET['orderStatus']== '5'){ echo 'selected';}?>>所有状态</option>
                            <option value="0" <?php if(isset($_GET['orderStatus'])&&($_GET['orderStatus']) == '0'){ echo 'selected';}?>>等待支付</option>
                            <option value="1" <?php if(isset($_GET['orderStatus'])&&intval($_GET['orderStatus']) == '1'){ echo 'selected';}?>>支付成功</option>
                            <option value="3" <?php if(isset($_GET['orderStatus'])&&intval($_GET['orderStatus']) == '3'){ echo 'selected';}?>>交易完成</option>
                        </select>
                    </span>
                    <div style="clear:both; height:10px;"></div>
                    <span class="mt10">
                        <font>&nbsp;&nbsp;&nbsp;流水号：</font>
                        <input class='txt' name="serialNum" id="serialNum" type="text" value="<?php if(isset($_GET['serialNum'])){ echo trim($_GET['serialNum']); }?>">
                    </span>
                    
                    <span>
                        <font>&nbsp;&nbsp;&nbsp;&nbsp;订单号：</font>
                        <input class='txt' name="orderNum" id="orderNum" type="text" value="<?php if(isset($_GET['orderNum'])){ echo trim($_GET['orderNum']); }?>">
                    </span>

                    <span>
                    <div style="clear:both; height:10px;"></div>
                    <input style="margin-left:72px;" class="cx_btn" type="submit" value='' />
                    </span>
                    
                </div>

            </form>
                <div style="text-align:right; background:#fff;">
                    <a href="{{ constant("YNP_URL")}}" target="_blank" ><img src="/mdg/images/ynp_logo.png" alt="我的云农宝" width='150' height='50'  ></a>
                    
                </div>

            <style>
            .my_banlance .chaxun a.active font{ color:#05780a;}
            .my_banlance .chaxun a{ margin-right:10px;}
            </style>
            <div class="chaxun clearfix">
                <span>
                    <a href="/member/account/index" class='active'>
                        <font>交易记录</font>
                    </a>
                    <a href="/member/account/searchopstransactionbyselleruserphone">
                        <font>收款记录</font>
                    </a><!-- 
                    <a href="/member/account/searchopsdeposit">
                        <font>充值记录</font>
                    </a> -->
                    <!-- <a href="/member/account/searchopswithdraw">
                        <font>提现记录</font>
                    </a> -->

                </span>
            </div>

            <table cellpadding="0" cellspacing="0" width="100%" class="f-fs12">
                <tr height="28" class="title">
                    <th width="39">序号</th>
                    <th width="112">交易类型</th>
                    <th width="141">创建时间</th>
                    <th width="155">交易流水号</th>
                    <th width="155">订单号</th>
                    <th width="100">金额/元</th>
                    <th width="100">状态</th>
                </tr>
                <?php if(isset($data->payLogList)) { ?>
                {% for key, val in data.payLogList %}
                <tr height="50">
                    
                    <td>{{ key + 1 + start }}</td>
                    <td>{{ val.payType}}</td>
                    <td>{{ val.createDate}}</td>
                    <td>{{ val.serialNum}}</td>
                    <td>{{ val.orderNum}}</td>
                    <td>{{ val.amount}}</td>
                    <td><?php echo isset($_status[$val->status]) ? $_status[$val->status] : ''; ?></td>
                </tr>
                {% endfor %}
                <?php } ?>
            </table>

            <!-- 块 start -->
        <div class="page">
            {{ pages }}
  
        </div>
        </div>
</div>
<!-- 右侧 end -->
</div>

<!-- 主体内容结束 -->
<style>li em {line-height: 37px;}</style>







<script>

function selUserType(val) {
    switch(val) {
        case '1' :
        $('#mytype').html('我要卖：');
        $('#myfarm').show();
        break;
        case '2' :
        $('#mytype').html('我要买：');
        $('#myfarm').hide();
        break;
    }
}

$(function(){
    selUserType('{{ users.usertype }}');
})
</script>
{{ partial('layouts/footer') }}