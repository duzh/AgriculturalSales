<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">

<div class="dialog" id="add-description" style="height:650px;">
	<div class="developFoot-box pb20 f-oh">
		<form action="/member/farm/save" method="post" id="descriptionForm">
			<div class="message clearfix">
				<font>上传照片：</font>
				<div class="loadBox f-pr">
					<div class="loadBtn">
						<input class="btn1" type="button" value="+上传图片">
						<input class="btn2" name="photo" type="file" id="ent_logo_pic" > 
					</div>
					<div class="tips">图片规格844 ＊ 250</div>
					<input name="photo" type="hidden"  value="" id="ent_logo_pic_hide">
					<div id="ent_show_logo_pic" class="f-oh" style="margin-top: 5px;"></div>
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
					<textarea name="desc"></textarea>
					<div class="tips">
						您最多可以输入<em class="numbers" id="srNum">180</em>个字
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

		$('#descriptionForm').validator({
			fields: {
				'photo': '上传照片:required;',
				'title': '标题:required;length[1~20];',
				'desc': '内容:required;length[~180];'
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
</script>
 <script>
function closeImgup(obj, id) {
    $.getJSON('/member/farm/deleteImg', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
            $(obj).parents('.imgBox').slideUp();
        }
    });
}

function bankImg(id,type,show_img,tip_id){
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
                tip_id.val('1');
                tip_id.next('.n-right').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                show_img.html(data.html);
            }else{
            	tip_id.val();
            }
        }
    });
}
bankImg($('#ent_logo_pic'),37,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide'));
 </script>
<style>
	.uploadify{z-index:2; opacity:0; filter:alpha(opacity:0);}
</style>