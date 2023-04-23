{{ form("/friendlink/savelink", "method":"post","id":"savelink") }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<style>
.uploadify {
    margin-bottom: 1em;
    opacity: 1;
    position: relative;
    z-index: 999;
}
</style>
<div class="main_right">
    <div class="bt2">添加连接</div>
</div>
   <div id="test2_1" class="tablist block">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">网站名称： </td>
                    <td class="cx_content">
                       <input type='text' style="width:190px" id="website_name" name="website_name" value=""/><span style='color:red;'>*</span>
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">网址： </td>
                    <td class="cx_content">
                     http:// <input type='text' style="width:190px" name="website_link" value="" id='website_link' onblur='filtelink()'/><span style='color:red;'>*</span>
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">logo： </td>
                    <td class="cx_content paddingtd">
                        <div class="loadImg-box">
                            <div class="file-btn">
                                <input class="btn2" type="file" id='ent_logo_pic'/>
                                <input id="ent_logo_pic_hide" style="width:20px;opacity:0;filter:alpha(opacity:0);" type="hidden" value="" />
                                <i id='ent_logo_pic_tip' style="position:absolute; left:176px; top:-6px;"></i>  

                            </div>
                            图片规格88  * 31 支持*.jpg;*.png;*.jpeg;*.bmp;*.png格式
                            <ul class="clearfix">
                                <li class="f-fl imgs" id="ent_show_logo_pic">
                                    
                                    {% if logo %}
                                    <img src="" height="31" width="88">
                                    <a class="f-db" href="javascript:;" onclick="closeImgup(this, {{id}},3);">删除</a>
                                    {% else %}
                                    <input type="hidden" name="yanz" value='' data-rule="图片:required;">
                                    {% endif %}
                                </li>
                            </ul>
                        </div>
                    </td>
                     <input id="logopath" name="logopath" type="hidden" value=''/>
                </tr>
                 <tr>
                     <td class="cx_title">排序： </td>
                    <td class="cx_content">
                    <input type='text' style="width:190px" name="order_item" value="10"/><span style='color:red;'>*</span>
                    </td>
                </tr>
                <tr>
                     <td class="cx_title">展示位置： </td>
                    <td class="cx_content">
						 <input type='radio' name="location" value="1"/>首页
						 <input type='radio' name="location" value="2"/>全站
						 <span style='color:red;'>*</span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">状态： </td>
                    <td class="cx_content"> 
					<input type='radio' name="status" value="1"/>禁用
                    <input type='radio' name="status" value="2"/>激活
					<span style='color:red;'>*</span>
                     </td>
                </tr>
				<tr>
                    <td class="cx_title"  valign="top">备注：</td>
                    <td > <div class="cx_content1">                       
						<textarea name="demo" cols="50" rows="5" ></textarea>
                        </div></td>
                </tr>
         </table>
         
    </div>
		<div align="center" style="margin-top:20px;">
         <input type="submit" value="保存" class="sub"/>
        </div>
  <!-- main_right 结束  --> 
  
</div>
</form>

 

<script type="text/javascript">
 $(function(){
function bankImg(id,type,show_img,tip_id){
    //银行正面照
    id.uploadify({
        'swf'      : '/uploadify/uploadify.swf?ver=<?php echo rand(0,9999);?>',
        'width': '88',
        'height': '31',
        'uploader': '/upload/tmpfile',
        'fileSizeLimit': '1MB',
        'fileTypeExts': '*.jpg;*.png;*.jpeg;*.bmp;*.png',
        'formData': {
            'sid': '{{sid}}',
            'type': type
        },
        'buttonClass': 'upload_btn',
        'buttonText': '上传图片',
        'multi': false,
        onDialogOpen: function() {
            $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
        },
        onUploadSuccess: function(file, data, response) {
            data = $.parseJSON(data);
            alert(data.msg);
            if (data.status) {
                //show_img.attr("src":data.path);
                show_img.html('<img  height="31" width="88" src="'+data.path+'" />');
                $("#logopath").val(data.path);
            }
        }
    });
}
    bankImg($('#ent_logo_pic'),43,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide'));
    
})
</script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript">
$('#savelink').validator({
    ignore: ':hidden'
});
    
$("#savelink").validator({ 
     fields:  {
         website_name:"required;length[1~20];",
         website_link:"required;",
         order_item:"required;integer;range[1~]",
         location:"checked;",
		 status:"checked;",
		 demo:"length[~150];"
     },
    
});
function filtelink(){
var link=$('#website_link').val();
link=link.replace(/(http:\/\/)|(<)|(>)|({)|(})|(《)|(》)/g,'');
$('#website_link').val(link);
}
</script>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

