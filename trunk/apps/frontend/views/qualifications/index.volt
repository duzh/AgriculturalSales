{{ partial('layouts/orther_herder') }}
<div class="bread-crumbs w1185 mtauto">
   {{ partial('layouts/ur_here') }}我的商户
</div>

		<div class="w1185 mtauto clearfix">
			<!-- 左侧 -->
			{{ partial('layouts/navs_left') }}
			<!-- 右侧 -->
			<div class="center-right f-fr">

				<div class="plant-process-list pb30">
					<div class="title">资质认证</div>
					<div class="box">
						<label class="f-db">
							<font></font>
							<input class="add-btn" type="button" value="新增" />
						</label>
						<table cellpadding="0" cellspacing="0" width="736">
							<tr height="30">
								<th width="30%">图片</th>
								<th width="30%">资质名称</th>
								<th width="25%">认证日期</th>
								<th width="15%">操作</th>
							</tr>
							{% if credible_farm_picture %}
							{% for v in credible_farm_picture%}
							<tr height="120" class="imgBox">
								<td align="center">
									<img src="{{ constant('IMG_URL') }}{{v['picture_path']}}" height="86" width="166" />
								</td>
								<td align="center">{{v['title']}}</td>
								<td align="center">{{date('Y/m/d',v['picture_time'])}}</td>
								<td align="center">
									<a class="f-db" href="#" onclick="closeImgup(this, {{v['id']}});">删除</a>
								</td>
							</tr>
							{% endfor %}
							{% endif %}
						</table>
					</div>
				</div>

			</div>

		</div>
	</div>

	<!-- 弹框 -->
	<div class="plant-layer"></div>
	<div class="plant-add-box">
<form action="/member/qualifications/save" method="post" id="perfect"> 
		<a class="close-btn" href="javascript:;"></a>
		<div class="title">新增图文介绍</div>
		<div class="message clearfix">
			<font>日期</font>
			<div class="select-box">
			<select name="year1"></select>
			<select name="month1"></select>
			<select name="day1" data-rule="日期:required;"></select>
			</div>
		</div>
		<div class="message clearfix">
			<font>资质名称</font>
			<div class="input-box">
				<input type="text" name="title" data-rule="资质名称:required;length[1~20]" />
			</div>
		</div>
		<div class="message clearfix">
			<font>上传照片</font>
				<div class="loadImg-box">
					<div class="file-btn">
						<input class="btn2" type="file" id='ent_logo_pic' />
						<input id="ent_logo_pic_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" value="" />
	                    <i id='ent_logo_pic_tip' style="position:absolute; left:176px; top:-6px;"></i>  
					</div>
						<p>图片规格844 X 250</p>
					<dl id="ent_show_logo_pic">
						<input type="hidden" name="yanz" value='' data-rule="图片:required;">
						<dt>
						</dt>
					</dl>
				</div>
		</div>
		<input class="plant-layer-btn" type="submit" value="确定" />
	</form>
	</div>

	<!-- 底部 -->
			{{ partial('layouts/orther_footer') }}
			<script language="javascript" src="/ymdclass/YMDClass.js"></script>
	<script>
				function closeImgup(obj, id) {
		    $.getJSON('/member/farm/deleteImg', {id : id}, function(data) {
		        alert(data.msg);
		        if(data.state) {
		            $(obj).parents('.imgBox').slideUp();
		        }
		    });
		}
		$(function(){
			$('.plant-process-list .add-btn').click(function(){
				$('.plant-layer').show();
				$('.plant-add-box').show();
			});
			$('.plant-add-box .close-btn').click(function(){
				$('.plant-layer').hide();
				$('.plant-add-box').hide();
			});
function bankImg(id,type,show_img,tip_id){
				//银行正面照
				id.uploadify({
					'swf'      : '/uploadify/uploadify.swf?ver=<?php echo rand(0,9999);?>',
					'width': '88',
					'height': '28',
					'uploader': '/upload/tmpfile',
					'fileSizeLimit': '1MB',
					'fileTypeExts': '*.jpg;*.png;*.jpeg;*.bmp;*.png',
					'formData': {
						'sid': '{{sid}}',
						'type': type,
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
							// show_img.attr("src":data.path);
							show_img.html(data.html);
						}
					}
				});
			}
 		var timer22 = setTimeout(bankImg($('#ent_logo_pic'),40,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide')),10);
		});
	new YMDselect('year1','month1','day1');
	</script>