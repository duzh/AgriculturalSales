



<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
  <div class="main_right">
    <div class="bt2">新增采购</div>
     <div align="left" style="margin-top:20px;">
         <div><?php if(isset($error)){ echo "总上传:".$suc."失败:".$error."<a href='/manage/purchase/downexcal/'>点击下载错误的信息</a>"; } ?><div>
         <input type="button" value="导入采购信息" class="sub"  onclick="ShowDiv('MyDiv','fade')"/>
         </div>
           <div id="fade" class="black_overlay"></div>
                         <div id="MyDiv" class="white_content">
                         <div class="gb1">导入采购信息<a href="#" onclick="CloseDiv('MyDiv','fade')"></a></div>

                         <div class="tk5" >
                        {{ form("purchase/upexcal/", 'enctype':"multipart/form-data", "method":"post") }}
                          <input type="submit" value="导入文件"     class="sub">
                         <div class="tk7">
                         <input type="file" name='myexcal'value="导入文件" class="sub" onchange="getFileName(this)" style="opacity:0; width:121px; height:31px;cursor: pointer; "/><input id="filename" type="text" style="border:none;width:300px">
                         </div>
                       
                         </div>
                         &nbsp&nbsp&nbsp每次限上传100条
                          <div class="tk6">
                          <input type="submit" value="提交" class="sub">{{ content() }}
                          </div>
                         </form>
                         <div class="tk4">
                        导入模板：<a href="/manage/purchase/download/">下载</a>
                         </div>
           </div>
           {{ form("purchase/create", "method":"post","id":"addpur") }}
            <div class="cx">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">选择会员：</td>
                    <td class="cx_content">
                        <input type="text" class="txt"   id="user" value="{% if users %}{{ users.username }}{% endif %}" />
                        <input type="hidden" class="txt" name="user"  id="userid" value="{% if users %}{{ users.id }}{% endif %}" />
                        <div id="showuser"></div>
                    </td>   
                </tr>
                <tr>
                    <td class="cx_title"></td>
                    <td  class="cx_content">
                        <a href="javascript:;"><input type="button"  onclick="newWindows('user', '选择会员', '/manage/purchase/showuser')" value="搜索"  class="sub" /></a>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">采购商品名称：</td>
                    <td class="cx_content">
                        {{ text_field("title","class":"txt") }}  
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">所属分类：</td>
                    <td class="cx_content">
                        <select name="maxcategory" class="selectCate">
                            <option value="">选择分类</option>
                        </select>
                        <select name="category" class="selectCate">
                            <option value="">选择分类</option>
                        </select>
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">采购数量：</td>
                    <td class="cx_content">
                      {{ text_field("quantity","class":"txt") }}
                        <select name="goods_unit" id="goods_unit" style="width: 120px;">
                            {% for key, value in goods_unit %}
                            {% if key > 0 %}
                               <option value="{{ key }}">{{ value }}</option>
                            {% endif %}
                            {% endfor %}
                        </select>
                        <span class="msg-box" style="position:static;" for="quantity"></span>
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">报价截止时间：</td>
                    <td class="cx_content">
                         {{ text_field("endtime","class":"txt") }}
                    </td>
                </tr>
                
                <tr>
                    <td class="cx_title">采购人姓名：</td>
                    <td class="cx_content">
                        <input type="text" name="username"  class="txt" {% if users %}  value="{{ users.ext.name }}" {% endif %} >
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">采购人电话：</td>
                    <td class="cx_content"> 
                        <input type="text" name="mobile"    class="txt" {% if users %}  value="{{ users.username }}"  {% endif %} >
                    </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">采购人地址：</td>
                    <td class="cx_content">
                    <div class="cx_content1"> 
                        <select name="province" class="selectAreas" id="province">
                            <option value="0" selected>省</option>
                        </select>
                        <select name="city" class="selectAreas" id="city">
                            <option value="0">市</option>
                        </select>
                        <select name="town" class="selectAreas"  id="town">
                            <option value="">区/县</option>
                        </select>
                        <select name="towns" class="selectAreas"  id="town">
                            <option value="">镇</option>
                        </select>
                        <select name="village" class="selectAreas"  id="town">
                            <option value="">村</option>
                        </select>
                        
                        <!-- <input type="text" name="address" value="{% if users %}{{ users.ext.address }}{% endif %}" /> -->
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">规格要求：</td>
                    <td ><div class="cx_content1" >{{ text_area("content") }}</div></td>
                </tr>
         </table>
       
    </div> 
    <div align="center" style="margin-top:20px;">
         <input type="submit" value="添加" class="sub"/>
         </div>
  </div>
  <!-- main_right 结束  --> 
  
</div>
</form>
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
<script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
document.getElementById(show_div).style.zIndex = 100;
document.getElementById(bg_div).style.zIndex = 90;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
// bgdiv.style.height = $(document).height();
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
</script>
<script>
$(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    style : {"width" : 140}
});
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if areas_name %}
    texts : [{{ areas_name }}],
    {% endif %}
    style : {"width" : 140}
});
$(function(){
    $("#endtime").datepicker();
});
$("#addpur").validator({
     fields:  {
         title:"required;",
         category:"required;",
         quantity: "required;digits",
         content : "required;",
         endtime : "required;",
         village : "required;",
         address : "required;",
         username  :"required",
         mobile:'required',

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
function reAsk(sStr)
{
    return confirm(sStr);
}
function getFileName(o)
{
    var pos=o.value.lastIndexOf("\\");
    var filename=o.value.substring(pos+1);//文件名
    $("#filename").val(filename);
    
}
function GetFileExt(o)
{
    return o.value.replace(/.+\./,"");
}
</script>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

