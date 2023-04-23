<div class="box clearfix">
    <div class="noFloat f-fl"> <i>手机号：</i>
        {{ mobile }}
    </div>
    <div class="message clearfix"> <font>名称：</font>
        <div class="inputVal f-pr">
            <input type="text"  name='realname' data-rule="required;" id='edit_realname_{{ mobile }}'/>
        </div>
    </div>
</div>
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
            <select name='edit_bank_name' id='edit_input_bank_name_{{ mobile }}' data-rule="required;" >
                {% for key, val in bankList %}
                <option value='{{ val['gate_id'] }}'>{{ val['bank_name'] }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="message clearfix">
        <font>开户名：</font>
        <div class="inputVal f-pr">
            <input type="text" name='bank_account' data-rule="required;" id='edit_input_bank_account_{{ mobile }}' />
        </div>
    </div>
</div>
<div class="box clearfix">
    <div class="message clearfix">
        <font>银行卡号：</font>
        <div class="inputVal f-pr">
            <input type="text" name='bank_card' data-rule="required;mark;" id='edit_input_bank_card_{{ mobile }}'  />
        </div>
    </div>
    <div class="message clearfix">
        <font>银行所在地：</font>
        <div class="inputVal f-pr">
            <input type="text" name='bank_address' data-rule="required;" id='edit_input_bank_address_{{ mobile }}'  />
        </div>
    </div>
</div>
<div class="box clearfix">
    <div class="message clearfix">
        <font>采购数量：</font>
        <div class="shortVal f-pr">
            <input type="text" name='edit_number' data-rule="required;sl;" data-rule-sl="[/^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+))$/, '请输入正确的数量']" data-target="#editgoodsnumberTip" id='edit_input_number_{{ mobile }}' onblur="add_goods_numbers(this)" /> 
            <i class="unit">公斤</i>
            <i id='editgoodsnumberTip' style="margin-top:7px;"></i>
        </div>
    </div>
</div>
<div class="s-line"></div>
<div class="addPrice f-tar">
    <span>
        采购金额：
        <i id='edit_input_goods_number_{{ mobile }}' class='goodsAmount'>0</i>
        元
    </span>
</div>
<div class="btn-box f-fr clearfix">
    <label class="f-db clearfix">
        <input type="checkbox"  id='edit_input_user_partner_{{ mobile }}' />
        <font>同时更新商友信息</font>
    </label>
    <input class="btn1" type="button" value="确定"  onclick="editSellerCon(this)"/>
    <input class="btn2" type="button" value="取消" onclick="clearSeller(this)"/>
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

        var mobile = {{ mobile }};
        var bank_name    = $('input[name="bank_name['+mobile+']"]').val();
        var name         = $('input[name="realname['+mobile+']"]').val();
        var number       = $('input[name="goods_number['+mobile+']"]').val();
        var bank_account = $('input[name="bank_account['+mobile+']"]').val();
        var bank_card    = $('input[name="bank_card['+mobile+']"]').val();
        var bank_address = $('input[name="bank_address['+mobile+']"]').val();
        var user_partner = $('input[name="user_partner['+mobile+']"]').val();
 

        var goods_price = $('#goods_price').val();
        var amount = parseFloat(number * goods_price);
        var number = number ? number :0; 
        $('#edit_input_bank_account_' + mobile ).val(bank_account);

        $('#edit_input_number_' + mobile ).val(number);
        $('#edit_input_bank_address_' + mobile ).val(bank_address);
        $('#edit_input_bank_card_' + mobile ).val(bank_card);
        $('#edit_realname_' + mobile ).val(name);
        $("#edit_input_bank_name_" + mobile ).val(bank_name);
        $('#edit_input_goods_number_' + mobile ).text(amount.toFixed(2));
        user_partner = user_partner == 'true' ? true : false;
        $('#edit_input_user_partner_' + mobile ).attr('checked', user_partner);
    })

        /**
         * 添加卖家
         */
        function editSellerCon(obj){

            
            $('.sellerEditBox').hide();
            $('.esc-addSeller').hide();
            var mobile           = '{{ mobile }}';

            if($('#buy_mobile').val() == mobile) {
                alert('请勿添加自己为卖家');
                return false;
            }
            
            var name             =  $('#edit_realname_' + mobile ).val();
            var add_bank_name    = $('#edit_input_bank_name_' + mobile ).val();
            var add_bank_text    = $('#edit_input_bank_name_' + mobile ).find("option:selected").text();
            var add_bank_account = $('#edit_input_bank_account_' + mobile ).val();
            var add_bank_card    = $('#edit_input_bank_card_' + mobile ).val();
            var add_bank_address = $('#edit_input_bank_address_' + mobile ).val();
            var add_goods_number = $('#edit_input_number_' + mobile ).val();
            var largeBox         = $('<div class="largeBox" id="largeBox_'+ mobile +'">');
            var goods_price = $('#goods_price').val();
            var add_user_partner = $('#edit_input_user_partner_' + mobile ).prop('checked');
            var amount = parseFloat(add_goods_number * goods_price);
            var amount = amount.toFixed(2);
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

            var largeBox_html = '<div class="sellerInfo" id="sellerInfo_'+mobile+'"><table cellpadding="0" cellspacing="0" width="632"><tbody><tr height="30"><td width="165"><font>手机号：</font>' + mobile + '<input type="hidden" name="mobile['+ mobile +']"  value="'+mobile+'" class="userName" /></td><td width="172"><font>名称：</font>'+ name +'<input type="hidden" name="realname['+ mobile +']" value="'+name+'" /></td><td width="143"><font>采购数量：</font>' + add_goods_number +'<input type="hidden" name="goods_number['+ mobile +']" value="'+add_goods_number+'" class="goods_sum_number" /><b class="unit f-fwn">公斤</b></td><td width="152"><font>采购金额：</font> <i class="cg-price"> ' + amount + '</i>元<input type="hidden" name="goods_amount['+ mobile +']" value="'+amount+'" /></td></tr><tr height="30"><td><font>收款方式：</font>银行支付</td><td><font>收款银行：</font>'+ add_bank_text +'<input type="hidden" name="bank_name['+ mobile +']" value="'+add_bank_name+'"  /></td><td><font>开户名：</font>' + add_bank_account + '<input type="hidden" name="bank_account['+ mobile +']" value="'+add_bank_account+'" /></td><td><font>卡号：</font>'+ add_bank_card +'<input type="hidden" name="bank_card['+ mobile +']"  value="'+add_bank_card+'"/></td></tr><tr height="30"><td colspan="4"><font>所在地：</font>'+ add_bank_address +'<input type="hidden" name="bank_address['+ mobile +']" value="'+add_bank_address +'" /><input type="hidden" name="user_partner['+ mobile +']" value="'+add_user_partner +'" /></td></tr></tbody></table><div class="btns"><a class="btn1" onclick="editSeller(this, '+ mobile+')" href="javascript:;">修改</a><a class="btn2" onclick="removeSellerOne(this)" href="javascript:;">删除</a></div></tbody></table></div><div class="sellerEditBox f-oh sellerEditBox_'+ mobile +'" ><div id="editShowDiv_'+mobile+'"></div></div>';
            
            $('#largeBox_' + mobile ).remove();

            largeBox.html(largeBox_html);
            $(largeBox).insertBefore('.addBtn');
            
            numPrice();
        }

        
</script>
<style>
    /*.msg-box{ display: block; margin:0; padding:0;}
    .msg-box .n-error{ margin:0; padding:0;}
    .n-default .n-left, .n-default .n-right{ margin-top:0;}
    .msg-box .n-ok{ position: absolute; right:-18px; top:-33px;}*/
    /*.esc-addSeller .tip{ margin-top:15px;}
    .esc-addSeller .box{ margin-top:15px;}*/
</style>