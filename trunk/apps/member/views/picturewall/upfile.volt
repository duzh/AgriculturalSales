<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">

<div class="dialog" id="add-newWall" style="height:648px;">
		<div class="developFoot-box pb20 f-oh">
			 {{ form("picturewall/create", "method":"post","id":"newsell") }}
				<div class="message clearfix">
					<font>新增图片墙：</font>
					<div class="loadBox f-pr">
						<div class="loadBtn">
							<input class="btn1" type="button"  value="+上传图片">
							<input class="btn2" type="file" id="img_upload">
							
						</div>
						<input name="path" id="img_upload_hide"  type='hidden' id="path" data-rule="required;" value="">
						<div class="tips">图片推荐规格800 X 600</div>
						<dl id="ent_show_logo_pic" class="mt10 f-oh">
						</dl>
					</div>
				</div>
				<div class="message clearfix">
					<font>标题内容：</font>
					<div class="inputBox f-pr">
						<input name="title" type="text">
					</div>
				</div>
				<div class="message clearfix">
					<font>内容：</font>
					<div class="areaBox f-pr" id="textArea">
						<textarea name="contents"></textarea>
						<div class="tips">
							您还可以输入<em class="numbers" id="srNum" name="srNum">255</em>个字
						</div>
					</div>
				</div>
				<input class="confirm-btn" type="submit" value="确  认">
			</form>
		</div>
	</div>

	<script>
		$(function(){
			// 验证
			$('#newsell').validator({
				fields: {
			        'path': '图片墙:required;',
			        'title': '标题:required;length[1~20];',
			        'contents': '内容:required;length[~255];'
			    }
			});

			// 字数统计
		// var old_text=$("#textArea textarea").val().length;
		// var old_num = $("#textArea em").text();
		// if(old_text > 0){
		// 	$("#textArea em").text(num-old_text);
		// 	var new_num = $("#textArea em").text();
		// 	$(document).keyup(function() {
		// 		var new_text=$("#textArea textarea").val();
		// 		var counter=new_text.length;
		// 		if(counter > new_num){
		// 			alert("字数超出限制");
		// 			var string = $("#textArea textarea").val().substring(0, old_num);
		// 			$("#textArea textarea").val(string);
		// 			return false;
		// 		}else{
		// 			$("#textArea em").text(new_num-counter);
		// 		};
		// 	});
		// }else{
		// 	$(document).keyup(function() {
		// 		var text=$("#textArea textarea").val();
		// 		var counter=text.length;
		// 		if(counter > old_num){
		// 			alert("字数超出限制");
		// 			var string = $("#textArea textarea").val().substring(0, old_num);
		// 			$("#textArea textarea").val(string);
		// 			return false;
		// 		}else{
		// 			$("#textArea em").text(old_num-counter);
		// 		};
		// 	});
		// }
		});
function imgWall(id,type,show_img,tip_id){
    //银行正面照
    id.uploadify({
        'swf'      : '/uploadify/uploadify.swf?ver=<?php echo rand(0,9999);?>',
        'width': '120',
        'height': '40',
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
            if(data.status) {
            	 tip_id.val("1");
            	 tip_id.next('.n-right').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
            	 show_img.html(data.html);
            }else{
            	tip_id.val();
            }
        }
    });
}
imgWall($('#img_upload'),39,$('#ent_show_logo_pic'),$('#img_upload_hide'));
	</script>
<style>
	.uploadify{z-index:2; opacity:0; filter:alpha(opacity:0);}
</style>
