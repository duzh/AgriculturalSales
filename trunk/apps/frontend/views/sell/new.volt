
<link type="text/css" rel="stylesheet" href="/form/style/validator.css"></link>
<script src="/form/formValidator-4.0.1.js" type="text/javascript" charset="UTF-8"></script>
<script src="/form/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>
<script>

$(document).ready(function(){
$.formValidator.initConfig({formID:"fabu",onError:function(){alert("校验没有通过，具体错误请看错误提示")}});
$("#yhm").formValidator({onShow:"输入1个产品名称，如西瓜",onFocus:"产品名称不能为空",onCorrect:"产品名称可用"}).inputValidator({min:1,onError:"产品名称不能为空"})
    .ajaxValidator({
    type : "get",
    dataType : "text",
    url : "http://mdg.ync365.com/sell/checkname",
    success : function(data){
     if( data == "yes" )
     { 
        return true;
     }
     else
     {
        return false;
     }
        return false;
    },
    error: function(jqXHR, textStatus, errorThrown){
    alert("服务器没有返回数据，可能服务器忙，请重试"+errorThrown);},
    onError : "此产品名已存在，请更换产品名",
    onWait : "正在对产品名进行合法性校验，请稍候..."
  });

  $("#category").formValidator({onShow:"请选择分类",onCorrect:"验证通过"}).inputValidator({min:1,onError: "请选择分类"});  
  $("#min_price").formValidator({onShow:"请输入数字",onCorrect:"输入正确"}).regexValidator({regExp:"num",dataType:"enum",onError:"数字格式不正确"});
  $("#quantity").formValidator({onShow:"请输入数字",onCorrect:"输入正确"}).regexValidator({regExp:"num",dataType:"enum",onError:"数字格式不正确"});
  $("#town").formValidator({onShow:"请选择地区",onFocus:"",onCorrect:"验证通过"}).inputValidator({min:1,onError: "请选择地区"});
  $("#u72_input").formValidator({onShow:"请选择上市时间",onFocus:"",onCorrect:"验证通过"}).inputValidator({min:1,onError: "请选择上市时间"});


});
</script>
<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->

<form action="/sell/create/" id="fabu" method="post" enctype="multipart/form-data" >
<!-- 主体内容开始 -->
<div class="fabu_gy w960 mt20 mb20">
    <h6>免费发布供应信息</h6>
    <div class="gy_box">
        <p class="wx_tip">温馨提示：填写规范的信息能够被更多采购商看到</p>
        <!-- 块 start -->
        <div class="gy_step active">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <span>1</span> <font>产
                    <br />
                    品</font> 
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span> <font>*</font>
                        要发布的产品：
                    </span> <em>{{ text_field("title","id":"yhm") }}</em> <em id="yhmTip"></em>
                </li>
                <li>
                    <span>
                        <font>*</font>
                        所属分类：
                    </span>
                    <em><select name="category" id="category" onchange='change1()'  >
                            <option value="0">请选择</option>
                            <?php if(!empty($cat_list)){ ?>
                            <?php foreach ($cat_list as $cat) { ?>
                            <option value="<?php echo $cat['id']; ?>
                                "  >
                                <?php echo $cat['title']; ?></option>
                            <?php } ?>
                            <?php } ?></select>
                        <select name="categorys" id="categorys" onfocus="sel()">
                            <option value="0">请选择</option>
                            <?php foreach ($cat_list as $cat) { ?>
                            <?php if(!empty($cat['child'])) { ?>
                            <?php foreach ($cat['child'] as $child) { ?>
                            <option value="<?php echo $child['id']; ?>
                                ">
                                <?php echo $child['title']; ?></option>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?></select></em> 

                    <em id="categoryTip" ></em>
                </li>
            </ul>
            <!-- 右侧 end --> </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <div class="gy_step">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <span>2</span>
                <font>上
                    <br />
                    传
                    <br />
                    图
                    <br />
                    片</font> 
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <div class="load_img f-fl">
                <div class="load_btn">
                    <span>上传产品图片</span>
                    <input type="button" value="浏览" />
                    <input type="file" value="浏览" />
                </div>
                <p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p>
                <dl>
                    <dt>
                        <img src="{{ constant('STATIC_URL') }}mdg/images/add_img1-03.png" />
                        <a href="javascript:;">关闭</a>
                    </dt>
                </dl>
                <dl>
                    <dt>
                        <img src="{{ constant('STATIC_URL') }}mdg/images/add_img1-03.png" />
                        <a href="javascript:;">关闭</a>
                    </dt>
                </dl>
                <dl>
                    <dt>
                        <img src="{{ constant('STATIC_URL') }}mdg/images/add_img1-03.png" />
                        <a href="javascript:;">关闭</a>
                    </dt>
                </dl>
                <dl>
                    <dt>
                        <img src="{{ constant('STATIC_URL') }}mdg/images/add_img1-03.png" />
                        <a href="javascript:;">关闭</a>
                    </dt>
                </dl>
            </div>
            <!-- 右侧 end --> </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <div class="gy_step gy_step_input">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <span>3</span>
                <font>
                    其
                    <br />
                    他
                    <br />
                    信
                    <br />
                    息
                </font>
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span>
                        <font>*</font>
                        价格：
                    </span>
                    <em>{{ text_field("min_price", "type" : "numeric","class" : "mr10 w1") }}
                       一{{ text_field("max_price", "type" : "numeric","class" : "mr10 ml10 w1") }}
                        <select name="price_unit">
                            <?php foreach (Mdg\Models\Sell::$type3 as $key =>
                            $value) {
                               echo "
                            <option value='".$key."'>元/".$value."</option>
                            ";
                             }?>
                        </select></em> 
                    <em id="min_priceTip"></em>
                </li>
                <li>
                    <span>
                        <font>*</font>
                        供应量：
                    </span>
                    <em>
                        {{ text_field("quantity", "type" : "numeric") }}
                        <select name="goods_unit">
                            <?php foreach (Mdg\Models\Sell::$type3 as $key =>
                            $value) {
                               echo "
                            <option value='".$key."'>".$value."</option>
                            ";
                             }?>
                        </select>
                    </em>
                    <em id="quantityTip"></em>
                </li>
                <li>
                    <span>
                        <font>*</font>
                        供货地：
                    </span>
                    <em>
                          <select name="province" class="selectAreas" id="province">
                    <option value="0" selected>省</option>
                </select>
                <select name="city" class="selectAreas" id="city">
                    <option value="0">市</option>
                </select>
                <select name="town" class="selectAreas" id="town" data-target="#town">
                    <option value="0">区/县</option>
                </select>
                        {{ text_field("areas", "type" : "numeric","value" : "请输入详细的供货地，具体到镇/乡、村、街道、门牌号","class" : "input1 mt10") }}
                    </em>
                    <em id="provinceTip"></em>
                    <em id="cityTip"></em>
                    <em id="townTip"></em>
                </li>
                <li>
                    <span>
                        <font>*</font>
                        上市时间：
                    </span>
                    <em>
                        <select id="u69_input" data-label="上市时间1" name="stime">
                            <option value="">请选择</option>
                            <?php foreach (Mdg\Models\Sell::$type as $key =>
                            $value) {
                            echo "
                            <option value='".$key."'>".$value."</option>
                            ";}?>
                        </select>
                        <select id="u72_input" data-label="上市时间2" name="etime">
                            <option value="">请选择</option>
                            <?php foreach (Mdg\Models\Sell::$type as $key =>
                            $value) {
                             echo "
                            <option value='".$key."'>".$value."</option>
                            ";}?>
                        </select>
                        <a href="javascript:void(0);" onclick="fun()" >全年供应</a>
                    </em>
                    <em id="u69_inputTip"></em>
                    <em id="u72_inputTip"></em>
                </li>
                <li>
                    <span>品种：</span>
                    <em>{{ text_field("breed") }}</em>
                </li>
                <li>
                    <span>规格：</span>
                    <em>{{ text_field("spec") }}</em>
                </li>
                <li>
                    <span>详细描述：</span>
                    <em>{{  text_area("content", "type" : "numeric") }}</em>
                </li>
            </ul>
            <!-- 右侧 end --> </div>
        <!-- 块 end -->{{ submit_button("确认发布","class":"fabu_btn ") }}</div>
</div>
<!-- 主体内容结束 -->
</form>
<!-- 底部开始 -->
{{ partial('layouts/page_header') }}
<!-- 底部结束 -->
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script>
jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);
    
    $('.load_img dt a').click(function(){
        $(this).parents('dl').slideUp();
    });
});
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
function change1(){
      var selectvalue = $("#category").val();
       $("#categorys  option").each(function() {
        $(this).remove();
       });
      $.ajax({type: "post",url:"http://mdg.ync365.com/sell/showcategory",data:{selectvalue: selectvalue},dataType: "text",
      success: function(data) {

        if(data){
            jQuery(data).appendTo(jQuery("#categorys"));
        }else{
            jQuery("<option value='0'>请选择</option>").appendTo(jQuery("#categorys"));
        }
        
      }
  });
      
}
</script>
<script>
$("#checkuser").bind("click",
    function() {
        var str = $("#user").val();
        if (str=="") {
            alert("请填写查询内容");
        }else {
            $('#showuser').load("http://mdg.ync365.com/sell/showuser", {str:str},function() {
             
            })
        }
});
$(function(){
  $(".gy_step").bind("click",function(){
     $(this).addClass("active").siblings().removeClass("active");
  })
})
</script>
</body>
</html>
<script>
    
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
    defaultParentId : 0,
    style : {"width" : 140}
});

</script>
