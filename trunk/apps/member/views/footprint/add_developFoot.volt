<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">

<div class="dialog" id="add-developFoot" style="height:715px;">
	<div class="developFoot-box pb20 f-oh">
		<form action="/member/footprint/save" id="developForm" method="post">
			<div class="message clearfix">
				<font>日期：</font>
				<div class="selectBox f-pr">
				    <select name="year1"></select>
					<select name="month1"></select>
					<select name="day1" data-rule="日期:required;"></select>
				</div>
			</div>
			<div class="message clearfix">
				<font>上传照片：</font>
				<div class="loadBox f-pr">
					<div class="loadBtn">
						<input class="btn1" type="button" value="+上传图片">
						<input class="btn2" type="file" id="ent_logo_pic" >
					</div>
					<input name="photo" type="hidden" value="" id="ent_logo_pic_hide">
					<div class="tips">图片推荐规格200 ＊ 200</div>
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
					<textarea name="content"></textarea>
					<div class="tips">
						您最多可以输入<em class="numbers" id="srNum">150</em>个字
					</div>
				</div>
			</div>
			<input class="confirm-btn" type="submit" value="确  认">
		</form>
	</div>
</div>
<script language="javascript" src="/ymdclass/YMDClass.js"></script>
<script>
$(function(){
	// 验证
	$('#developForm').validator({
		fields: {
			'data': '日期:required;',
			'photo': '上传照片:required;',
			'title': '标题:required;length[1~20];',
			'content': '内容:required;length[~150];'
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
                tip_id.val('1');
                tip_id.next('.n-right').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                show_img.html(data.html);
            }else{
            	 tip_id.val();
            }
        }
    });
}
bankImg($('#ent_logo_pic'),38,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide'));
new YMDselect('year1','month1','day1');
</script>
<style>
	.uploadify{z-index:2; opacity:0; filter:alpha(opacity:0);}
</style>
