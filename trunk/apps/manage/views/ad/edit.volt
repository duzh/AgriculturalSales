{{ form("ad/save", "method":"post","id":"newad") }}
<table width="100%">
    <tr>
        <td align="left">{{ link_to("/ad", "返回") }}</td>
    </tr>
</table>
{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />

<div class="main">
  <div class="main_right">
    <div class="bt2">广告位修改</div>
    <div class="cx">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">广告位说明：</td>
                    <td class="cx_content">
                      {{ad.addesc}}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">广告链接：</td>
                    <td class="cx_content">
                      <input type="text" name="adsrc" value="{{ adsrc }}" style="width:1000px" >
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">广告位名称：</td>
                    <td class="cx_content">
                       <input type="text" name="adtitle" value="{{ ad.adtitle }}" />   
                     </td>
                </tr>
                {% if ad.type == 2 or ad.type == 0 %}
                <tr>
                    <td class="cx_title">是否显示：</td>
                    <td class="cx_content">
                       <input type="radio" name="isshwow" value="0" {% if isshwow== 0 %}checked{% endif %}/>否 
                       <input type="radio" name="isshwow" value="1" {% if isshwow== 1 %}checked{% endif %}/>是
                    </td>
                </tr>
                {% endif %}
                {% if ad.type!=3 %}
                <tr>
                    <td class="cx_title"  valign="top">广告位图片：</td>
                    <td class="cx_content">  
                         <div class="gy_step">
                                <div class="load_img f-fl">
                                    <div class="load_btn">
                                        <input type="file" value="浏览" id="img_upload"></div>
                                        {% if ad.type == 0 %}
                                        <font color="red">图片尺寸 宽: 770px;高: 380px;</font>
                                        {% elseif ad.type == 1 and ad.position != 9 %}
                                        <font color="red">图片尺寸 宽: 200px;高: 200px;</font>
                                        {% elseif ad.type == 2 %}
                                        <font color="red">图片尺寸 宽: 400px;高: 200px;</font>
                                        {% elseif ad.position == 9 %}
                                        <font color="red">图片尺寸 宽: 400px;高: 200px;</font>                                        
                                        {% endif %}
                                    <div>
                                    <img src="{{ constant('IMG_URL') }}{{ ad.imgpath }}" id="img_show" width="100" height="100"></div>
                                </div>
                        </div>
                     </td>
                </tr>
                {% endif %}

                
         </table>
         
    </div> 
    <div align="center" style="margin-top:20px;">
          <input type="hidden" name="id" value="{{ ad.id }}" />
          <input type="hidden" name="sid" value="{{ sid }}" />
          <input type="hidden" name="type" value="{{ type }}" />
         <input type="submit" value="确认修改" class="sub"/>
    </div>
  </div>
  <!-- main_right 结束  --> 
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script>
jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);
     setTimeout(function(){
          $('#img_upload').uploadify({
              'swf'      : '/uploadify/uploadify.swf',
              'uploader' : '/upload/tmpfile',
              'multi '   : false,
              'formData' : {
                  'sid'  : '{{ sid }}',
                  'type' : "4",
                  'adtype'  :'{{ ad.type }}',
                  'position':'{{ ad.position }}'
              },
              'buttonClass' : 'sub',
              'buttonText'  : '浏览',
              'multi'       : false,

              onUploadSuccess  : function(file, data, response) {
                  data = $.parseJSON(data);
                  alert(data.msg);
                  if(data.status) {
                      $('#img_show').attr('src', data.path);
                  }
              }
          })
      },10)
});
$("#newad").validator({ 
     fields:  {
         adsrc:"required;length[~500];",
         adtitle:"required;"
     },
    
});
function filtelink(){
var link=$('#website_link').val();
link=link.replace(/(http:\/\/)|(<)|(>)|({)|(})|(《)|(》)/g,'');
$('#website_link').val(link);
}
</script>
<style>
.upload_btn {width: 150px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f; margin-left:75px;}
</style>
</div>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

