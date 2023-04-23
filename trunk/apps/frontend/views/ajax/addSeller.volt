<div class="box clearfix">
    <div class="message clearfix"> <font>卖家手机号：</font>
        <div class="inputVal f-pr">
            <input type="text" class='add_partner_phone' name='add_partner_phone' value='' id='add_partner_phone' data-rule="required;mobile;remote[/ajax/getUserInfo]"  onblur="cSelectName(this.value);"/>
        </div>
    </div>
    <div class="message clearfix"> <font>卖家名称：</font>
        <div class="inputVal f-pr">
            <input type="text"  name='add_partner_name' id='add_partner_name' value='' data-rule="required;" />
        </div>
    </div>
</div>
<div class="tip messageTip " >温馨提示：由于卖家手机号还未在丰收汇注册，在提交订单后，我们会自动帮其注册一个账户，并将密码发送到该注册手机.</div>
<div class="radioBox clearfix">

    <label class="f-db clearfix">
        <input type="radio" checked />
        <font>结算到银行</font>
    </label>
</div>
<div class="box clearfix">
    <div class="message clearfix">
        <font>结算银行：</font>
        <div class="selectVal f-pr">
            <select name='add_bank_name' data-rule="required;" >
                {% for key, val in bankList %}
                <option value='{{ val['gate_id'] }}'>{{ val['bank_name'] }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="message clearfix">
        <font>开户名：</font>
        <div class="inputVal f-pr">
            <input type="text" name='add_bank_account'   data-rule="required;" />
        </div>
    </div>
</div>
<div class="box clearfix">
    <div class="message clearfix">
        <font>银行卡号：</font>
        <div class="inputVal f-pr">
            <input type="text"  name='add_bank_card' value='' data-rule="required;mark" />
        </div>
    </div>
    <div class="message clearfix">
        <font>银行所在地：</font>
        <div class="inputVal f-pr">
            <input type="text"  name='add_bank_address' value='' data-rule="required;" />
        </div>
    </div>
</div>
<div class="box clearfix">
    <div class="message clearfix">
        <font>采购数量：</font>
        <div class="shortVal f-pr">
            <input type="text" name='add_goods_number' id='add_goods_number' value='' class='goods_number' data-rule="required;xxx;" data-rule-xxx="[/^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+))$/, '请输入正确的数量']"  onblur="add_goods_numbers(this)" data-target="#addgoodsnumberTip"/> 
            <i class="unit">公斤</i>
            <i id='addgoodsnumberTip' style="margin-top:7px;"></i>
        </div>
    </div>
</div>
<div class="addPrice f-tar">
    <span>
        采购金额： <i class='goodsAmount'>0</i>
        元
    </span>
</div>
<div class="btn-box f-fr clearfix">
    <label class="f-db clearfix">
        <input type="checkbox" checked name='add_user_partner'  />
        <font>同时添加为我的商友</font>
    </label>
    <input onclick="removeSeller()" class="btn1" type="button" value="取消" />
    <input onclick="addSellerCon()" class="btn2" type="button" value="添加" />
</div>

<script type="text/javascript">
$(function () {
    // 设置单位
        var $arr = [];
        var $unit = '';
        var $value = $.trim($('select[name="goods_unit"]').find("option:selected").text());
        $arr = $value.split('／');
        $unit = $arr[1];
        $('.esc-addConBox .unit').text($unit);
        $('.esc-chooseFriend .unit').text($unit);
        
});
        /**
         * 添加卖家
         */
        function addSellerCon(){
            
            var mobile = $('input[name="add_partner_phone"]').val();
            if(!repeatMobile(mobile)) {
                alert('请勿重复添加卖家');
                return false;
            }

            if($('#buy_mobile').val() == mobile) {
                alert('请勿添加自己为卖家');
                return false;
            }

            var name             = $('input[name="add_partner_name"]').val();
            var add_bank_name    = $('select[name="add_bank_name"]').val();
            var add_bank_text    = $('select[name="add_bank_name"]').find("option:selected").text();
            var add_bank_account = $('input[name="add_bank_account"]').val();
            var add_bank_card    = $('input[name="add_bank_card"]').val();
            var add_bank_address = $('input[name="add_bank_address"]').val();
            var add_goods_number = $('input[name="add_goods_number"]').val();
            var largeBox         = $('<div class="largeBox" id="largeBox_'+ mobile +'" >');
            var goods_price = $('#goods_price').val();
            var add_user_partner = $('input[name="add_user_partner"]').prop('checked');

            if(!name || !add_bank_name  || !add_bank_text || !add_bank_account || !add_bank_card || !add_bank_address || !add_goods_number || !goods_price ) {
                alert('请检查输入信息');
                return false;
            }

            /* 检测身份证 */
            var reg = /^(\d{18}|\d{16}|\d{17}|\d{19})$/;
            var r = add_bank_card.match(reg); 
            if(r == null )    {
                alert('请检查输入信息');
                return false;
            }
            /* 检测数量 */
            var reg =/^[-+]?\d*$/;
            var r = add_goods_number.match(reg); 
            if(r == null )    {
                alert('请检查输入信息');
                return false;
            }
            
            $.ajax({
                url: '/ajax/checkUserPartner',
                type: 'POST',
                dataType: 'JSON',
                async:false,
                data: {add_partner_phone: mobile, send : '1'},
                success:function (e) {
                    if(e.state > 0 ) {
                        return false;
                    }
                }
            })

            $('.esc-addSeller').hide();
            var amount = parseFloat(add_goods_number * goods_price);
            var amount = amount.toFixed(2);
            var largeBox_html = '<div class="sellerInfo" id="sellerInfo_'+mobile+'"><table cellpadding="0" cellspacing="0"><tbody><tr height="30"><td width="165"><font>手机号：</font>' + mobile + '<input type="hidden" name="mobile['+ mobile +']"  value="'+mobile+'" class="userName"/></td><td width="172"><font>名称：</font>'+ name +'<input type="hidden" name="realname['+ mobile +']" value="'+name+'" /></td><td width="143"><font>采购数量：</font><b class="cg-num f-fwn">' + add_goods_number +'</b><input type="hidden" name="goods_number['+ mobile +']" class="goods_sum_number" value="'+add_goods_number+'" /><b class="unit f-fwn">' + $unit +'</b></td><td width="170"><font>采购金额：</font> <i class="cg-price"> ' + amount + '</i>元<input type="hidden" name="goods_amount['+ mobile +']" value="'+amount+'" /></td></tr><tr height="30"><td><font>收款方式：</font>银行支付</td><td><font>收款银行：</font>'+ add_bank_text +'<input type="hidden" name="bank_name['+ mobile +']" value="'+add_bank_name+'"  /></td><td><font>开户名：</font>' + add_bank_account + '<input type="hidden" name="bank_account['+ mobile +']" value="'+add_bank_account+'" /></td><td><font>卡号：</font>'+ add_bank_card +'<input type="hidden" name="bank_card['+ mobile +']"  value="'+add_bank_card+'"/></td></tr><tr height="30"><td colspan="4"><font>所在地：</font>'+ add_bank_address +'<input type="hidden" name="bank_address['+ mobile +']" value="'+add_bank_address +'" /><input type="hidden" name="user_partner['+ mobile +']" value="'+add_user_partner +'" /></td></tr></tbody></table><div class="btns"><a class="btn1" onclick="editSeller(this, '+ mobile+')" href="javascript:;">修改</a><a class="btn2" onclick="removeSellerOne(this)" href="javascript:;">删除</a></div></tbody></table></div><div class="sellerEditBox f-oh sellerEditBox_'+ mobile +'" ><div id="editShowDiv_'+mobile+'"></div></div>';

            largeBox.html(largeBox_html);
            $(largeBox).insertBefore('.addBtn');
            $('#submitButton').removeAttr('disabled');
            
            $('.messageTip').hide();

            numPrice();

        }
 
</script>

<script type="text/javascript">
function cSelectName(mobile) {
  
    /* 获取银行卡信息 以及姓名信息 */
    $.ajax({
        url: '/ajax/checkUserPartner',
        type: 'POST',
        dataType: 'json',
        data: {add_partner_phone: mobile },
        async:false,
        success:function (e) {
            if(e.uname) {
                $('#add_partner_name').val(e.uname);
                $('.messageTip').hide();
            }

        }
    })

}
$('.add_partner_phone').on('valid.field', function(e, result, me){
    $('.messageTip').hide();
    var mobile  = $(this).val();
    /* 获取银行卡信息 以及姓名信息 */
    $.ajax({
        url: '/ajax/checkUserPartner',
        type: 'POST',
        dataType: 'json',
        data: {add_partner_phone: mobile },
        async:false,
        success:function (e) {
            if(e.uname) {
                $('#add_partner_name').val(e.uname);
                // $('#add_partner_name').append('<span class="msg-box n-right" style="" for="add_partner_name"><span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span></span>')
            }

        }
    })
    
    
});

</script>
<style>
    /*.msg-box, .n-right{ display: block; margin:0; padding:0;}
    .msg-box .n-error{ margin:0; padding:0;}
    .n-default .n-left, .n-default .n-right{ margin-top:0;}
    .msg-box .n-ok{ position: absolute; right:-18px; top:-33px;}*/
    .esc-addSeller .tip{ margin-top:15px;}
    .esc-addSeller .box{ margin-top:15px;}
</style>