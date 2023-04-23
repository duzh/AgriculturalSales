<style>
ul li {}
</style>
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/jquery.powertip.css" />
<div class="dialog" style="width:620px; padding-bottom:3px;">
    <form action="/member/dialog/savepricepro" method="post" id="editprice">
    <div class="message">
      <ul>
          <li style="line-height: 37px;"><span class="label">供应编号：</span><em>{{ sell.sell_sn }}</em></li>
          <li style="line-height: 37px;"><span class="label">发布时间：</span><em>{{ date('Y-m-d H:i:s', sell.updatetime) }}</em></li>
          <li style="line-height: 37px;"><span class="label">供应时间：</span><em>{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</em></li>
          <li style="line-height: 37px;"><span class="label">供应商品：</span><em>{{ sell.title }}</em></li>
          <li>
          <span class="label" style="line-height: 37px;">设置价格：</span>
          <em class="clearfix" style="display:block;">
          <div class="post-offer" style="border:none;">
            <div class="message clearfix" style="padding-left:0; margin-top:0;">
              <div class="inputBox inputBox2 f-pr clearfix ptype1" {% if sell.price_type==0 %}style="display:block"{% else %}style="display:none"{% endif %} >
                               <input name="min_price" data-target="#price-yz1" class="input2 ptype1"  value="{{ sell.min_price }}" type="text" id="min_price" >
                               <i class="heng ptype1">-</i>
                               <input name="max_price" data-target="#price-yz2" class="input2 ptype1" value="{{ sell.max_price }}" type="text" id='max_price' ><i>元／<b class="add-unitChange">{{ goods_unit[sell.goods_unit] }}</b></i>
                                <i id="price-yz1"></i>
                                <i id="price-yz2"></i>
              </div>
              <div class="price-changes clearfix ptype2 "  {% if sell.price_type==1 %}style="display:block"
                            {% else %} style="display:none" {% endif %} >
                                <!-- 增加价格区间 -->
                                <div class="pc-left f-fl">
                                    <table cellpadding="0" cellspacing="0" width="100%" id="priceArea">
                                        <tr height="30">
                                            <th width="192">购买数量</th>
                                            <th width="200" class="border-none">产品单价</th>
                                        </tr>
                                        <?php $sellstepprices=$sellstepprice->toArray();?>
                                        {% if sellstepprices %}
                                           <?php $i=1;?>
                                           <?php $i1=1;?>
                                           {% for key,val in sellstepprice %}
                                            <tr height="44">
                                                <td>
                                                    <div class="n-box f-pr">
                                                        <i>购买</i>
                                                        <input type="text" name="step_quantity[]" onkeyup="check_price_quantity()" value="{{val.quantity}}" data-target="#jt-price-yz<?php echo $i++ ?>"  >
                                                        <i><b class="add-unitChange">{{ goods_unit[sell.goods_unit] }}</b>及以上</i>
                                                        <em class="jt-jgYz1" id="jt-price-yz<?php echo $i1++ ?>"></em>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="n-box f-pr">
                                                        <input type="text" name="step_price[]" value="{{val.price}}"   onkeyup="check_price_quantity()" data-target="#jt-price-yz<?php echo $i++ ?>" >
                                                        <i>元／<b class="add-unitChange">{{ goods_unit[sell.goods_unit] }}</b></i>{% if key>0 %}<a href="javascript:;" onclick="delprices(this)">删除</a>{% endif %}
                                                        <em class="jt-jgYz2" id="jt-price-yz<?php echo $i1++?>"></em>
                                                    </div>
                                                </td>
                                            </tr>
            
                                            {% endfor %}
                                        {% else %}
                                            <tr height="44">
                                                <td>
                                                    <div class="n-box f-pr">
                                                        <i>购买</i>
                                                        <input type="text" name="step_quantity[]" onkeyup="check_price_quantity()" data-target="#price-yz5"  >
                                                        <i><b class="add-unitChange">公斤</b>及以上</i>
                                                        <em class="jt-jgYz1" id="price-yz5"></em>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="n-box f-pr">
                                                        <input type="text" name="step_price[]" onkeyup="check_price_quantity()" data-target="#price-yz6" >
                                                        <i>元／<b class="add-unitChange">公斤</b></i>
                                                        <em class="jt-jgYz2" id="price-yz6"></em>
                                                    </div>
                                                </td>
                                            </tr>
                                        {% endif %}
                                    </table>
                                    <span class="msg-box" id="price-yz4" ></span>
                                    <a href="javascript:;" {% if SellStepcount>=3 %} style="display:none" {% endif %}class="add-priceArea" id="add_step" >+增加价格区间</a>
                                   
              </div> 
            </div>  
          </div>
          </em>
         </li>
          
         
                                    
          <li style="margin-bottom:0; margin-top:22px;">
                <span class="label">&nbsp;</span>
                <em>
                    <input type="hidden" name="quantity" value="{{ quantity }}" />
                    <input type="hidden" name="sellid" value="{{ sell.id }}" />
                     <input type="hidden" name="price_type" value="{{ sell.price_type }}" />
                    
                    <input class="fu_btn submit_btn" type="submit" value="确认修改" style="margin:0px!important;margin-bottom:5px;"/>
                </em>
            </li>
        </ul>
        
    </div>
    </form>
</div>
<style type="text/css">
    #price-yz1 .n-error{ position: absolute; left:0; bottom:-20px; margin:0;}
    #price-yz1 .n-ok{ position: absolute; left:88px; top:12px; margin:0;}
    #price-yz2 .n-error{ position: absolute; left:130px; bottom:-20px; margin:0;}
    #price-yz2 .n-ok{ position: absolute; left:220px; top:12px; margin:0;}
</style>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery.form.js"></script>

<script>
    
// var api = frameElement.api, W = api.opener;


    $('#editprice').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9]){1,2})?$/, '小数点后最多支持2位'],
            intqutaily:[/^[1-9]\d*$/,'请输入正整数'],
            checkNum: function () {
                    var quantity = "{{sell.quantity}}";
                    var price_type="{{sell.price_type}}";
                    if(price_type==1){
                        $('input[name="step_quantity[]"]').each(function(i){
                            if(i == 0){
                                min_number = $(this).val();
                            }
                        });
                        //var min_number = getprice_quantity();
                    }else{
                        var min_number = $('#min_number').val();
                    }
                    if(parseFloat(quantity) < parseFloat(min_number) &&  parseFloat(quantity) > 0 && parseFloat(min_number) > 0 ) {
                        if(price_type==1){
                        return '供应量不能小于购买数量';
                        }else{
                        return '供应量不能小于起购量';    
                        }
                    }else{
                        return true;
                    }
                    if(parseFloat(min_number) > parseFloat(quantity) && parseFloat(quantity) > 0 && parseFloat(min_number) > 0 ) {
                        if(price_type==1){
                        return '购买数量不能大于供应量';
                        }else{
                        return '起购量不能大于供应量';    
                        }
                    }else{
                        return true;
                    }   
            },
            checkMax: function(element, param, field) {

                switch(param[0]) {
                    case 'scgt':
                        return parseFloat(element.value) > parseFloat($('input[name="'+param[1]+'"]').val()) || '价格区间不正确';
                    case 'cz':
                        return parseFloat(element.value) < parseFloat($('input[name="'+param[1]+'"]').val()) || '价格区间不正确';
                }
            },
            checkstep_quantity: function () {
                    var els =document.getElementsByName("step_quantity[]");
                    for (var i = 0, j = els.length; i < j; i++){
                        if(els[0]&&els[1]){
                           if(els[0].value&&els[1].value&&parseInt(els[0].value)>=parseInt(els[1].value)){
                                 return '购买数量必须大于上一区间';
                           }else if(els[1]&&els[2]){
                               if(els[1]&&els[2]&&parseInt(els[1].value)>=parseInt(els[2].value)){
                                 return '购买数量必须大于上一区间';
                               }else{
                                 return true;
                               }
                           }else{
                              return true;
                           }
                        }
                    }
            },
            checkstep_price: function () {
                    var els =document.getElementsByName("step_price[]");
                    for (var i = 0, j = els.length; i < j; i++){
                        if(els[0]&&els[1]){
                           if(els[0].value&&els[1].value&&parseFloat(els[0].value)<=parseFloat(els[1].value)){
                                  return '产品单价必须小于上一区间';
                           }else if(els[1]&&els[2]){
                                   if(els[1]&&els[2]&&parseFloat(els[1].value)<=parseFloat(els[2].value)){
                                         return '产品单价必须小于上一区间';
                                   }else{
                                         return true;
                                   }
                           }else{
                                return true;
                           }
                        }
                    }
            }
        },
        ignore: ':hidden',
        fields: {
          'min_price': '最小价格:required;float[*];nimei;checkMax[cz, max_price];length[~8]',
          'max_price': '最大价格:required;float[*];nimei;checkMax[scgt, min_price];length[~8]',
          'step_quantity[]':"购买数量:required;intqutaily;checkstep_quantity;checkNum;length[~8]",
          'step_price[]':"产品单价:required;nimei;checkstep_price;length[~8]"
        }
    });
    //添加
    function isprice(str){  
       var reg = /^([0-9])+(\.([0-9]){1,2})?$/;

       if(!str){
        return false;
       }
       if(str.value==''){
        return false;
       }
       if(str.value.length>8){
        return false;
       }
       if(!reg.test(str.value)){
         return false;
       }
       return true;
    }  
    function isquantity(str1){  
       var reg1 = /^[1-9]\d*$/;  
       var quantity = $("#quantity").val();
       if(!str1){
        return false;
       }
       if(str1.value==''){
        return false;
       }
       if(str1.value.length>8){
        return false;
       }
       if(!reg1.test(str1.value)){
         return false;
       }
       /*if(quantity!=''){
           var min_number = str1.value;
           if(parseFloat(quantity) < parseFloat(min_number) &&  parseFloat(quantity) > 0 && parseFloat(min_number) > 0 ) {
              return false
           }
           if(parseFloat(min_number) > parseFloat(quantity) && parseFloat(quantity) > 0 && parseFloat(min_number) > 0 ) {
                return false
           }  
       }*/
       return true;
    }
    function isgetquantity(str1){  
       var reg1 = /^[1-9]\d*$/;  
       var quantity = $("#quantity").val();
       if(!str1){
        return false;
       }
       if(str1.value==''){
        return false;
       }
       if(str1.value.length>8){
        return false;
       }
       if(!reg1.test(str1.value)){
         return false;
       }
       return true;
    }
    function check_price_quantity(){
      return true;
    }
    function check_add_price_quantity(){
              var str='';//<p>可根据买家采购的不同数量设置不同价格</p>
              var step_quantity =document.getElementsByName("step_quantity[]");
              var step_price =document.getElementsByName("step_price[]");
              if(step_quantity[0]&&step_price[0]&&!step_quantity[1]&&!step_price[1]&&!step_quantity[2]&&!step_price[2]){
                   var check1=isquantity(step_quantity[0]);
                   var check2=isprice(step_price[0]);
                   if(check1&&check2){
                      return true;
                   } 
              }
              if(step_quantity[0]&&step_price[0]&&step_quantity[1]&&step_price[1]&&!step_quantity[2]&&!step_price[2]){
                   var check3=isquantity(step_quantity[0]);
                   var check4=isprice(step_price[0]);
                   var check5=isquantity(step_quantity[1]);
                   var check6=isprice(step_price[1]);
                   if(check3&&check4&&check5&&check6){
                       if(parseInt(step_quantity[0].value)>=parseInt(step_quantity[1].value)||parseFloat(step_price[0].value)<=parseFloat(step_price[1].value)){

                       }else{
                           return true;
                       }
                   } 
              }
              if(step_quantity[0]&&step_price[0]&&step_quantity[1]&&step_price[1]&&step_quantity[2]&&step_price[2]){
                   var check7=isquantity(step_quantity[0]);
                   var check8=isprice(step_price[0]);
                   var check9=isquantity(step_quantity[1]);
                   var check10=isprice(step_price[1]);
                   var check11=isquantity(step_quantity[2]);
                   var check12=isprice(step_price[2]);
                   if(check7&&check8&&check9&&check10&&check11&&check12){
                       if(parseInt(step_quantity[1].value)>=parseInt(step_quantity[2].value)||parseFloat(step_price[1].value)<=parseFloat(step_price[2].value)){
                          // str='';
                       }else{
                            return true;
                       }
                   } 
              }
              return false;
    }
    function getprice_quantity(){
              var step_quantity =document.getElementsByName("step_quantity[]");
              var step_price =document.getElementsByName("step_price[]");
              if(step_quantity[0]&&step_price[0]&&!step_quantity[1]&&!step_price[1]&&!step_quantity[2]&&!step_price[2]){
                   var check1=isgetquantity(step_quantity[0]);
                   var check2=isprice(step_price[0]);
                   if(check1&&check2){
                      return step_quantity[0].value;
                   } 
              }
              if(step_quantity[0]&&step_price[0]&&step_quantity[1]&&step_price[1]&&!step_quantity[2]&&!step_price[2]){
                   var check3=isgetquantity(step_quantity[0]);
                   var check4=isprice(step_price[0]);
                   var check5=isgetquantity(step_quantity[1]);
                   var check6=isprice(step_price[1]);
                   if(check3&&check4&&check5&&check6){
                       if(parseInt(step_quantity[0].value)>=parseInt(step_quantity[1].value)||parseFloat(step_price[0].value)<=parseFloat(step_price[1].value)){

                       }else{
                          return step_quantity[1].value;
                       }
                   } 
              }
              if(step_quantity[0]&&step_price[0]&&step_quantity[1]&&step_price[1]&&step_quantity[2]&&step_price[2]){
                   var check7=isgetquantity(step_quantity[0]);
                   var check8=isprice(step_price[0]);
                   var check9=isgetquantity(step_quantity[1]);
                   var check10=isprice(step_price[1]);
                   var check11=isgetquantity(step_quantity[2]);
                   var check12=isprice(step_price[2]);
                   if(check7&&check8&&check9&&check10&&check11&&check12){
                       if(parseInt(step_quantity[1].value)>=parseInt(step_quantity[2].value)||parseFloat(step_price[1].value)<=parseFloat(step_price[2].value)){
                          // str='';
                       }else{
                            return step_quantity[2].value;
                       }
                   } 
              }
              return 0;
    }
    function delprices(object){
        $(object).parents('tr').remove();
        if($('input[name="step_quantity[]"]').length<3){
            $("#add_step").show();
        }
    }

  (function(){
      var i = 7;
      $('.add-priceArea').click(function(){
          var unit = $("#ladder_goods_unit  option:selected").text();
          //$('.add-unitChange').html(unit);
          var check_add_price=check_add_price_quantity();
          if(check_add_price){
              var str = '<tr height="44"><td><div class="n-box f-pr"><i>购买</i><input data-target="#jt-price-yz' + i + '" type="text" name="step_quantity[]" onkeyup="check_price_quantity()" ><i><b class="add-unitChange">'+ unit +'</b>及以上</i><em class="jt-jgYz1" id="jt-price-yz' + i + '"></em></div></td><td><div class="n-box f-pr"><input data-target="#jt-price-yz' + (++i) + '" type="text" name="step_price[]" onkeyup="check_price_quantity()" ><i>元／<b class="add-unitChange">'+ unit +'</b></i><a href="javascript:;" onclick="delprices(this)">删除</a><em class="jt-jgYz2" id="jt-price-yz' + (i++) + '"></em></div></td></tr>';
              if($('input[name="step_quantity[]"]').length>=2){
                  $("#add_step").hide();
              }
              $("#price-yz4").html('');
              $(str).appendTo($('#priceArea'));
              i++;
          }else{
              $("#price-yz4").html('<span class="msg-box n-right" style="" for="title"><span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">验证通过才可以添加下一组</span></span></span>');
          }
          
      });
  })();
    //检测+预览
 window.parent.dialog.size(554,376);


</script>
<style>
    .uploadify{ z-index:2; opacity:0; filter:alpha(opacity:0);}
    #price-yz1 .n-ok .n-msg{ position:absolute; left:90px; line-height: auto;}
    #price-yz2 .n-ok .n-msg{ position:absolute; left:214px; line-height: auto;}
    #price-yz1 .n-error .n-msg{ line-height: auto;}
    #price-yz2 .n-error .n-msg{ position:absolute; left:124px; line-height: auto;}
    .jt-jgYz1 .n-ok .n-msg{ position:absolute; left:108px; top:15px; line-height: auto;}
    .jt-jgYz2 .n-ok .n-msg{ position:absolute; left:84px; top:15px; line-height: auto;}
    .jt-jgYz1 .n-error .n-msg{ position:absolute; bottom:0; left:15px; line-height: auto;}
    .jt-jgYz2 .n-error .n-msg{ position:absolute; left:19px; bottom:0; line-height: auto;}
    .post-offer .message .pc-left .n-box{ padding-bottom:8px;}
    b{ font-weight: normal;}
    .post-offer .message .price-changes{ width:auto; position: relative; padding-bottom: 20px;}
    #price-yz4 .n-error .n-msg{ position: absolute; bottom:0;}
    ul li .n-msg{ line-height: auto;}
</style>