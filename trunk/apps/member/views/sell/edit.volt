<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/jquery.powertip.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery.powertip.min.js"></script>
<style>
#powerTip{ padding: 10px; max-width:242px; color:#999; font-family:'宋体'; line-height:24px; background:#fff2d8; white-space:normal; word-wrap:break-word;}
#powerTip.sw-alt:before, #powerTip.se-alt:before, #powerTip.s:before{ border-bottom:10px solid #fff2d8;}
#powerTip.nw-alt:before, #powerTip.ne-alt:before, #powerTip.n:before{ border-top:10px solid #fff2d8;}
</style>
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
       <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}编辑供应</span>
        </div>
        <!-- 左侧 -->
        
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">

            <div class="post-offer f-oh pb30">
                <div class="title f-oh">
                    <span>编辑供应</span>
                </div>
                {{ form("sell/save", "method":"post","id":"editsell") }}
                    <div class="m-title">基本信息</div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>要发布的产品：
                        </font>
                        <div class="inputBox inputBox1 f-pr">
                            <input name="title"  class="input1" type="text" value="{{ sell.title }}" >
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>产品分类：
                        </font>
                        <div class="selectBox selectBox1 f-pr">
                            <select name="maxcategory" class="select1 selectcate " id="maxselectcate">
                                <option value="">选择分类</option>
                            </select>
                            <select name="category" class="select1 selectcate " onchange="changecate()" id="selectcate" >
                                <option value="">选择分类</option>
                            </select>
                             {% if user_info['lwtt'] %} <a style="display:inline-block; line-height:40px; color:#4471b8; min-width:143px; position:absolute; left:536px; top:0;" class="south-west-alt" title="分类中默认只会显示在产地服务站认证时所填写的分类，如需修改可联系自己的服务工程师；当有整合的可信农场成功发布供应信息后，此处也会新增该供应所属分类。" href="javascript:;">为什么有些分类没有显示</a>{% endif %}
                        </div>
                    </div>
                    {% if user_info['maxcategory_id']  %}
                        {% if user_info['lwtt'] %}
                        <!-- add 2015.10.28 盟商 代码开始 -->
                        <div class="message clearfix">
                            <font>
                                <i>*</i>来自哪个可信农场：
                            </font>
                            <div class="wms-radioBox clearfix f-pr" id="showfram">
                            {% if userlwtt %}
                                {% for key,val in user_info['lwtt'] %}
                                    <div class="wms-box f-oh">
                                        <label class="f-db f-fl">
                                            <input type="checkbox" name="fromfarm[]" {% for k,v in userlwtt %}{% if val['credit_id']==
                                       v['farm_id'] %}checked='checked'{% endif %}{% endfor  %} value="{{val['credit_id']}}">
                                            <em>{{val['farm_name']}}农场</em>
                                        </label>
                                        <div class="wms-icon f-db icon1 f-fl">可信农场</div>
                                    </div>
                                {% endfor %}
                            {% else %}
                              无
                            {% endif %}
                            </div>
                        </div>
                        {% endif %}
                    {% endif %}
                    <div class="line"></div>
                    <div class="m-title">产品图片</div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>上传产品照片：
                        </font>
                        <div class="loadBox f-pr">
                            <div class="loadBtn">
                                <input class="btn1" type="button" value="+上传图片">
                                <input class="btn2" type="file"  id="img_upload"  >
                            </div>
                            <input name="photo" id="photo" type="hidden" value="{% if is_img %} {{is_img}} {% endif %}">
                            <div class="tips">图片大小不超过2M，支持jpg、png格式（使用高质量图片，可提高成交的机会）图片规格400*400</div>
                        </div>
                    </div>
                    <!-- 上传成功后的图片位置 -->
                    <div class="imgBox f-oh" id="show_img" >
                         {% for key, img in simages %}
                            <div class="imgs f-fl f-pr">
                                <a class="close-btn" href="javascript:;" onclick="deleteImg(this, {{ img.id }});" ></a>
                                <img src="{{ constant('IMG_URL') }}{{ img.path }}" height="120" width="120" alt="">
                            </div>
                         {% endfor %}    
                    </div>
                    <div class="line"></div>
                    <div class="m-title">详细信息</div>
                    <div class="message clearfix " >
                        <font>
                            <i>*</i>计量单位：
                        </font>
                        <div class="inputBox inputBox2 f-pr clearfix" >
                         <select class="select2"  name="ladder_goods_unit" id="ladder_goods_unit" onchange="ladderupinfo()" style="margin-left:4px; _display:inline;" >
                                {% for key, val in goods_unit %}
                                    {% if key> 0 %}
                                    <option value="{{ key }}" {% if( key == sell.goods_unit) %}selected="selected"{% endif %} >{{ val }}</option>
                                    {% endif %}
                                {% endfor %}
                         </select>
                        </div>
                    </div>
                     <div class="message clearfix">
                            <font>
                                <i>*</i>价格：
                            </font>
                            <div class="inputBox inputBox2 f-pr clearfix">
                                <div class="price-tabs clearfix">
                                    <label class="f-db f-fl f-oh">
                                        <input class="f-fl" type="radio" value="0" id="price_type_0" name="price_type" 
                                        {% if sell.price_type==0 %} checked {% endif %} >
                                        <em class="f-db f-fl">区间价格</em>
                                    </label>
                                    <label class="f-db f-fl f-oh">
                                        <input class="f-fl" type="radio" value="1" id="price_type_1" name="price_type" 
                                        {% if sell.price_type==1 %} checked {% endif %}  >
                                        <em class="f-db f-fl">阶梯价格</em>
                                    </label>
                                    <a style="display:inline-block; line-height:40px; color:#4471b8; min-width:143px; position:absolute; left:186px; top:0;" class="south-west-alt" title="区间价格：适合价格调整较频繁的产品，如粮食、蔬菜、水果<br />阶梯价格：适合价格较平稳的产品，如有机产品、礼盒装" href="javascript:;">如何选择价格形式</a>
                                </div>
                                <!--区间价格-->
                                <div class="inputBox inputBox2 f-pr clearfix ptype1" {% if sell.price_type==0 %}style="display:block"{% else %}style="display:none"{% endif %} >
                                   <input name="min_price" data-target="#price-yz1" class="input2 ptype1"  value="{{ sell.min_price }}" type="text" id="min_price" >
                                   <i class="heng ptype1">-</i>
                                   <input name="max_price" data-target="#price-yz2" class="input2 ptype1" value="{{ sell.max_price }}" type="text" id='max_price' ><i class="range f-fl" >元／<b class="add-unitChange">公斤</b></i>
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
                                                                <i><b class="add-unitChange">公斤</b>及以上</i>
                                                                <em class="jt-jgYz1" id="jt-price-yz<?php echo $i1++ ?>"></em>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="n-box f-pr">
                                                                <input type="text" name="step_price[]" value="{{val.price}}"   onkeyup="check_price_quantity()" data-target="#jt-price-yz<?php echo $i++ ?>" >
                                                                <i>元／<b class="add-unitChange">公斤</b></i>{% if key>0 %}<a href="javascript:;" onclick="delprice(this)">删除</a>{% endif %}
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
                                    <!-- 预览 -->
                                    <div class="pc-right f-fl f-oh">
                                        <div class="r-title f-fl">预<br />览</div>
                                        <!-- 数据为空时显示 -->
                                        <div class="r-list f-fl" id="showlist" {% if sellstepprice %} style="display:none"{% else %} style="display:block" {% endif %}>
                                            <p>可根据买家采购的不同数量设置不同价格</p>
                                        </div>
                                        <!-- 有数据时显示样式 -->
                                        <div class="r-list f-fl" id="showlist1"  {% if sellstepprice %} style="display:block"{% else %} style="display:none" {% endif %} >
                                        <table cellpadding="0" cellspacing="0" width="276">
                                                <tr height="30">
                                                    <th width="50%">起购量（<b class="add-unitChange">公斤</b>）</th>
                                                    <th width="50%">价格</th>
                                                </tr>
                                                <?php $checkprice=Mdg\Models\SellStepPrice::getprice($sell->id,0);?>
                                                {% for key,val in checkprice %}
                                                <tr height="30">
                                                    <td align="center">{{val["quantity"]}}</td>
                                                    <td align="center">
                                                        <i>{{val["price"]}}</i>元/<b class="add-unitChange">公斤</b>
                                                    </td>
                                                </tr>
                                                {% endfor %}
                                        </table>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                     </div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>供应量：
                        </font>
                        <div class="inputBox inputBox2 f-pr clearfix">
                            <input  class="input1 f-fl" type="text" name="quantity" value="{{ quantity }}" id="quantity">
                            <i class="range f-fl" id="quantity_1"><label>元/公斤</label>（0为不限）</i>
                        </div>
                    </div>
                    <div class="message clearfix" id="min_number_tip" {% if sellstepprices %} style="display:none"{% else %} style="display:block" {% endif %} >
                        <font>
                            <i>*</i>起购量：
                        </font>
                        <div class="inputBox inputBox2 f-pr clearfix">
                            <input name="min_number" class="input1 f-fl" type="text"  id="min_number" value="{{ sell.min_number }}" >
                            <i class="range f-fl" id="min_number1">元/公斤</i>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>供货地：
                        </font>
                        <div class="selectBox selectBox1 f-pr">
                            <select name="province_id" class="select1 mb10 selectAreas">
                                <option value="">省</option>
                            </select>
                            <select name="city_id" class="select1 mb10 selectAreas">
                                <option value="">市</option>
                            </select>
                            <select class="select1 mb10 selectAreas">
                                <option value="">区／县</option>
                            </select>
                            <select class="select1 mb10 selectAreas">
                                <option value="">街道</option>
                            </select>
                            <select class="select1 selectAreas " name="areas"  data-rule="select" >
                                <option value="0">办事处</option>
                            </select>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>上市时间：
                        </font>
                        <div class="inputBox inputBox3 f-pr clearfix">
                            <select class="select2" name="stime" id="s_stime" data-rule="select">
                                <option value='0'>请选择</option>
                                {% for key, val in time_type %}
                                <option value='{{ key }}' {% if( key == sell.stime) %}selected="selected"{% endif %} >{{ val }}</option>
                                {% endfor %}
                            </select>
                            <i class="heng">-</i>
                           <select class="select2" name='etime' id="s_etime" data-rule="select"  onchange='savetime()'>
                                <option value='0'>请选择</option>
                                {% for key, val in time_type %}
                                <option value='{{ key }}' {% if( key == sell.etime) %}selected="selected"{% endif %} >{{ val }}</option>
                                {% endfor %}
                            </select>
                            <a class="link1" href="javascript:selectTime();">全年供应</a>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>品种：</font>
                        <div class="inputBox inputBox1 f-pr">
                            <input class="input1" type="text" name='breed' value="{{ sell.breed }}" >
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>规格：</font>
                        <div class="inputBox f-pr clearfix" style="width:350px;">
                            <input class="input1 f-fl" type="text" name="spec"  value="{{ sell.spec }}" >
                            <i class="range f-fl">(如50KG/袋)</i>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>
                            <i>*</i>详细信息：
                        </font>
                        <div class="areaBox f-pr">
                             <textarea name="content" id='editor' >{{ sell.scontent.content }}</textarea> 
                               <input type="text" data-target="#content-yz"  name="contenthidden" id='contenthidden' style="opacity:0;filter:alpha(opacity:0);"{% if sell.scontent.content %} value='1' {% else %}  value=''{% endif %} >
                              <i id="content-yz"></i>
                        </div>
                    </div>
                    {% if plat and plat!='' %}
                    <div class="message clearfix">
                        <font>
                            <i>*</i>发布平台：
                        </font>
                        <div class="inputBox inputBox4 f-pr clearfix">
                            {% for key,val in plat %}
                                {% if key == 1 or key == 2 or key == 0 %}
                                <label class="f-db f-fl f-oh" style="margin-right:30px;">
                                    <input style="margin-right:10px; margin-top:14px;" class="f-fl" type="radio" name="plat" value="{{ key }}" {% if key == sell.publish_place %} checked {% endif %}/>
                                    <i style="line-height:40px;" class="f-db f-fl">{{ val }}</i> 
                                </label>
                                {% endif %}
                            {% endfor %}
                            
                        </div>
                    </div>
                    {% endif %}
                     {% if !user_info['lwtt'] %}
                    <div class="message clearfix">
                        <font>
                            <i>*</i>验证码：
                        </font>
                        <div class="inputBox inputBox4 f-pr clearfix">
                            <input name="img_yz" class="input1 f-fl" type="text"   >
                            <img class="f-fl" src="/member/code/index" alt="" id='codeimg' onclick="javascript:this.src='/member/code/index?tm='+Math.random();" >
                            <a class="link2" href="javascript:" onclick="fun()" >看不清楚换一张</a>
                            
                        </div>
                    </div>
                    {% endif %}
                    <div class="btns f-oh">
                            <input type="hidden" name="sid" value="{{ sid}}" />
                            <input type="hidden" name="sellid" value="{{ sell.id }}" />
                            <input class="post-btn f-fl" type="submit" value="确认修改">
                            <a href="{{url}}" class="back-btn f-db f-fl">取消并返回</a>
                     </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}
<script type="text/javascript">
    var ue = UE.getEditor('editor');

    ue.on('blur',function(){

        if(UE.getEditor('editor').hasContents()){

            var info = UE.getEditor('editor').getPlainTxt();
            var reg = /<img.*?>/;
            if(reg.test(info)){
                 $('#contenthidden').val(1);
                $('#content-yz').html('');
            }else{
                $('#contenthidden').val(1);
                $('#content-yz').html('<span class="msg-box n-right" style="" for="editor"><span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">描述必须包括文字和图片描述</span></span></span>'); 
            };  
        }else{
            $('#contenthidden').val('');
            $('#content-yz').html('<span class="msg-box n-right" style="" for="editor"><span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">详细信息不能为空</span></span></span>');
            
        }
    })
</script>
<script>
function upinfo(){
    var unit = $("#goods_unit  option:selected").text();
     unit = unit.substr(2);
    $("#quantity_1 label").html(unit);
    $("#min_number1").html(unit);

}
function ladderupinfo(){
    var unit = $("#ladder_goods_unit  option:selected").text();
    $("#quantity_1 label").html(unit);
    $("#min_number1").html(unit);
    $('.add-unitChange').html(unit);
}
jQuery(document).ready(function(){
    var unit = $("#goods_unit  option:selected").text();
     unit = unit.substr(2);
    $("#quantity_1 label").html(unit);
    $("#min_number1").html(unit);
    $(".gy_step").bind("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    })
    $('input[name="price_type"]').click(function(){
        var type = $(this).val();
        if(type == 1){
            $('.ptype1').hide();
            $('.ptype2').show();
            $("#min_number_tip").hide();
        }
        else{
            $('.ptype1').show();
            $('.ptype2').hide();
            $("#min_number_tip").show();
        }
        if($('input[name="min_number"]').val() == '0.00')
            $('input[name="min_number"]').val('');
    });
    $('#goods_unit').change(function() {
        var cur_unit = $(this).find('option:selected').text();
        cur_unit = cur_unit.split('/');
        $('#cur_unit').html(cur_unit[1]);
        $('#cur_unit1').html(cur_unit[1]);
    })
    {% if user_info["lwttstate"] %}
    $(".selectcate").ld({ajaxOptions : {"url" : "/ajax/getlwttcate"},
         defaultParentId : 0,
         texts : [{{ textCate }}],
         style : {"width" : 250}
    });
    {% else %}
    $(".selectcate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
         defaultParentId : 0,
         texts : [{{ textCate }}],
         style : {"width" : 250}
    });
    {% endif %}
    $('#editsell').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9]){1,2})?$/, '小数点后最多支持2位。如：“1.20”、“2.55”'],
            intqutaily:[/^[1-9]\d*$/,'请输入正整数，如：“1”、“10”'],
            checkNum: function () {
                    var quantity = $("#quantity").val();
                    var price_type=$('input[name="price_type"]:checked').val();
                    if(price_type==1){
                        $('input[name="step_quantity[]"]').each(function(i){
                            if(i == 0){
                                min_number = $(this).val();
                            }
//                            if((i+1) == $('input[name="step_quantity[]"]').length){
//                                max_number = $(this).val();
//                            }
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
            },
            checkImg: function () {
                var info = UE.getEditor('editor').getPlainTxt();
                var reg = /<img.*?>/;
                if(reg.test(info)){
                    return true;
                   
                }else{
                    return  '描述必须包括文字和图片描述';
                };  
            }
        },
        ignore: ':hidden',
        fields: {
          'title':'发布产品:required;length_name;length[5~30]',
          'autocomplete': 'required',
          'fromfarm[]':'checked',
          'breed':"品种:length_name;length[5~30]",
          'spec':"规格:length_name;length[5~30]",
          'min_price': '价格:required;nimei;checkMax[cz, max_price]',
          'max_price': '价格:required;nimei;checkMax[scgt, min_price]',
          'category':'商品类型:required',
          'photo':'产品照片:required;',
          'quantity':'供应量:required;float[*];nimei;length[~8];checkNum',
          'min_number':'起购量:required;float[+];nimei;length[~8];checkNum',
          'contenthidden':'详细信息:required;checkImg',
          'img_yz':"验证码:required;remote[/member/code/checkvcoed ];",
          'step_quantity[]':"购买数量:required;intqutaily;checkstep_quantity;checkNum;length[~8]",
          'step_price[]':"产品单价:required;nimei;checkstep_price;length[~8]"
        }
    });
    setTimeout(function(){
          $('#img_upload').uploadify({
                'swf'      : '/uploadify/uploadify.swf',
                'uploader' : '/upload/tmpfile',
                'fileSizeLimit' : '2MB',
                'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
                'formData' : {
                    'sid' : '{{ sid }}',
                    'type' : '1'
                },
                'buttonClass' : 'upload_btn',
                'buttonText'  : '浏览',
                'multi'       : false,
                onDialogOpen : function() {
                    $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
                },
                onUploadSuccess  : function(file, data, response) {
                    data = $.parseJSON(data);
                    alert(data.msg);
                    var count=$("#photo").val();
                    var newcount=parseInt(count)+parseInt(1);
                    if(data.status) {
                        $('#show_img').append(data.html);                        
                          if(count>0){
                               $('#photo').val(newcount);
                          }else{
                               $('#photo').val(1);
                          }
                    }  
                }
          })
    },10);
});
// 地区联动
$(".selectAreas").ld({ajaxOptions:{"url":"/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 250}
});
function fun(){
    var tm=Math.random();
    $("#codeimg").attr("src","/member/code/index?tm="+tm);
}

// 全年供应
function selectTime() {
    $('#s_stime').val(11);
    $('#s_etime').val(123);
    $("span[for='s_etime']").html('');
}
// 删除图片
function closeImg(obj, id) {
    $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
             $(obj).parent().slideUp('200');
             var count=$("#photo").val();
             if(count==1){
                 $('#photo').val(''); 
             }else{
                 $("#photo").val(count-1);
             }
        
        }
    });
}
function deleteImg(obj, id) {
    $.getJSON('/member/sell/delimg', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
             $(obj).parent().slideUp('200');
             var count=$("#photo").val();
             if(count==1){
                 $('#photo').val(''); 
             }else{
                if(count==''){
                    $("#photo").val(1);
                }else{
                    $("#photo").val(count-1);
                }
             }
        }
    });
}
function changecate(){
    var maxcate=$("#maxselectcate").val();
    var cate=$("#selectcate").val();
    $.get('/member/sell/checkfarm', {maxcate:maxcate,cate:cate}, function(data){
      $("#showfram").html(data);
    });
   
}
$('.south-west-alt').powerTip({
    placement: 's',
    smartPlacement: true
});
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
          var unit = $("#ladder_goods_unit  option:selected").text();
          var str='';//<p>可根据买家采购的不同数量设置不同价格</p>
          var step_quantity =document.getElementsByName("step_quantity[]");
          var step_price =document.getElementsByName("step_price[]");
          if(step_quantity[0]&&step_price[0]&&!step_quantity[1]&&!step_price[1]&&!step_quantity[2]&&!step_price[2]){
               var check1=isquantity(step_quantity[0]);
               var check2=isprice(step_price[0]);
               if(check1&&check2){
                   str='<tr height="30"><td align="center">≥'+step_quantity[0].value+'</td><td align="center"><i>'+step_price[0].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr>'
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
                         if(step_quantity[0].value==1&&step_quantity[1].value==2){
                             str='<tr height="30"><td align="center">'+step_quantity[0].value+'</td><td align="center"><i>'+step_price[0].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr><tr height="30"><td align="center">'+step_quantity[1].value+'</td><td align="center"><i>'+step_price[1].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr>';
                         }else{
                              var pirce=parseInt(step_quantity[1].value)-1;
                              str='<tr height="30"><td align="center">'+step_quantity[0].value+"~"+pirce+'</td><td align="center"><i>'+step_price[0].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr><tr height="30"><td align="center">≥'+step_quantity[1].value+'</td><td align="center"><i>'+step_price[1].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr>';
                         }
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
                         if(step_quantity[0].value==1&&step_quantity[1].value==2&&step_quantity[2].value==3){
                             str='<tr height="30"><td align="center">'+step_quantity[0].value+'</td><td align="center"><i>'+step_price[0].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr><tr height="30"><td align="center">'+step_quantity[1].value+'</td><td align="center"><i>'+step_price[1].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr><tr height="30"><td align="center">≥'+step_quantity[2].value+'</td><td align="center"><i>'+step_price[2].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr>';
                         }
                         else{
                              var pirce=parseInt(step_quantity[1].value)-1;
                              var pirce2=parseInt(step_quantity[2].value)-1;
                              str='<tr height="30"><td align="center">'+step_quantity[0].value+"~"+pirce+'</td><td align="center"><i>'+step_price[0].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr><tr height="30"><td align="center">'+step_quantity[1].value+"~"+pirce+'</td><td align="center"><i>'+step_price[1].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr><tr height="30"><td align="center">≥'+step_quantity[2].value+'</td><td align="center"><i>'+step_price[2].value+'</i>元/<b class="add-unitChange">'+ unit +'</b></td></tr>';
                         }
                      
                   }
               } 
          }
          if(str!=""){
             $("#showlist").hide();
             
             var tablestr='<table cellpadding="0" cellspacing="0" width="276"><tr height="30"><th width="50%">起购量（<b class="add-unitChange">'+ unit +'</b>）</th><th width="50%">价格</th></tr>'+str+'</table>';
              
             $("#showlist1").html(tablestr);
             $("#showlist1").show();
          }else{
             $("#showlist").show();
             $("#showlist1").hide();
          }
         
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
function delprice(object){
    $(object).parents('tr').remove();
    check_price_quantity();
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
            var str = '<tr height="44"><td><div class="n-box f-pr"><i>购买</i><input data-target="#jt-price-yz' + i + '" type="text" name="step_quantity[]" onkeyup="check_price_quantity()" ><i><b class="add-unitChange">'+ unit +'</b>及以上</i><em class="jt-jgYz1" id="jt-price-yz' + i + '"></em></div></td><td><div class="n-box f-pr"><input data-target="#jt-price-yz' + (++i) + '" type="text" name="step_price[]" onkeyup="check_price_quantity()" ><i>元／<b class="add-unitChange">'+ unit +'</b></i><a href="javascript:;" onclick="delprice(this)">删除</a><em class="jt-jgYz2" id="jt-price-yz' + (i++) + '"></em></div></td></tr>';
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
</script>
<style>
    .uploadify{ z-index:2; opacity:0; filter:alpha(opacity:0);}
    #price-yz1 .n-ok .n-msg{ position:absolute; left:90px;}
    #price-yz2 .n-ok .n-msg{ position:absolute; left:214px;}
    #price-yz1 .n-error .n-msg{}
    #price-yz2 .n-error .n-msg{ position:absolute; left:124px;}
    .jt-jgYz1 .n-ok .n-msg{ position:absolute; left:108px; top:15px;}
    .jt-jgYz2 .n-ok .n-msg{ position:absolute; left:84px; top:15px;}
    .jt-jgYz1 .n-error .n-msg{ position:absolute; bottom:0; left:15px;}
    .jt-jgYz2 .n-error .n-msg{ position:absolute; left:19px; bottom:0;}
    .post-offer .message .pc-left .n-box{ padding-bottom:8px;}
    b{ font-weight: normal;}
</style>
