<div class='down'>
    <div class="box clearfix">
        <div class="message clearfix f-fl"> <font>手机号：</font>
            <div class="inputVal">
                <input type="text" name='selectbymobile' id='mobile'  value='{{add_mobile}}'/>
            </div>
        </div>
        <div class="message clearfix f-fl"> <font>付款方式：</font>
            <div class="inputVal">
                <select name='pay_type' id='paytype'>
                    <option value="0">请选择</option>
                    <option value="1" {% if add_paytype == 1 %} selected{% endif %}>银行支付</option>
                </select>
            </div>
        </div>
        <div style="clear:both;"></div>
        <input class="ss-btn" type="button" value="搜索" onclick="javascript:postPages()" />
    </div>
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr height="38">
            <th width="20%">名称</th>
            <th width="20%">手机号</th>
            <th width="20%">收款方式</th>
            <th width="20%">采购量</th>
            <th width="20%">采购金额</th>
        </tr>
        {% for key , item in UserPartner['items'] %}
        <tr height="38">
            <td align="center">
                <label class="f-db clearfix">
                    <input class="check-btn f-fl usernameCheckbox" type="checkbox" name='uid[{{ item.partner_phone}}]' value='{{ item.partner_phone}}' >
                    <font class="f-fl">{{ item.partner_name }}</font>
                </label>
            </td>
            <td align="center">{{ item.partner_phone }}</td>
            <td align="center">{{ item.paytype }}</td>
            <td align="center">
                <input class="txt goods_number" id='goods_numbers[{{ item.partner_phone}}]' data-url="{{ item.partner_phone }}" type="text" name='goods_numbers[{{ item.partner_phone}}]'> <em class="unit">公斤</em>
            </td>
            <td align="center" > <i class="goodsAmount" id='goodsAmount[{{item.partner_phone}}]'>0</i>
                <input type="hidden" name='goodsNumber' value='0'>元</td>
        </tr>

        <input type="hidden" name='get_username[]' value='{{ item.partner_phone }}'>
        <input type="hidden" name='get_realname[{{item.partner_phone}}]' value='{{ item.partner_name }}'>
        <input type="hidden" name='get_bank_name[{{item.partner_phone}}]' value='{{ item.bank_name }}'>
        <input type="hidden" name='get_bank_account[{{item.partner_phone}}]' value='{{ item.bank_account }}'>
        <input type="hidden" name='get_bank_card[{{item.partner_phone}}]' value='{{ item.bank_card }}'>
        <input type="hidden" name='get_bank_address[{{item.partner_phone}}]' value='{{ item.bank_address }}'>

        <input type="hidden" name='get_bank_text[{{item.partner_phone}}]' value='{{ item.banktext }}'>{% endfor %}</table>
    <!-- 分页 -->
    <div class="esc-page mt30 mb30 f-tac">
        {% if UserPartner['total_count']>1 %}
            {{ UserPartner['pages']}}
            <span>
                <label>去</label>
                <input type="text" name='p'  id='postPage' value='1'/>
                <label>页</label>
            </span>
            <input class="btn" type="button" value="确定" onclick="postPages()" />
        {% else %}
          <input type="hidden" name='p'  id='postPage' value='1'/>
        {% endif %}
    </div>
    <div class="btns f-tac">
        <input onclick="removeFriend()" class="btn1" type="button" value="取消" />
        <input onclick="addFriendCon()" class="btn2" type="button" value="确定" />
    </div>

</div>
<script type="text/javascript">
$(function(){
    unload();
    var $arr = [];
        var $unit = '';
        var $value = $.trim($('select[name="goods_unit"]').find("option:selected").text());
        $arr = $value.split('／');
        $unit = $arr[1];
        $('.esc-addConBox .unit').text($unit);
        $('.esc-chooseFriend .unit').text($unit);
    

});
function unload(){
    /**
     * 检测数量
     * @param
     * @return
     */
    $('.goods_number').unbind().blur(function(){
        /* 检测数量 */
        var reg =/^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+))$/;
        var gnumber = $(this).val();
        var r = gnumber.match(reg);

        if($(this).val == '' || r == null ){
            $(this).val('');
            alert('请检查输入数量');
            return ;
        }else{
            var number = $(this).val();
            var goods_price = $('#goods_price').val();
            var price = parseFloat(number * goods_price);
            var price = price.toFixed(2);
            $(this).parents('tr').find('.goodsAmount').html(price);
        }

    });
}
var page = 1;
function goto_pages(num){
    page = num;
    var mobile = $('#mobile').val();
    var paytype = $('#paytype').val();
    replaceRest(mobile , paytype,page);
}

function postPages(){
    page = $('#postPage').val();
    total={{UserPartner['total_count']}};
    if(page==0){
        page=1;
    }
    if(page>total){
        page=total;
    }
    var mobile = $('#mobile').val();
    var paytype = $('#paytype').val();
    replaceRest(mobile ,paytype,page);
}
function replaceRest(mobile,paytype,page) {
    $.ajax({
            type: "POST",
            url:'/ajax/getUserPartner?p='+page,
            data:{mobile: mobile, paytype:paytype},
            async: false,
            error: function(request) {
                alert("Connection error");
            },
            success: function(data) {
                $(".down").parent().html(data);
            } 
       }); 
}
function addFriendCon(){
    var flag = true ;
 
    /* 获取所有选中的数据 */
    $('.usernameCheckbox').each(function(index, el) {

        if($(this).prop('checked')) {
                        
            var mobile = $(this).val();
            var name = $('input[name="get_realname['+ mobile+']"]').val();
            var add_goods_number = $('input[name="goods_numbers['+ mobile+']"]').val();
            var reg =/^[-+]?\d*$/;
        
            if(!add_goods_number || add_goods_number.match(reg) == null ) {
               flag = false;
               alert('请检测输入数量');
               return false;
            }

            if(!repeatMobile(mobile)) {
               flag = false;
               alert('请勿重复添加卖家');
               return false;   
            }

            var realname      = $('input[name="get_realname['+ mobile+']"]').val();
            var bank_address  = $('input[name="get_bank_address['+ mobile+']"]').val();
            var bank_card     = $('input[name="get_bank_card['+ mobile+']"]').val();
            var bank_account  = $('input[name="get_bank_account['+ mobile+']"]').val();
            var bank_name     = $('input[name="get_bank_name['+ mobile+']"]').val();
            var add_bank_text = $('input[name="get_bank_text['+ mobile+']"]').val();
            var goods_price   = $('#goods_price').val();
            var amount        = parseFloat(add_goods_number * goods_price);
            var amount = amount.toFixed(2);
            var largeBox      = $('<div class="largeBox" id="largeBox_' + mobile +'" >');

           // 填充商友信息和修改信息 布局按照页面给出的示例进行填充
           var largeBox_html = '<div class="sellerInfo" id="sellerInfo_'+mobile+'"><table cellpadding="0" cellspacing="0" width="632"><tbody><tr height="30"><td width="165"><font>手机号：</font>' + mobile + '<input type="hidden" name="mobile['+ mobile +']"  value="'+mobile+'" class="userName"/></td><td width="172"><font>名称：</font>'+ name +'<input type="hidden" name="realname['+ mobile +']" value="'+name+'" /></td><td width="143"><font>采购数量：</font><b class="cg-num f-fwn">' + add_goods_number +'</b><input type="hidden" name="goods_number['+ mobile +']" class="goods_sum_number" value="'+add_goods_number+'" /><b class="unit f-fwn">公斤</b></td><td width="170"><font>采购金额：</font> <i class="cg-price">' + amount + '</i>元<input type="hidden" name="goods_amount['+ mobile +']" value="'+amount+'" /></td></tr><tr height="30"><td><font>收款方式：</font>银行支付</td><td><font>收款银行：</font>'+ add_bank_text +'<input type="hidden" name="bank_name['+ mobile +']" value="'+bank_name+'"  /></td><td><font>开户名：</font>' + bank_account + '<input type="hidden" name="bank_account['+ mobile +']" value="'+bank_account+'" /></td><td><font>卡号：</font>'+ bank_card +'<input type="hidden" name="bank_card['+ mobile +']"  value="'+bank_card+'"/></td></tr><tr height="30"><td colspan="4"><font>所在地：</font>'+ bank_address +'<input type="hidden" name="bank_address['+ mobile +']" value="'+bank_address +'" /><input type="hidden" name="user_partner['+ mobile +']" value="true" /></td></tr></tbody></table><div class="btns"><a class="btn1" onclick="editSeller(this, '+ mobile+')" href="javascript:;">修改</a><a class="btn2" onclick="removeSellerOne(this)" href="javascript:;">删除</a></div></tbody></table></div><div class="sellerEditBox f-oh sellerEditBox_'+ mobile +'" ><div id="editShowDiv_'+mobile+'"></div></div>';
                largeBox.html(largeBox_html);
                $(largeBox).insertBefore('.addBtn');
                $('#submitButton').removeAttr('disabled');
                $('.messageTip').hide();
                
            }
    });
    
    
    if(flag){
        $('.esc-friendLayer').hide();
        $('.esc-chooseFriend').hide();
    }else{
        return false;
    };
       
    numPrice();
}

// // 检测采购量
// function checkNumber(obj){
//     var $value = $(obj).val();
//     var reg =/^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+))$/;
//     var r = $value.match(reg); 
//     if(!r)    {
//         alert('请检查输入信息');
//         return false;
//     }
// }


</script>
<style>
    .esc-chooseFriend .ss-btn{ clear:both; display: block; margin:15px auto 0; width:150px; height:40px; background: #f99514; text-align: center; line-height: 40px; color:#fff; font-size: 14px; border-radius: 4px; cursor: pointer;}

</style>