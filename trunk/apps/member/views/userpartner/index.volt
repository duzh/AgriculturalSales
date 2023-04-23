{{ partial('layouts/member_header') }}
<!-- <link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css"> -->
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}js/validator/jquery.validator.css" />
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}我的商友</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">
            <div class="esc-myFriend">

                <div class="title">
                    <span>我的商友</span>
                    <a href="#" class="addFr-btn" onclick="editMsg(0)">添加商友</a>
                </div>
                <div class="esc-friendList f-oh">
                    <div class="esc-friendSearch f-oh">
                        <form method="get">
                        <label class="f-db f-fl clearfix">
                            <font class="f-db f-fl">手机号：</font>
                            <input name="moblie" type="text" class="f-fl" value="{{moblie}}">
                        </label>
                        <!--<label class="f-db f-fl clearfix">
                            <font class="f-db f-fl">收款方式：</font>
                            <select name="s_pay_type"  class="f-fl">
                                <option value="all"{% if (s_pay_type == 'all') %} selected="selected" {% endif %} >请选择</option>
                                {% for key, val in _pay_type %}
                                <option value="{{ key }}" {% if (s_pay_type == key and s_pay_type != 'all') %} selected="selected" {% endif %} >{{ val }}</option>
                                {% endfor %}
                            </select>
                        </label>-->
                        <input type="submit" value="查找" class="btn f-fr">
                        </form>
                    </div>
                    <div class="esc-searchList">

                        <!-- 列表 -->
                        {% for key , userpartner in data['items']%}
                        <div class="listBox f-oh">
                            <div class="msg f-fl clearfix">
                                <label class="f-db f-fl" style="width: 180px;">
                                    <font>手机号：</font>{{userpartner.partner_phone}}
                                </label>
                                <label class="f-db f-fl" style="width: 180px;">
                                    <font>名称：</font>{{userpartner.partner_name}}
                                </label>
                                <label class="f-db f-fl" style="width: 180px;">
                                    <font>收款方式：</font>
                                    {% for key, val in _pay_type %}
                                    {% if (userpartner.pay_type == key) %} {{ val }} {% endif %}
                                    {% endfor %}
                                </label>
                                {% if (userpartner.pay_type == 1) %}
                                <br>
                                <label class="f-db f-fl" style="width: 180px;">
                                    <font>收款银行：</font>
                                    {% for key, val in _banks %}
                                    {% if (userpartner.bank_name == val['gate_id']) %} {{val['bank_name']}} {% endif %}
                                    {% endfor %}
                                </label>
                                <label class="f-db f-fl" style="width: 180px;">
                                    <font>开户名：</font>{{userpartner.bank_account}}
                                </label>
                                <label class="f-db f-fl" style="width: 180px;">
                                    <font>卡号：</font>{{userpartner.bank_card}}
                                </label>
                                <br>
                                <label style="width:auto;" class="f-db f-fl">
                                    <font>所在地：</font>{{userpartner.bank_address}}
                                </label>
                                {% endif %}
                            </div>
                            <div class="btns f-fr">
                                <a href="#" onclick="editMsg({{userpartner.id}})">修改</a>
                                <a href="#" onclick="delpartner({{userpartner.id}})">删除</a>
                            </div>
                        </div>
                        {% endfor %}
                    </div>
                    <!-- 分页 -->
                    {% if data['total_count']>1 %}
                    <form action="/member/userpartner/index" method='get'>
                        <div class="esc-page mt30 mb30 f-tac f-fr mr30">

                            {{ data['pages'] }}
                            <span>
                                <label>去</label>
                                <input type="text" name='p' id="p" value='1' onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">
                                <label>页</label>
                            </span>
                            <input class="btn" type="submit" value="确定" onclick="go()"></div>

                    </div>
                        
                    </form>
                     {% endif %}  
                </div>

            </div>
        </div>
    </div>
</div>
<!-- 弹框 -->
<div class="esc-layer"></div>
<div class="esc-friendAlert">
    <a class="close-btn" href="javascript:;"></a>
    <form action="#" id="addFriendForm" onsubmit="return false;">
    <div class="editMsg clearfix" style="margin-left: 36px;">
        <input type="hidden" id="partner_id" value="">
        <div class="noEdit f-fl">
            <i id="dg_moblie">手机号：</i>
            <div></div>
        </div>
        <div class="box f-fl clearfix">
            <font>卖家名称：</font>
            <div class="inputVal f-pr">
                <input name="partner_name" type="text" data-rule="required;chinese;" id="partner_name" value="">
            </div>
        </div>
    </div>
    <div class="radioBox">
        <!--<label class="f-db clearfix">
            <input name="pay_type" type="radio" id="pt_nyb" value="1" />
            <font>结算到云农宝</font>
        </label>-->
        <label class="f-db clearfix">
            <input name="pay_type" type="radio" value="2" checked />
            <font>结算到银行</font>
        </label>
    </div>
    <div class="editMsg no-editMsg clearfix">
        <div class="box f-fl clearfix">
            <font>结算银行：</font>
            <div class="selectBox f-pr">

                <select id="bank_names" name="bank_name">
                    <option value="" >请选择</option>
                    {% for key, val in _banks %}
                    <option value="{{ val['gate_id'] }}"  >{{ val['bank_name'] }}</option>
                    {% endfor %}
                </select>

            </div>
        </div>
        <div class="box f-fl clearfix">
            <font>开户名：</font>
            <div class="inputVal f-pr">
                <input name="bank_account" id="bank_account" data-rule="required;chinese;" type="text" value="">
            </div>
        </div>
    </div>
    <div class="editMsg no-editMsg clearfix">
        <div class="box f-fl clearfix">
            <font>银行卡号：</font>
            <div class="inputVal f-pr">
                <input name="bank_card" id="bank_card" data-rule="required;mark;" type="text" value="">
            </div>
        </div>
        <div class="box f-fl clearfix">
            <font>银行所在地：</font>
            <div class="inputVal f-pr">
                <input name="bank_address" id="bank_address" data-rule="required;" type="text" value="">
            </div>
        </div>
    </div>
    <div class="btns f-tar">
        <input class="btn1" type="submit" id="savedata" value="添加" />
        <input class="btn2 but-quit" type="button" value="取消" />
    </div>
    </form>
</div>
<!-- 底部 -->
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
                style : {"width" : 140}
        });
        // 验证
        $('#addFriendForm').validator({
            rules: {
                //username: [/^\w$/, "请输入正确的银行所在地"]
            },
            fields: {
                'bank_name': 'required;'
            },
            valid:function(form){
                if(checkdatas()){
                    $('#savedata').attr('disabled',true);
                    $.ajax({
                        type: "POST",
                        url: "/member/userpartner/savedatas",
                        data: "partner_phone="+$("#partner_phone").val()+"&partner_name="+$("#partner_name").val()+"&pay_type=1&bank_name="+$("#bank_names :selected ").val()+"&bank_account="+$("#bank_account").val()+"&bank_card="+$("#bank_card").val()+"&bank_address="+$("#bank_address").val()+"&partner_id="+$("#partner_id").val(),
                        dataType: "text",
                        success:function(res){
                            if(res == 1)window.location.reload();
                            else alert(res); $('#savedata').attr('disabled',false);
                        }
                    });
                }
                else{
                    //alert('数据不完整');
                }
            }
        });
        $('#partner_phone').focusout(function(){
            re = /^1[34578]\d{9}$/;
            if($(this).val()){
                if(re.exec($(this).val())){
                    $.ajax({
                        type: "GET",
                        url: "/member/userpartner/checkuser",
                        data: "moblie="+$(this).val(),
                        dataType: "text",
                        success:function(res){
                            var _disabled = (res == 1)?false:true;
                            $('#pt_nyb').attr('disabled',_disabled);
                        }
                    });
                }
                else
                {
                    alert('格式错误');
                }
            }

        });
        $('.but-quit').click(function(){
            $("#dg_moblie + span").replaceWith("<div></div>");
            $('.esc-layer').hide();
            $('.esc-friendAlert').hide();
        });
    });
    function editMsg(val){

        //val是用来区别修改的哪个信息
        var _index = val;
        if(_index !=0){
            $('#partner_id + div').attr('class','noEdit f-fl');
            $.ajax({
                type: "GET",
                url: "/member/userpartner/getdata",
                data: "id="+_index,
                dataType: "json",
                success:function(res){
                    var data = eval(res);
                    $("#savedata").val('修改');
                    $("#partner_id").val(data.id);
                    $('#dg_moblie + div').replaceWith('<span>'+data.partner_phone+'<input type="hidden" id="partner_phone" value="'+data.partner_phone+'"></span>');
                    $('#dg_moblie').replaceWith('<i id="dg_moblie">手机号：</i>');
                    $("#partner_name").val(data.partner_name);
                    $("#pay_type").val(data.pay_type);
                    $("#bank_names").val(data.bank_name);
                    $("#bank_account").val(data.bank_account);
                    $("#bank_card").val(data.bank_card);
                    $("#bank_address").val(data.bank_address);
                }
            });
        }
        else{
            $('#partner_id + div').attr('class','box f-fl clearfix phone-box');
            $('#dg_moblie + div').replaceWith('<div class="inputVal clearfix"><input style="margin-left:-28px;" type="text" id="partner_phone" data-rule="required;mobile" data-rule-mobile="[/^1[34578]\\d{9}$/, '+"'"+'手机格式不正确'+"'"+']" onblur="cSelectName(this.value);"  name="partner_phone" value=""></div>');
            $('#dg_moblie').replaceWith('<font style="width:auto; text-align:left;" id="dg_moblie">手机号：</font>');
            $("#savedata").val('添加');
            $("#partner_id").val('');
            $("#partner_phone").val('');
            $("#partner_name").val('');
            $("#pay_type").val('');
            $("#bank_names").val('');
            $("#bank_account").val('');
            $("#bank_card").val('');
            $("#bank_address").val('');
        }
        $('#addFriendForm').validator('cleanUp');
        $('.esc-layer').show();
        $('.esc-friendAlert').show();
    };
    function delpartner(pid){
        if(confirm('确认要删除吗?')){
            $.ajax({
                type: "GET",
                url: "/member/userpartner/del",
                data: "id="+pid,
                dataType: "text",
                success:function(res){
                    if(res == 1){
                        window.location.reload();
                    }
                    else{
                        alert('删除失败!');
                    }
                }
            });
        }
    }
    function cSelectName(mobile) {
    /* 获取银行卡信息 以及姓名信息 */
    $.ajax({
        url: '/ajax/checkUserPartner',
        type: 'POST',
        dataType: 'json',
        data: {add_partner_phone: mobile , send : '1'},
        async:false,
        success:function (e) {
            if(e.uname) {
                $('#partner_name').val(e.uname);
            }

        }
    })

}
    $('.esc-friendAlert .close-btn').click(function() {
        $("#dg_moblie + span").replaceWith("<div></div>");
        $('.esc-layer').hide();
        $(this).parent().hide();
    });
    function checkdatas(){
        var partner_phone =  $("#partner_phone").val();
        if(partner_phone == ''){
            return false;
        }
        var partner_name =  $("#partner_name").val();
        if(partner_name == ''){
            return false;
        }
        var pay_type =  $("#pay_type :checked").val();
        if(pay_type == ''){
            return false;
        }
        var bank_name =  $("#bank_name :selected").val();
        if(bank_name == '0'){
            return false;
        }
        var bank_account =  $("#bank_account").val();
        if(bank_account == ''){
            return false;
        }
        var bank_card =  $("#bank_card").val();
        if(bank_card == ''){
            return false;
        }
        var bank_address =  $("#bank_address").val();
        if(bank_address == ''){
            return false;
        }
        return true;
    }
    

</script>
<style>
    /*.esc-friendAlert .editMsg .phone-box{ padding-left:52px;}
    .esc-friendAlert .editMsg .phone-box font{ margin-left:-52px;}
    .msg-box{ display: block; margin:0; padding:0;}
    .msg-box .n-error{ margin:0; padding:0;}
    .n-default .n-left, .n-default .n-right{ margin-top:0;}*/
    .esc-friendAlert .radioBox{ margin-top:18px;}
    .esc-friendAlert .editMsg{ margin-top:18px; *margin-top:10px;}
    /*.msg-box .n-ok{ position: absolute; right:-18px; top:-28px;}*/
</style>
