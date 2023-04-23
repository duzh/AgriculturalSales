<form action="/member/entrustorder/orderSubsidy" id='myform' method="post" >
    <div class="useSub-form" id="use" >
        <label class="f-db f-oh">
            <input class="f-fl" type="checkbox" checked id="is_subsigy"  /> <font class="f-fl">使用补贴</font>
        </label>
        <div id='showDiv'>
        <div class="message"> <font>本次使用：</font>
            <div class="msge-box">
                <input data-target="#code-yz" class="txt" type="text" name="use_money"  id="user_money"/> <i>元</i>
                <input class="codeBtn" type="button" id="getvcode" disabled="disabled"   value="获取验证码" /> <em class="f-db" id="code-yz"></em>
            </div>
        </div>
        <div class="message">
            <font>验证码：</font>
            <div class="msge-box">
                <input data-target="#vcode-yz" class="txt" type="text" name="vcode"  />
                <input class="btn" type="submit" value="确认" /> <em class="f-db" id="vcode-yz"></em>
            </div>
        </div>
    
    <div class="order-used-price f-tac" id="use_after"></div>
    <div class="matter f-tac">
        <span>
            您有补贴金额： <strong>{{subsidy["usersubsidy"]}}</strong>
            元
        </span>
        <span>
            本次最多可使用： <strong>{{subsidy["ordersubsidy"]}}</strong>
            元
        </span>
        <input type="hidden" name="ordersubsidy" value="{{subsidy["ordersubsidy"]}}"></div>
        <input type="hidden" name='order_sn' value='{{ order.order_sn }}'>
        <input type="hidden" name='mobile' value='{{ order.buy_user_phone }}'>

    </form>
</form>
</div>

</div>


<script type="text/javascript">
 var limit_time = times = 60;
    var _setInterval = null;
    
  $(function () {

    $(':checkbox').click(function(event) {

        if(!$(this).prop('checked')) {
            $('#showDiv').hide();
        }else{
            $('#showDiv').show();
        }
    });

    $('#myform').validator({
        rules: {
                nimei:[/^[0-9]+(.[0-9]{1,2})?$/, '请输入数字'],
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
                    var order_sn="{{order.order_sn}}";
                    if(money>0&&$('#is_subsigy').is(":checked")){
                        $.ajax({
                            url: '/member/entrustorder/orderSubsidy',
                            dataType: 'post',
                            dataType:"json",
                            async:false,
                            data: {order_sn:order_sn, money : money , is_subsigy : $("#is_subsigy").prop('checked') },
                            success:function(e) {
                                if(e.ok == 1 ) {
                                    location.reload();
                                }
                            }
                        })
                        
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


  })

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