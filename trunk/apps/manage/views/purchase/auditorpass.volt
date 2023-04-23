{{ form("purchase/save", "method":"post","id":"addpur") }}

{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
{% if purchase.state == 0 %}
<input type="button" value="审核通过" class="btn" onclick="ShowDiv('MyDiv','fade')"/>
  <input type="button" value="审核未通过" class="btn" onclick="ShowDiv1('MyDiv1','fade1')"/>
{% endif %}
<div class="main">
    <div class="main_right">
        <div class="bt2">审核采购信息</div>
        <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">采购商品名称：</td>
                    <td class="cx_content">{{purchase.title}}</td>
                </tr>
                <tr>
                    <td class="cx_title">采购数量：</td>
                    <td class="cx_content">
                        {{ purchase.quantity > 0 ? purchase.quantity : '不限/' }}{{ goods_unit[purchase.goods_unit] }}
                       
                        <span class="msg-box" style="position:static;" for="quantity"></span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">报价截止时间：</td>
                    <td class="cx_content">{{ date('Y/m/d H:i:s', purchase.endtime) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">采购人姓名：</td>
                    <td class="cx_content">
                       {{ purchase.username }}</td>
                </tr>
                <tr>
                    <td class="cx_title">采购人电话：</td>
                    <td class="cx_content">
                        {{ purchase.mobile }}</td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">采购人地址：</td>
                    <td class="cx_content">
                    {{ purchase.address }}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">规格要求：</td>
                    <td >
                       {% if purchase.pcontent %}{{ purchase.pcontent.content }}{% endif %}
                    </td>
                </tr>
            </table>
        </div>
       
    </div>
    <!-- main_right 结束  -->

</div>
</form>


  <!-- 审核通过弹框开始  -->
  <div id="fade" class="black_overlay"></div>
  
  <form action="/manage/purchase/auditorpasspro"   method="post" id='via' >
  <div id="MyDiv" class="white_content2">
    <div class="gb">
      确定审核通过
      <a href="#" onclick="CloseDiv('MyDiv','fade')"></a>
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable></lable>
          <div>
            <!-- <input name="url_dns" type="text" id='url_dns'  data-target="#dns" />
            .mdg.ync365.com  -->
            <span id='dns'></span>
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input name="" type="submit" value="确定" class="btn3"/>
            <input type="hidden" name="pages" value="{{ pages}}" />
            <input type="hidden" name='id' value='{{ purchase.id}}'> <!-- # 隐藏ID -->
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv('MyDiv','fade')" />
          </div>
        </li>
      </ul>
    </div>
    </form>

  </div>
  <!-- 审核通过弹框结束  -->
  <!-- 审核未通过弹框开始  -->
  <div id="fade1" class="black_overlay"></div>
  <form action="/manage/purchase/fall"   method="post"  >
  <div id="MyDiv1" class="white_content2">
    <div class="gb">
      确定审核未通过
      <a href="#" onclick="CloseDiv1('MyDiv1','fade1')"></a>
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable>请输入拒绝理由：</lable>
          <div>
            <input name="reject" type="text"  value=''  data-rule="required;"/>
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input type="submit" value="确定" class="btn3"/>
            <input type="hidden" name="pages" value="{{ pages}}" />
            <input type="hidden" name='id' value='{{ purchase.id}}'> <!-- # 隐藏ID -->
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv1('MyDiv1','fade1')"/>
          </div>
        </li>
      </ul>
    </div>
    </form>
  </div>
  <!-- 审核未通过弹框结束  -->


<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script>
$(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    {% if curCate %}
    texts : [{{ curCate }}],
    {% endif %}
    style : {"width" : 140}
});
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
    defaultParentId : 0,
    {% if curAreas %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 140}
});
$(function(){
    $("#endtime").datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });
});
$("#addpur").validator({
     rules: {
          
     },
     fields:  {
         title:"required;",
         category:"required;",
         quantity: "required;",
         content : "required;",
         endtime  : "required;",
         town : "required;",
         address : "required;",
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
function closeDialog(){
    dialog.close();
}

</script>

<script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
//弹出隐藏层
function ShowDiv1(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv1(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
</script>

<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>