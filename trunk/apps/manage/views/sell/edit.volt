{{ form("sell/save", "method":"post","id":"mysell") }}
{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">修改供应信息</div>
        <div align="left" style="margin-top:20px;display:none">
            <input type="button" value="导入供应信息" class="sub" onclick="ShowDiv('MyDiv','fade')"/>
        </div>
        <div id="fade" class="black_overlay"></div>
        <div id="MyDiv" class="white_content">
            <div class="gb1">
                转移商品
                <a href="#" onclick="CloseDiv('MyDiv','fade')"></a>
            </div>
            <div class="tk5" >
                <input type="button" value="导入文件" class="sub" />
                <p>c://desktop/find/kedy123/产品图片.jpg</p>
            </div>
            <div class="tk4">
                导入模板：
                <a href="">下载</a>
            </div>
        </div>
        <div class="cx">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  colspan="2" class="cx_title1">1. 要发布的产品</td>
                </tr>
                <tr>
                    <td class="cx_title">要发布的产品：</td>
                    <td class="cx_content">{{ text_field("title",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">所属分类：</td>
                    <td class="cx_content">

                        <select class="selectCate" name="maxcategory" >
                            <option value="">一级分类</option>
                         </select>
                        <select class="selectCate" name="category"  id="selectcate" data-target="#msg" 
                        onchange="changefile()">
                            <option value="">二级分类</option> 
                        </select>
                         <i class="help-block" id="msg" ></i>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                
                <tr colspan="2">
                     <td class="cx_title1" colspan="2">2. 上传产品图片</td>
                </tr>
                <tr>
                    <td class="cx_title">上传产品图片：</td>
                    <td class="cx_content"> <input type="submit" id="img_upload" value="浏 览" class="sub" /></td>
                </tr>
                <tr>
                    <td class="cx_title1" colspan="2" style=" padding-left:53px;">图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</td>  
                </tr>
                <tr>
                    <td class="cx_title"></td>
                    <td class="cx_content">
                        <div class="cx_content1" id="show_img">
                            {% for key, img in imgfile %}
                              <dl ><dt><img src="{{ constant('IMG_URL') }}{{ img['path'] }}" width="200" height="200"><dt><dd><a href="javascript:;" onclick="deleteImg(this,{{ img['id'] }});">删除</a></dd></dl>
                            {% endfor %}

                         
                        </div>
                        <input type="hidden" id="imgerrorone-tip" name="img"  value='' data-target="#imgerrorone">
                         <span id="imgerrorone"></span>
                    </td>
                </tr>
           
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                <tr colspan="2">
                    <td class="cx_title1" colspan="2">3. 其他信息</td>
                </tr>
                <tr class="ptype2" style="display: none;">
                    <td class="cx_title">计量单位：</td>
                    <td class="cx_content">
                        <select name="goods_unit2"  id="goods_unit2"class="ptype2">
                            <?php foreach (Mdg\Models\Purchase::$_goods_unit as $key =>$value) {
                            if($key>0){
                                if ( $key == $sell->goods_unit ) {
                                echo "<option value='".$key."' selected='selected'>".$value."</option>";
                                } else {
                                echo "<option value='".$key."'>".$value."</option>";
                                }
                            }

                            }?>
                        </select>
                        <p style="display: none;" id="select-unit"></p>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">价格：</td>
                    <td class="cx_content">
                        <input type="radio" value="0" id="price_type_0" name="price_type" {% if sell.price_type == 0 %}checked='checked'{% endif %} ><label for="price_type_0">区间价格</label>
                        <input type="radio" value="1" id="price_type_1" name="price_type" {% if sell.price_type == 1 %}checked='checked'{% endif %} ><label for="price_type_1">阶梯价格</label>
                        {{ text_field("min_price",'class':'txt ptype1') }}<label class="ptype1">-</label>{{ text_field("max_price",'class':'txt ptype1') }}
                        <select name="goods_unit"  id="goods_unit" class="ptype1">
                            <?php foreach (Mdg\Models\Purchase::$_goods_unit as $key =>$value) {
                               if($key>0){
                                  if ( $key == $sell->goods_unit ) {
                                      echo "<option value='".$key."' selected='selected'>元/".$value."</option>";
                                  } else {
                                      echo "<option value='".$key."'>元/".$value."</option>";
                                  }
                                }

                            }?>
                        </select>
                        <span class="msg-box" style="position:static;" for="max_price"></span>
                        <div class="ptype2" style="display: none;">
                            <div id="step_prices">
                                <div>
                                购买<input type="text" size="5" name="step_quantity[]" onkeyup="check_price_quantity()" data-target="#price-yz5" value="{% if sell_step_price %}{{ sell_step_price[0]['quantity']}}{% endif %}"><span class="g-unit"></span>及以上<input type="text" size="5" name="step_price[]" onkeyup="check_price_quantity()" data-target="#price-yz6" value="{% if sell_step_price %}{{ sell_step_price[0]['price']}}{% endif %}">元&nbsp;<a href="javascript:void(0)" id="add_step">增加</a>
                                <?php foreach ($sell_step_price as $key =>$value) {
                                if($key>0){
                                    echo '<div>购买<input type="text" size="5" name="step_quantity[]" onkeyup="check_price_quantity()" data-target="#price-yz5" value="'.$value['quantity'].'"><span class="g-unit"></span>及以上<input type="text" size="5" name="step_price[]" onkeyup="check_price_quantity()" data-target="#price-yz6" value="'.$value['price'].'">元&nbsp;<a href="javascript:void(0);" class="step_del">删除</a></div>';
                                }
                                }?>
                                </div>
                            </div>
                            <span class="msg-box" id="price-yz4" ></span>
                            <span class="msg-box" id="price-yz5" ></span>
                            <span class="msg-box" id="price-yz6" ></span>
                        </div>
                    </td>
                   
                </tr>
                <tr>
                    <td class="cx_title">供应量：</td>
                    <td class="cx_content">
                        {{ text_field("quantity",'class','txt') }}
                        <span id="cur_unit">{{ goods_unit[sell.goods_unit] }}</span>
                        <span class="msg-box" style="position:static;" for="quantity"></span>
                    </td>
                </tr>
                <tr class="ptype1">
                   <td class="cx_title">起购量：</td>
                    <td class="cx_content">
                        {{ text_field("min_number",'class','txt') }}
                        <span id="cur_unit1">{{ goods_unit[sell.goods_unit] }}</span>
                        <span class="msg-box" style="position:static;" for="min_number"></span>
                    </td>              
                </tr>
                <tr>
                    <td class="cx_title">上市时间：</td>
                    <td class="cx_content">
                    <select id="u69_input" data-label="上市时间1" name="stime">
                            <option value="">请选择</option>
                            <?php foreach (Mdg\Models\Sell::$type as $key =>$value) {
                                if($key ==  $stime ){
                                    echo "<option value='$key'  selected >$value</option>";
                                }else{
                                    echo "<option value='$key' >$value</option>";
                                } 
                            } ?>
                   </select>
                   <select id="u72_input" data-label="上市时间2" name="etime">
                            <option value="">请选择</option>
                            <?php foreach (Mdg\Models\Sell::$type as $key =>$value) {
                              if($key ==  $etime ){
                                  echo "<option value='$key'  selected >$value</option>";
                              }else{
                                  echo "<option value='$key' >$value</option>";
                              } 
                            }?>
                    </select>
                        或
                        <a href="javascript:void(0);" onclick="fun()">全年供应</a>
                        <span class="msg-box" style="position:static;" for="u72_input"></span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">品种：</td>
                    <td class="cx_content">{{ text_field("breed",'class':'txt') }}</td>
                </tr>
                <tr style="display:none">
                    <td class="cx_title">是否热卖：</td>
                    <td class="cx_content">
                        <label for=""><input type="radio" name="is_hot" {% if sell.is_hot %}checked='checked'{% endif %} value='1'>是</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for=""><input type="radio" name="is_hot" {% if !sell.is_hot %}checked='checked'{% endif %} value='0'>否</label>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">规格：</td>
                    <td class="cx_content">{{ text_field("spec",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">详细描述：</td>
                    <td >
                        <div class="cx_content1" >{{  text_area("content",'class':'txt',"value":contents, 'id' : 'editor') }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">供货地:</td>
                  
                    <td class="cx_content">
                        <select name="province" class="selectAreas" id="province">
                            <option value="0" selected>省</option>
                        </select>
                        <select name="city" class="selectAreas" id="city">
                            <option value="0">市</option>
                        </select>
                        <select name="town" class="selectAreas" id="town">
                            <option value="0">区/县</option>
                        </select>
                        <select name="zhen" class="selectAreas" id="zhen">
                            <option value="0">请选择</option>
                        </select>
                        <select name="cun" class="selectAreas" id="cun">
                            <option value="0">请选择</option>
                        </select>
                        
                    </td>
                    <td><span class="msg-box" style="position:static;" for="town"></span></td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td  colspan="2" class="cx_title1">4.供应商信息</td>
                </tr>
                <tr>
                    <td class="cx_title">供应商姓名：</td>
                    <td class="cx_content">{{ text_field("uname",'class':'txt') }}</td>
                </tr>
                <tr>
                    <td class="cx_title">供应商电话：</td>
                    <td class="cx_content">
                      {{ text_field("usermoblie",'class':'txt') }}
                      {% if sell.mobileurl %}<img src='http://yncmdg.b0.upaiyun.com{{sell.mobileurl}}' width="100px" height="30px" >{% else %}无{% endif %}
                    </td>
                </tr>


                <tr>
                     <td class="cx_title">发布平台：</td>
                    <td class="cx_content">
                        {% for key,val in plat %}
                          {% if key != 3 %}
                          <input type="radio" name="plat" value="{{ key }}" {% if key == publish_set %} checked {% endif %}/>{{ val }} 
                          {% endif %}
                        {% endfor %}

                    </td>
                </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
            <td>{{ hidden_field("id") }}</td>
             <input type="hidden" name="pages" value="{{page}}">
             <input type="hidden" name="referer" value="{{referer}}">
            <input type="submit" value="修改" class="sub"/>
        </div>
    </div>
    <!-- main_right 结束  -->
</div>

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?ver=<?= rand(0, 9999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<script type="text/javascript">
    var ue = UE.getEditor('editor');
</script>

<script>
 function fun(){
       var opts = document.getElementById("u69_input");
       var opts1 = document.getElementById("u72_input");
       for(var i=0;i<opts.options.length;i++){
          if("11"==opts.options[i].value){
              opts.options[i].selected = 'selected';     
          }
       }
       for(var i=0;i<opts1.options.length;i++){
          if("123"==opts1.options[i].value){
              opts1.options[i].selected = 'selected';     
          }
       }       
}

$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if curAreas%}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 140}
});
function changefile(){
      return true;
      var id=$("#selectcate").val();
      if(id!=""){
          jQuery.get('/upload/tmpcatefile', {selectval:id,sid:'{{sid}}'}, function(data){
             if(data!=''){

                  $('#show_img').append(data);
                      $('#imgerrorone').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                    $('#imgerrorone-tip').val('1');
             }
          });
      }
}
 function unbindClass(){
     $('.step_del').unbind().click(function(){
         $(this).parent('div').remove();
         if($('#step_prices div').length  < 3) $('#add_step').show();
     });
 }
 function check_price_quantity(){
     var str='';
     var step_quantity =document.getElementsByName("step_quantity[]");
     var step_price =document.getElementsByName("step_price[]");
     if(step_quantity[0]&&step_price[0]&&!step_quantity[1]&&!step_price[1]&&!step_quantity[2]&&!step_price[2]){
         var check1=isquantity(step_quantity[0]);
         var check2=isprice(step_price[0]);
         if(check1&&check2){
             str+="起购量>="+step_quantity[0].value+"价格"+step_price[0].value;
         }
     }
     if(step_quantity[0]&&step_price[0]&&step_quantity[1]&&step_price[1]&&!step_quantity[2]&&!step_price[2]){
         var check3=isquantity(step_quantity[0]);
         var check4=isprice(step_price[0]);
         var check5=isquantity(step_quantity[1]);
         var check6=isprice(step_price[1]);
         if(check3&&check4&&check5&&check6){
             if(parseInt(step_quantity[0].value)>parseInt(step_quantity[1].value)||parseFloat(step_price[0].value)<parseFloat(step_price[1].value)){
                 str='';
             }else{
                 var pirce=parseInt(step_quantity[1].value)-1;
                 str="起购量"+step_quantity[0].value+"~"+pirce+"价格:"+step_price[0].value;
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
             if(parseInt(step_quantity[1].value)>parseInt(step_quantity[2].value)||parseFloat(step_price[1].value)<parseFloat(step_price[2].value)){
                 str='';
             }else{
                 str+="起购量>="+step_quantity[2].value+"价格"+step_price[0].value;
             }
         }
     }
     $("#show_tip").text(str);
 }
jQuery(document).ready(function(){
    if($('#step_prices div').length  < 3) $('#add_step').show();
    else $('#add_step').hide();
    var s_unit = $('#goods_unit2').find('option:selected').text();
    $('.g-unit').text(s_unit);
    $('#select-unit').text(s_unit);
    $('#goods_unit2').change(function(){
        var s_unit = $(this).find('option:selected').text();
        $('.g-unit').text(s_unit);
        $('#select-unit').text(s_unit);
        $('#cur_unit').html(s_unit);
        $('#cur_unit1').html(s_unit);
    });
    {% if sell.price_type == 0 %}
        $('.ptype1').show();
        $('.ptype2').hide();
    {% endif %}
    {% if sell.price_type == 1 %}
        $('.ptype1').hide();
        $('.ptype2').show();
    {% endif %}
    unbindClass();
    $('input[name="price_type"]').click(function(){
        var type = $(this).val();
        if(type == 1){
            $('.ptype1').hide();
            $('.ptype2').show();
        }
        else{
            $('.ptype1').show();
            $('.ptype2').hide();
        }
    });
    $('#add_step').click(function(){
        if($('#step_prices div').length < 3){
            var unit = $('#select-unit').text();
            var html='<div>购买<input type="text" size="5" name="step_quantity[]" onkeyup="check_price_quantity()" data-target="#price-yz5" value=""><span class="g-unit">'+unit+'</span>及以上<input type="text" size="5" name="step_price[]" onkeyup="check_price_quantity()" data-target="#price-yz6" value="">元&nbsp;<a href="javascript:void(0);" class="step_del">删除</a></div>';
            $('#step_prices').append(html);
            unbindClass();
            if($('#step_prices div').length  == 3) $('#add_step').hide();
        }
        else
            $('#add_step').hide();
    });

    var gyInput = $('.gy_step li input');
    inputFb(gyInput);

    $('#goods_unit').change(function() {
        var cur_unit = $(this).find('option:selected').text();
        cur_unit = cur_unit.split('/');
        $('#cur_unit').html(cur_unit[1]);
        $('#cur_unit1').html(cur_unit[1]);
    })

    setTimeout(function(){
          $('#img_upload').uploadify({
              'swf'      : '/uploadify/uploadify.swf',
              'uploader' : '/upload/tmpfile',
              'formData' : {
                  'sid' : '{{ sid }}',
                  'type':'1',
              },
              'buttonClass' : 'upload_btn',
              'buttonText'  : '浏览',
              'multi'       : false,

              onUploadSuccess  : function(file, data, response) {
                  data = $.parseJSON(data);
                  alert(data.msg);
                  if(data.status) {
                      $('#show_img').append(data.html);
                      $('#imgerrorone').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                        $('#imgerrorone-tip').val('1');
                  }
              }
          })
    },10)
});
$(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
     {% if curCat %}
    texts : [{{ curCat }}],
    {% endif %}
    style : {"width" : 140}
});
function deleteImg(obj, id) {
  
    $.getJSON('/manage/sell/delimg', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
            $(obj).parent().parents('dl').slideUp();
             $('#imgerrorone').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">产品图片不能为空</span></span>');
            $('#imgerrorone-tip').val('');
        }
    });
}

// 删除临时图片
function closeImg(obj, id) {
    $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
        if(data.state) {
             $(obj).parents('.imgBox').slideUp();
            $('#imgerrorone').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">产品图片不能为空</span></span>');
            $('#imgerrorone-tip').val('');


        }
    });
}

$("#mysell").validator({
     stopOnError:false,
     focusCleanup:true,
     ignore: ':hidden',
     rules: {
          max_price:[/^\d{1,10}\.*\d{0,2}$/, '请输入正确的价格区间'],
         select: function(element, param, field) {
             return element.value > 0 || '请选择';
         },
         nimei0  : [/^([0-9])+(\.([0-9]){1,2})?$/, '保留两位小数'],
         nimei  : [/^(0\.[0-9][1-9]*|[1-9]\d*(\.\d{1,2})?)$/, '请输入正确的数字'],
         checkNum: function () {
             var quantity = $("#quantity").val();
             var min_number = $('#min_number').val();
             if(parseFloat(quantity) < parseFloat(min_number) &&  parseFloat(quantity) > 0 && parseFloat(min_number) > 0 ) {
                 return '供应量不能小于起购量';
             }else{
                 return true;
             }
             if(parseFloat(min_number) > parseFloat(quantity) && parseFloat(quantity) > 0 && parseFloat(min_number) > 0 ) {
                 return '起购量不能大于供应量';
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
                     if(els[0].value&&els[1].value&&parseInt(els[0].value)>parseInt(els[1].value)){
                         return '购买数量必须大于上一区间';
                     }else if(els[1]&&els[2]){
                         if(els[1]&&els[2]&&parseInt(els[1].value)>parseInt(els[2].value)){
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
                     if(els[0].value&&els[1].value&&parseInt(els[0].value)<parseInt(els[1].value)){
                         return '价格必须小于上一区间';
                     }else if(els[1]&&els[2]){
                         if(els[1]&&els[2]&&parseInt(els[1].value)<parseInt(els[2].value)){
                             return '价格必须小于上一区间';
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
    groups:[{
        fields: 'twocategory autocomplete ',
        callback: function($elements){
            var me = this, count = 0;
            $elements.each(function(){
                if (me.test(this, 'required')) count+=1;
            })
            return count==2 || '分类不能为空';
        },
        target: '#msg'
    },
        {
            fields: "step_price[] step_quantity[]",
            callback: function($elements){
                var me = this, count = 0;
                $elements.each(function(){
                    if (me.test(this, 'required')) count+=1;
                });
                if(count<2){
                    return '请最少填写一组购买数量和价格';
                }else{
                    return '';
                }

            },
            target: "#price-yz4"
        }],
     fields:  {
         title:"required;",
         category:"required;",
         max_price : "required;max_price",
         quantity  : "required;",
         etime : "required;",
         cun  : "required;",
         //breed : "required;",
         search  :"required",
         //spec  :"requi red",
         min_number:'required',
         content  :"required;",
         search  :"required",
         user: "required",
         autocomplete: 'required',
         'step_quantity[]':"购买数量:required;integer[+];checkstep_quantity;length[~8]",
         'step_price[]':"价格:required;nimei0;checkstep_price;length[~8]"
     },    
});
var dialog = null;
function newWindows(id,title,url) {
    var username=$("#user").val();

    if(username==""){
       alert("请输入");
    }else{
        dialog = $.dialog({
        id    : id,
        title : title,
        min   : false,
        max   : false,
        lock  : true,
        width : 600,
        content: 'url:'+url+"/"+username
       });
    }
}
</script>

<style>
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
</style>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
