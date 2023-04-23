<!--头部 start-->
{{ partial('layouts/member_header') }}
<!--头部 end-->   
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css" />

<!--主体 start-->
<div class="wrapper">
        <div class="bread-crumbs w1190 mtauto">
            <span>{{ partial('layouts/ur_here') }}&gt;&nbsp;我的银行账户</span>
        </div>
    <div class="w1185 mtauto clearfix">
        <!-- 左侧 start-->
        {{ partial('layouts/navs_left') }}
        <!-- 左侧 end-->
        
        <!-- 右侧 start-->
        <div class="center-right f-fr">
            <!-- 我的账户 -->
            <div class="my-bankAccount f-oh" style="min-height:800px;margin-bottom:30px;">
                <div class="title f-oh">
                    <span class="f-fl">我的银行账户</span>
                    <a class="add-bankCard f-fr" href="javascript:void();" id="addBankCard">添加银行卡</a>
                </div>
                <table cellpadding="0" cellspacing="0" width="890" class="list">
                    <tr height="32">
                        <th width="20"></th>
                        <th align="left" width="116">开户行</th>
                        <th width="155">开户行所在地</th>
                        <th width="105">开户名</th>
                        <th width="166">帐号</th>
                        <th width="110">来源</th>
                        <th width="84">默认</th>
                        <th width="135">操作</th>
                    </tr>
                    {% if data %}
                        {% for key,val in data['items'] %}
                    <tr height="36">
                        <td></td>
                        <td><?php echo Mdg\Models\Bank::selectByCode($val['bank_name']);?></td>
                        <td align="center">{{ val['bank_address'] }}</td>
                        <td align="center">{{ val['bank_account'] }}</td>
                        <td align="center">{{ val['bank_cardno'] }}</td>
                        <td align="center">
                            {% if val['source_type'] %}
                            {{ val['source_type'] }}
                            {% endif %}
                        </td>
                        <td align="center">{% if val['is_default'] %}默认{% endif %}</td>
                        <td align="center">
                            {% if !val['is_default'] %}
                                <a href="javascript:void(0)" onclick="del({{ val['id'] }},1);">设为默认</a>
                                {% if !val['source_type'] %}<a href="javascript:void(0)" onclick="del({{ val['id'] }},2);">删除</a>{% endif %}
                            {% endif %}
                        </td>
                    </tr>
                        {% endfor %}
                        {% endif %}
                </table>
                    <form action="/member/usercard/index">
                {% if data['total_count']>1 %}
                <div class="esc-page mt30 mb30 mr30 f-tac f-fr">
                    
                    {{ data['pages']}}
                    <span>
                        <label>去</label>
                        <input type="text" name='p' id="p" value='1' />
                        <label>页</label>
                    </span>
                    <input class="btn" type="submit" value="确定" onclick="go()"/>
                    
                </div>
                {% endif %}
            </form>
                
            </div>
        </div>
        <!-- 右侧 end-->
        <!-- 弹框 -->
        <style>
            .plant-add-box .message{margin-top:20px;}
            .plant-add-box .message font{width:100px;}
            .plant-add-box .plant-layer-btn{margin-left:100px;margin-top:20px;height:30px;line-height:30px;}
            .msg-box .n-error .n-msg{position:relative;left:10px;bottom:0;}
            .msg-box .n-ok .n-msg{position:relative;left:10px;top:0;}
            .ui_title_buttons {
    top: 2px;
    right: 5px;
}
.ui_title_buttons {
    position: absolute;
    cursor: pointer;
    font-size: 0;
    letter-spacing: -.5em;
}
.close-btn {
    _line-height: 22px;
    color: #FFF;
    font-size: 22px;
    line-height: 18px;
    font-weight: 500;
    outline: 0 none;
    letter-spacing: normal;
    text-align: center;
    zoom: 1;
    vertical-align: top;
    font-family: tahoma,arial,\5b8b\4f53,sans-serif;
}
        </style>
        <div class="addBankCard-layer"></div>
        <div class="addBankCard" style="width:500px;height:400px;">
            <div class="ui_title_buttons">
            <a href="javascript:;" class="close-btn" style="display: inline-block;top:5px;right:-5px;background:none;">×</a>
            </div>
            <div class="title">添加银行卡</div>
            <form method="post"   id='addBankCard_form'  action="/member/usercard/save" onsubmit=" return selectbankNo()"> 
                <div class="message clearfix">
                    <font>开户行：</font>
                    <div class="selectBox">
                        <select name='name'  data-rule="required;" >
                            <option value=''>请选择</option>
                            {% for key , item in  bankList %}
                            <option value='{{ item['gate_id']}}'>{{ item['bank_name']}}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="message clearfix">
                    <font>开户所在地：</font>
                    <div class="selectBox">
                        <select name='province_id' class='ent_class_bank_address'>
                            <option value=''>请选择</option>
                        </select>
                        <select name='city_id'  class='ent_class_bank_address' >
                            <option value=''>请选择</option>
                        </select>
                        <select name='district_id'  class='ent_class_bank_address' data-rule="required;" >
                            <option value=''>请选择</option>
                        </select>
                    </div>
                </div>
                <div class="message clearfix">
                    <font>开户名：</font>
                    <div class="inputBox">
                        <input type="text" name='account'  data-rule="required;chinese" />
                    </div>
                </div>
                <div class="message clearfix">
                    <font>账号：</font>
                    <div class="inputBox">
                        <input type="text"  name='cardno' data-rule="required;mark;"  id="cardno"/>
                    </div>
                </div>
                <input class="refer-btn"  id='refer_btn_submit' type="submit" value="提交"/>
                <input type="hidden" name='refer_btn_cardno'  id='refer_btn_cardno' value='1'>
            </form>
        </div>
        <!--弹出结束-->
    </div>
</div>
<!--主体 end-->

<!--尾部 start-->
{{ partial('layouts/footer') }}
<script type="text/javascript" src="/mdg/js/user_farm.js?sid={{ sid }}&rand=<?php echo rand(1,999);?>"></script>
<!--尾部 end-->
<script type="text/javascript">
  function go(){
var p=$("#p").val();
 var count = {{data['total_count']}};
 if(p>count){
    $("#p").val(count);
 }
}  
function selectbankNo() {
    var cardno = $('#cardno').val();
    /* 检测数量 */
    var reg =/^(\d{18}|\d{16}|\d{17}|\d{19}|\d{20})$/;
    var r = cardno.match(reg); 
    if(r == null )    {
        // alert('请检查输入卡号');
        return false;
    }
    var flag = true;
    $.ajax({
        url: '/ajax/selectbankNo',
        type: 'POST',
        dataType: 'JSON',
        data: {cardno: cardno},
        async:false,
        success:function (e) {

            if(e.state == 4 ) {
                alert('参数错误');
                $('#refer_btn_submit').attr('disabled', true);
                $('#refer_btn_cardno').val(0);
                flag = false;
            }

            if(e.state ==0 ) {
                $('#refer_btn_submit').attr('disabled', false);
                $('#refer_btn_cardno').val(1);
                
            }
            if(e.state == 2 ) {
                alert('与认证卡号重复,不可重复添加');
                $('.addBankCard-layer').hide();
                $('.addBankCard').hide();
                $('#refer_btn_submit').attr('disabled', true);
                $('#refer_btn_cardno').val(0);
                flag = false;
            }
            
            if(e.state == 1 ) 
            {
                if(confirm('该银行卡号重复,是否覆盖？ ')) {
                        // $('#addBankCard_form').submit();
                        $('#refer_btn_submit').attr('disabled', false);
                        $('#refer_btn_cardno').val(1);
                }else{
                    $('#refer_btn_cardno').val(0);
                    flag = false;
                }
            }
        }
    })

    return flag;
    
}
$(function(){
    $('#addBankCard').click(function(){

        $('#addBankCard_form').validator( "cleanUp" );
        document.getElementById('addBankCard_form').reset();

        $('.addBankCard-layer').show();
        $('.addBankCard').show();
        
    });
    $('.close-btn').click(function(){
        $('.addBankCard-layer').hide();
        $('.addBankCard').hide();
    });
});

</script>

<script type="text/javascript">
function del(id,type) {
    $.ajax({
        type:"POST",
        url:"/member/usercard/set",
        data:{id:id,type:type},
        dataType:"json",
        success:function(msg){
            if(msg['code'] == 4) {
                alert(msg['result']);
                window.location.reload();
            } else {
                alert(msg['result']);
                return;
            }
        }
    });
}


</script>
