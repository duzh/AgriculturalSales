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
                <div class="left f-fl">
                    我的余额<br />
                    <strong><?php echo isset($userData->value)&&$userData->value>0 ? ($userData->value) : '0.00'; ?></strong>元
                </div>
                <div class="btns f-fr clearfix">
                    <a class="cz f-fl" href="/member/account/recharge">充值</a>
                    <a class="tx f-fr" href="/member/account/put">提现</a>
                </div>
            </div>
        <form action="/member/account/searchopsdeposit" method="get">

            <div class="chaxun clearfix">
         
                <span>
                    <font>充值时间：</font>
                    <select name="searchType" id="searchType">
                        <option value="1" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '1'){ echo 'selected';}?>>一个月内</option>
                        <option value="2" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '2'){ echo 'selected';}?> >一个月至三个月</option>
                        <option value="3" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '3'){ echo 'selected';}?> >三个月至一年</option>
                        <option value="4" <?php if(isset($_GET['searchType'])&&($_GET['searchType']) == '4'){ echo 'selected';}?>>一年前</option>
                    </select>
                </span>
                <input class="cx_btn f-fr" type="submit" value="" />
            </div>
        </form>

        <div class="chaxun clearfix">
                <span>
                    <a href="/member/account/index"><font>交易记录</font></a>
                    <a href="/member/account/searchopsdeposit" class='active'><font>充值记录</font></a>
                    <a href="/member/account/searchopswithdraw"><font>提现记录</font></a>
                    
                </span>
            </div>

            <style>
            .my_banlance .chaxun a.active font{ color:#05780a;}
            .my_banlance .chaxun a{ margin-right:10px;}
            </style>
            

            <table cellpadding="0" cellspacing="0" width="100%">
                <tr height="28" class="title">
                    <th width="39">序号</th>
                    <th width="105">创建时间</th>
                    <th width="155">交易流水号</th>
                    <th width="112">交易类型</th>
                    <th width="114">金额/元</th>
                    <th width="122">状态</th>
                </tr>
                {% for key, val in data.payLogList %}
                <tr height="50">
                    <td>{{ key+start }}</td>
                    <td>{{val.createDate}}</td>
                    <td>{{ val.orderNum}}</td>
                    <td>{{ val.payType}}</td>

                    <td>{{ val.amount}}</td>
                    <td>{{ val.status ? '成功' : '操作中' }}</td>
                </tr>
                {% endfor %}

       
                
            </table>
            
            <!-- 分页 start -->
    <div class="page">
            {{ pages }}
  
        </div>
            <!-- 分页 end -->
            
        </div>
    </div>
    <!-- 右侧 end -->
</div>

<!-- 主体内容结束 -->
<style>
li em {line-height: 37px;}
</style>



<?php if(isset($isYnp) && $isYnp){ ?>

<!-- 弹框 start -->
<style>
.catchUser_layer{ width:100%; height:100%; background:#000; opacity:0.5; filter:alpha(opacity:50); position:fixed; z-index:100; left:0; top:0;}
.catchUser{ width:429px; height:319px; position:fixed; left:50%; top:50%; margin-top:-160px; margin-left:-215px; z-index:102;}
.catchUser .close_btn{ display:block; width:20px; height:20px; background:url(images/close_btn.png) center center no-repeat; position:absolute; right:10px; top:6px; text-indent:-10000em;}
</style>
<div class="catchUser_layer"></div>
<div class="catchUser">
    <a class="close_btn" href="/member/index">关闭</a>
    <div class="title"></div>
    <div class="catchTip">
        <p>您还没有绑定云农宝支付账户，请先去绑定！</p>
        <div class="btns clearfix">
            <a class="f-db f-fl" href="/member/bind/index">去绑定</a>
            <a class="f-db f-fr" href="/member/index">取消</a>
        </div>
    </div>
    <div class="why">
        <div class="s_title">为什么要绑定云农宝支付账户？</div>
        <p>1、绑定是对您填写信息的一种认证过程，未绑定云农宝支付账户是不能在丰收会正常使用，也不可以支付、 提现等等。为了使您能正常使用云农宝的所有功能，建议您尽快绑定您的账号。</p>
        <p>2、绑定遇到问题时？请联系客服，客服会帮你解决。</p>
    </div>
</div>
<?php } ?>

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


