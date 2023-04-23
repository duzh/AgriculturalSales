{{ partial('layouts/page_header') }}

<!-- 交易补贴添加的样式 -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/nav-page.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/personal-center.css">

    <!-- 头部 -->

    <div class="wrapper f-oh">
        <div class="subsidy w1185 mtauto mt10 f-oh">
            <div class="box">

                <div class="success f-tac">您的订单已经提交，请尽快支付!</div>
                <div class="order-num f-tac">订单号：{{order.order_sn}}</div>
                <div class="order-price f-tac">应付金额：<strong>¥ {{order_amount}}</strong></div>

                 <!-- {% if  usersubsidy["usersubsidy"]!=0  and usersubsidy["ordersubsidy"]!=0 %}
                 <form action="/ordersbuy/payorderpro" method="post" id="myform"  autocomplete="off">
                    {% if !subpay  %}
                <div class="useSub-form" id="use" >
                    <label class="f-db f-oh">
                        <input class="f-fl" type="checkbox" checked id="is_subsigy"/>
                        <font class="f-fl">使用补贴</font>
                    </label>
                    <div class="message">
                        <font>本次使用：</font>
                        <div class="msge-box">
                            <input data-target="#code-yz" class="txt" type="text" name="use_money" id="user_money"/>
                            <i>元</i>
                            <input class="codeBtn" type="button" id="getvcode" disabled="disabled"   value="获取验证码" />
                            <em class="f-db" id="code-yz"></em>
                        </div>
                    </div>
                    <div class="message">
                        <font>验证码：</font>
                        <div class="msge-box">
                            <input data-target="#vcode-yz" class="txt" type="text" name="vcode"  />
                            <input class="btn" type="submit" value="确认" />
                            <em class="f-db" id="vcode-yz"></em>
                        </div>
                    </div>
                </div>
                <div class="order-used-price f-tac" id="use_after">
                        
                </div>
                <div class="matter f-tac">
                    <span>您有补贴金额：<strong>{{usersubsidy["usersubsidy"]}}</strong>元</span>       
                    <span>本次最多可使用：<strong>{{usersubsidy["ordersubsidy"]}}</strong>元</span>
                    <input type="hidden" name="ordersubsidy" value="{{usersubsidy["ordersubsidy"]}}">
                </div>
                {% else %}

                    <div class="matter f-tac">
                        <span>您已使用补贴：<strong>{{ subpay.pay_amount }}</strong>元</span>       
                       
                    </div>
                {% endif %}
                {% endif %} -->
                <div class="border"></div>
                <div class="lave-balance">
                    <div class="lave-price" id="make_money" >还需支付金额：{{order_amount}}元</div>
                    <div class="lave-style f-oh">
                        <font class="f-db f-fl">请选择支付方式：</font>
                        <span class="f-db f-fl">
                            <a href="/member/ordersbuy/payorderpro?gate_id=1&order_id={{order.id}}"><img src="{{ constant('STATIC_URL') }}mdg/version2.4/images/subsidy/bank-img1.png"></a>
                            <a href="/member/ordersbuy/payorderpro?gate_id=2&order_id={{order.id}}"><img src="{{ constant('STATIC_URL') }}mdg/version2.4/images/subsidy/bank-img2.png"></a>
                        </span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 底部 -->
    {{ partial('layouts/footer') }}
    <script>
    var limit_time = times = 60;
    var _setInterval = null;
    jQuery(document).ready(function(){

        // $('#codemsg').hide();
        $('#myform').validator({
            rules: {
                nimei:[/^([0-9])+(\.([0-9])+)?$/, '请输入数字'],
                checkMax: function(element, param, field) {
                    switch(param[0]) {
                        case 'mq':
                        return parseFloat(element.value) <= parseFloat($('input[name="'+param[1]+'"]').val()) || '请正确填写金额';
                    }
                }
            },
            fields: {
                'use_money': 'required;nimei;checkMax[mq, ordersubsidy]',
                'vcode':'required;remote[/member/register/checksubsidycode];'
            },
            valid:function(form){
                var is_subsigy=$("#is_subsigy").val();
                var money=$("#user_money").val();
                var order_id={{order.id}};
                var order_sn="{{order.order_sn}}";
       
                if(money>0&&$('#is_subsigy').is(":checked")){
                        $.ajax({
                            url: '/member/ordersbuy/subsidy',
                            type: 'post',
                            dataType:"json",
                            async:false,
                            data: {user_money:money,order_id:order_id,order_sn:order_sn},
                            success:function (e) {
                                if(e.state==1){
                                    $("#make_money").html("还需支付金额："+e.total+"元");
                                    $("#use").hide();
                                    $("#use_after").html("本次使用补贴金额<strong>"+money+"</strong>元");

                                }else{
                                    alert(e.msg);
                                }
                            } 
                        })
                 }else{
                    alert("请选择使用补贴");
                 }
                   
            }
        });

        $('input[name="use_money"]').on('valid.field', function(e, result, me){
            $('#getvcode').removeAttr('disabled');
        });

       

        $('#getvcode').click(function(){

            $("#getvcode").attr('disabled','disabled');
            if(_setInterval!=null) return;
            var mo = $('input[name="mobile"]').val();
            $.getJSON('/member/register/getsubsidycode', {mobile:mo},function(data) {
                if(data.ok=="1"){
                    
                    _setInterval = setInterval('clock()',1000);
                }
            });
        });
        //4000866073

    });

    function clock(){
        times--;
        if(times==0){
            clearInterval(_setInterval);
            _setInterval = null;
            $('#getvcode').removeAttr('disabled');
            times = limit_time;
            $('#getvcode').val("获取验证码");
            return;
        }
        var tpl = "秒后重新发送";
        $('#getvcode').val(times+tpl);
    }
    </script>

    <style>
    .n-right .msg-wrap{ margin:0; margin-top:-3px;}
    </style>
</body>
</html>