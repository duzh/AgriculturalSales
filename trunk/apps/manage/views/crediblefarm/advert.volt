{{ form("crediblefarm/advertsave", "method":"post","id":"editfarm") }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">

        <div class="bt2">修改可信农场信息</div>
        <div align="left" style="margin-top:20px;margin-left:20px">
          <ul class="zhibiaoTab">
            <li><a href="/manage/crediblefarm/edit/{{user_id}}">农场信息</a></li>
            <li class="active"><a href="/manage/crediblefarm/advert/{{user_id}}">宣传图</a></li>
            <li><a href="/manage/crediblefarm/present/{{user_id}}">农场介绍</a></li>
            <li><a href="/manage/crediblefarm/footprint/{{user_id}}">发展足迹</a></li>
            <li><a href="/manage/crediblefarm/mainproduct/{{user_id}}">产品推荐</a></li>
            <li><a href="/manage/crediblefarm/productprocess/{{user_id}}">种植过程</a></li>
            <li><a href="/manage/crediblefarm/qualifications/{{user_id}}">资质认证</a></li>
            <li><a href="/manage/crediblefarm/picturewall/{{user_id}}">图片墙</a></li>
            <div style="clear:both;"></div>
          </ul>
        </div>
        <div id="fade" class="black_overlay"></div>
        <div class="cx">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr>               
                <div class="message clearfix" style="margin-left:50px">
                	<td valign="top" class="cx_title"> 
                     <font style="display:block;margin-top:12px;">宣传图：</font>
                    </td>
                    <td class="cx_content paddingtd">
                    <div class="loadImg-box">
                        <div class="file-btn">
                            <input class="btn2" type="file" id='ent_logo_pic' />
                            <input id="ent_logo_pic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" />
                            <i id='ent_logo_pic_tip' style="position:absolute; left:176px; top:-6px;"></i>  
                        </div>
                        图片规格1187 X 426     您还可上传<span id="count"><?php echo 3-$count;?></span>张宣传图
                        <ul class="clearfix">
							<li class="f-fl imgs" id="ent_show_logo_pic">
								{% if crediblefarmpicture %}
								{% for va in crediblefarmpicture %}
								<dl class="imgBox" style="margin-right:10px;">	
									<dt>
										<img src="{{ constant('IMG_URL') }}{{va['picture_path']}}" height="118" width="168">
										<a class="f-db" href="#" onclick="closeImgup(this, {{va['id']}},4);">删除</a>
									</dt>
								</dl>
								{% endfor %}
								{% else %}
								<!-- <input type="hidden" name="yanz[]" value='' data-rule="图片:required;"> -->
								{% endif %}
							</li>
						</ul>
                    </div>
                    </td>
                </div>
            </tr>
            </table>
        </div>
        <div align="center" style="margin-top:20px;">
             <input type="hidden" name="user_id" value="{{user_id}}">
             <input type="submit" value="修改" class="sub"/>
        </div>
    </div>
</form>
    <!-- main_right 结束  -->
</div>

<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
<style>

</style>
<script>

// 删除图片
function closeImgup(obj, id, type) {
	var a = $(obj).parent().parent().parent();
	var b = "<input type='hidden' name='yanz[]' value=''  data-rule='图片:required;'>";
    $.getJSON('/manage/crediblefarm/delgraphic', {id : id,type : type}, function(data) {
        alert(data.msg);
        if(data.msg=='数据删除成功！'){
			$("#count").html(parseInt($("#count").html())+1);
		}
        if(data.state) {
            $(obj).parents('.imgBox').remove();
        	if(a.children("dl").length==0){
                a.html(b);
            }
        }
    });
}
// 删除图片
function closeImg(obj, id) {
	var a = $(obj).parent().parent().parent();
	var b = "<input type='hidden' name='yanz[]' value=''  data-rule='图片:required;'>";
	$.getJSON('/upload/deltmpfile', {
		id: id
	}, function(data) {
		alert(data.msg);
		if(data.msg=='删除成功！'){
			$("#count").html(parseInt($("#count").html())+1);
		}
		if (data.state) {
			$(obj).parents('.imgBox').remove();
        	if(a.children("dl").length==0){
                a.html(b);
            }
		}
	});
}

$(function(){
setTimeout(function(){
	//银行正面照
	$('#ent_logo_pic').uploadify({
		'swf'      : '/uploadify/uploadify.swf',
		'width': '88',
		'height': '28',
		'uploader': '/upload/tmpfile',
		'fileSizeLimit': '1MB',
		'fileTypeExts': '*.jpg;*.png;*.jpeg;*.bmp;*.png',
		'formData': {
			'sid': '{{sid}}',
			'type': 36,
			'count':4
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
			if(data.msg=='上传成功'){

				$("#count").html($("#count").html()-1);
			}
			if (data.status) {
				if($('#ent_show_logo_pic').children("dl").length==0){
               		$('#ent_show_logo_pic').html(data.html);
            	}else{
					$('#ent_show_logo_pic').append(data.html);
            	}
				// show_img.attr("src":data.path);
			}
		}
	});
},10);
})
</script>
<style>
td.paddingtd{ padding:20px 0;}
.table_22{ width:702px; border-collapse: collapse;}
.table_22 tr td{ border:1px solid #ccc; text-align: center;}

.upload_btn { width:88px; height:28px; line-height:28px; text-align: center; background: #f7f7f7; border: solid 1px #e4e4e4; color:#666;
  font-family: '微软雅黑';
  cursor: pointer;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
.imgBox{float:left; margin-right:10px;}
.zhibiaoTab li{ float: left; width:100px; height: 32px; text-align: center; line-height: 32px; }
.zhibiaoTab li.active{ border:1px solid #ccc; border-bottom: none; height: 32px; background:#fff;}
.cx{ margin-top:0;}
.zhibiaoTab li.active{ }
.zhibiaoTab li.active a{ color:#8AAF29; font-weight: bold;}
.icon{ background: none;}
</style>