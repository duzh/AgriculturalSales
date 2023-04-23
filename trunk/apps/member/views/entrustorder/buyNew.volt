{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}js/validator/jquery.validator.css">
<div class="center-wrapper pb30 f-oh">
    <form action="/member/entrustorder/buyCreate" method='post' id='createBuy'>
        <!-- 新建委托交易-添加卖家 -->
        <div class="esc-order-pay w1190 mtauto f-oh ">
            <div class="bread-crumbs w1185 mtauto">
               {{ partial('layouts/ur_here') }}委托交易
            </div>
            <div class="esc-pay-process">

                <div class="title">委托交易</div>
                <div class="pay-step clearfix">
                    <div class="step step1 active">
                        <div class="num">1</div>
                        <div class="con">创建交易</div>
                    </div>
                    <div class="step step2">
                        <div class="num">2</div>
                        <div class="con">付款</div>
                    </div>
                    <div class="step step3">
                        <div class="num">3</div>
                        <div class="con">线下交割</div>
                    </div>
                    <div class="step step4">
                        <div class="num">4</div>
                        <div class="con">确认收货</div>
                    </div>
                    <div class="step step5">
                        <div class="num">5</div>
                        <div class="con">完成交易</div>
                    </div>
                </div>
                <div class="m-line"></div>
                <div class="m-title">基本信息</div>
                <div class="esc-pay-form">
                    <div class="message clearfix"> <font>商品名称：</font>
                        <div class="inputVal">
                            <input type="text" name='goods_name' data-rule="required;filter;length[1~50]"  value='' />
                        </div>
                    </div>
                    <div class="message clearfix"> <font>采购类别：</font>
                        <div class="selectVal">
                            <select  name='category_id_one' class='selectCate'  >
                                <option value=''>请选择</option>
                            </select>
                            <select name='category_id_two'  class='selectCate' data-rule="required;"  data-target="#cateTip"  >
                                <option value=''>请选择</option>
                            </select> <i id='cateTip'></i>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>商品单价：</font>
                        <div class="inputVal">
                            <input type="text" name='goods_price' id='goods_price' value=''  data-rule="required;price" data-target="#priceTip"   data-rule-price="[/^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+)|([0-9]+\.[0-9]{1,2}))$/, '请输入正确的单价']"   />
                            <select id="unitEle" name='goods_unit' >
                                {% for key,val in _goods_unit %}
                                <option value="{{ key }}">元／{{ val }}</option>
                                {% endfor %}
                            </select> <i id='priceTip'></i>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>说明：</font>
                        <div class="areaVal">
                            <textarea name='demo'  id='demo'  ></textarea>
                            <i id='demo_tip'></i>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>工程师手机号：</font>
                        <div class="inputVal">
                            <input type="text" name='engphone' id='engphone' data-rule="mobile;remote[/ajax/checkengphone]"  data-target="#engineer_phoneTip" />
                            (选填)
                            <i id='engineer_phoneTip'></i>
                        </div>
                    </div>
                </div>
                <div class="m-line"></div>
                <div class="m-title">添加卖家</div>
                <div class="m-tip messageTip" id='sellmessageTip' >还没有卖家，请点击下面"添加新卖家“或”选择我的商友“添加！</div>
                <div class="esc-addConBox">

                    <!-- 添加按钮 -->
                    <div class="addBtn">
                        <a onclick="addSeller()" class="btn1" href="javascript:;">添加新卖家</a>
                        <a onclick="addFriend()" class="btn2" href="javascript:;">选择我的商友</a>
                    </div>
                    <!-- 添加新卖家表单 -->
                    <div class="esc-addSeller f-oh" >
                        <div id='showDiv'></div>

                    </div>

                    <div class="esc-pay-price f-tar">
                        <span>
                            总采购量：
                            <span id='sum_weight'></span> <b class="unit f-fwn">公斤</b>
                        </span>
                        <span>
                            总采购金额：
                            <i id='sum_amount'></i>
                            元
                        </span>
                    </div>
                    <div class="esc-pay-btn f-tar">
                        <input type="hidden" name='buy_mobile' id='buy_mobile' value='{{ buy_mobile }}'>
                        <input class="hack-bc" type="submit" id='submitButton' value="确认无误，付款" disabled="disabled"/>
                    </div>

                </div>
            </div>

        </div>

        <!-- 选择商友 弹框 -->
        <div class="esc-friendLayer"  ></div>
        <div class="esc-chooseFriend pb20"></div>

    </form>

    <!-- 底部 -->
    {{ partial('layouts/footer') }}
    <script type="text/javascript">
$(function () {
        numPrice();

        $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
            defaultParentId : 0,
            style : {"width" : 140}
        });

        $('#demo').keyup(function(event) {
                var len    = $(this).val().length;
                var maxlen = 500;
                if(len > maxlen){
                $(this).val($(this).val().substring(0,maxlen));
                    $('#demo_tip').html('<b style="color : #f00;">最多只能输入'+ maxlen +'字</b>');
                }else{
                    $('#demo_tip').html('');
                }
        });

});
$("#submitButton").click(function(){
    if (".largeBox"){
        $('#submitButton').removeAttr('disabled');
        $('.messageTip').hide();
    }
});


    </script>

    <style>.esc-page{ margin-left:132px;}</style>

    <script>
        function add_goods_numbers(obj) {
            var number = $(obj).val();
            var goods_price =$('#goods_price').val();
            var price = parseFloat(number * goods_price);
            /* 检测身份证 */
            var reg = /^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+))$/;
            var r = number.match(reg);

            if(number <= 0 && price <= 0  || r == null ) {
                alert('请检出输入数量')  
                return false;
            }
            
            // price.substr(0,price.indexOf(".")+3);
            // alert(price.indexOf(".")+3);
            $('.addPrice').parents('div').find('.goodsAmount').html((price.toFixed(2)));
            
        }

        // 设置单位
        var $arr = [];
        var $unit = '';

        $('#unitEle').change(function(){         
            var $value = $.trim($(this).find("option:selected").text());
            $arr = $value.split('／');
            $unit = $arr[1];

            $('.esc-addConBox .unit').text($unit);
            $('.esc-chooseFriend .unit').text($unit);
        });

        // 修改金额
        $('input[name="goods_price"]').blur(function(){
            var addLargePrice = 0;

            var $num = parseFloat($(this).val());
            /* 检测身份证 */
            var reg = /^(([1-9]+)|([1-9]+[0-9])|([1-9]+[0-9]+)|([0-9]+\.[0-9]{1,2}))$/;
            var r = $num.match(reg);
            if(r == null ) {
                alert('请检查输入单价')  
                return false;
            }

            
            $('.cg-price').each(function(){
                var $cgNum = parseInt($(this).parents('.largeBox').find('.cg-num').html()); 
                $(this).parents('.largeBox').find('.cg-price').html($num*$cgNum);

                var $cgPrice = parseFloat($(this).parents('.largeBox').find('.cg-price').html());
                addLargePrice += $cgPrice;
            });
            addLargePrice = parseFloat(addLargePrice);
            addLargePrice.toFixed(2);
            $('#sum_amount').html(addLargePrice);
        });

        /**
         * 显示卖家
         */
        function addSeller(){
            var goods_name  = $('input[name="goods_name"]').val();
            var goods_price  = $('input[name="goods_price"]').val();
            var demo  = $('#demo').val();
            if(!goods_name || !goods_price ) {
                alert('请输入产品信息再添加卖方信息');
                return false;
            }
            $.ajax({ url: '/ajax/addSeller', type:'post',async:false, success:function (e) {
                    // alert(e);
                    $('#showDiv').html(e);
                    // var showDiv = document.getElementById('showDiv');
                    // showDiv.innerHTML = e;
                    $('.esc-addSeller').show();
                }
            })
        };
        

        // 取消添加新卖家
        function removeSeller(){
            if($('.userName').length == 0 ) {
                $('#sellmessageTip').show();
            }
            $('.esc-addSeller').hide();
            var sum_amount = 0.00;
            $('.sellerInfo').each(function(i){
                //$(this).children('table tr td .cg-price').html()
                $(this).children('table').each(function(l){
                    $(this).children('tbody').each(function(s){
                        $(this).children('tr').each(function(t){
                            $(this).children('td').each(function(t){
                                if($(this).children('.cg-price').length){
                                    sum_amount += parseFloat($(this).children('.cg-price').html());
                                }
                            });
                        });
                    });
                });
            });
            $('#sum_amount').html(sum_amount.toFixed(2));
            $('#showDiv').html('');
        };
        /**
         * 修改卖家信息
         * @param  {[type]} obj    
         * @param  {[type]} mobile 手机号
         * @return 
         */
        function editSeller(obj, mobile) {
            $.ajax({
                url: '/ajax/editSeller',
                type:'post',
                data:{mobile:mobile}, 
                async:false,
                success:function (e) {
                        $('#editShowDiv_' + mobile).html(e);
                        $('.sellerEditBox_' + mobile ).show();
                    }
                })
        }

        // 删除新添加卖家信息
        function removeSellerOne(obj) {

            numPrice();
            $(obj).parents('.largeBox').remove();
            if($('.userName').length == 0 ) {
                $('#submitButton').attr('disabled',true); 
                $('#sellmessageTip').show();   
            }
            var sum_amount = 0.00;
            $('.sellerInfo').each(function(i){
                //$(this).children('table tr td .cg-price').html()
                $(this).children('table').each(function(l){
                    $(this).children('tbody').each(function(s){
                        $(this).children('tr').each(function(t){
                            $(this).children('td').each(function(t){
                                if($(this).children('.cg-price').length){
                                    sum_amount += parseFloat($(this).children('.cg-price').html());
                                }
                            });
                        });
                    });
                });
            });
            $('#sum_amount').html(sum_amount.toFixed(2));
        }
        function clearSeller(obj) {
            $(obj).parents('.sellerEditBox').hide();
        }

  
        // 取消修改卖家信息
        $('.sellerEditBox .btn-box .btn2').click(function(){
            $(this).parents('.sellerEditBox').hide();
        });

        // 添加我的商友
        function addFriend(){
            var goods_name  = $('input[name="goods_name"]').val();
            var goods_price  = $('input[name="goods_price"]').val();
            var demo  = $('#demo').val();
            if(!goods_name || !goods_price ) {
                alert('请输入产品信息再添加卖方信息');
                return false;
            }

            /* 获取商友信息 然后覆盖div */
            $.ajax({
                url: '/ajax/getUserPartner',
                async:false,
                success:function (e) {
                    
                    $('.esc-chooseFriend').html(e);
                    $('.esc-chooseFriend').show();
                    $('.esc-friendLayer').show();
                }
            });
        }
        
        // 取消添加我的商友
        function removeFriend(){
            $('.esc-friendLayer').hide();
            $('.esc-chooseFriend').hide();
        }

        /* 更新产品总价 和 总量*/
        function numPrice() {
            var number = 0;
            var goods_price = $('#goods_price').val();
            $('.goods_sum_number').each(function(index, el) {
                    number += parseFloat($(this).val());
            }); 

            var amount = parseFloat(number * goods_price);
            $('#sum_weight').html(number);
            $('#sum_amount').html(amount.toFixed(2));
        }

        function repeatMobile(mobile) {
            var flag = 1 ;
            $('.userName').each(function(index, el) {
                    if($(this).val() == mobile ) { 
                        flag = 0 ;
                    }
            });
            return flag;
        }


function formatCurrency(num) {
    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
    num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num0;
    num = Math.floor(num/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+','+
    num.substring(num.length-(4*i+3));
    return (((sign)?'':'-') + num + '.' + cents);
}

    </script>