<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">

<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}宣传图</span>
        </div>	
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
	    <div class="center-right f-fr">

				<div class="advertis-map f-oh pb30">
					<div class="title f-oh">
						<span>宣传图</span>
						<a href="http://{{url}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" target="_Blank">农场预览</a>
					</div>
					<form action="/member/advert/save" method="post" id="advertis-form">
						<div class="message clearfix">
							<font>上传宣传图：</font>
							<div class="loadBox f-pr">
								<div class="loadBtn">
									<input class="btn1" type="button" value="+上传图片">
									<input class="btn2" type="file" id="ent_logo_pic" >
								</div>
								<input type="hidden" id="photo" name="photo" value='{% if count %}{{count}}{% endif %}' data-rule="图片:required;">
								<div class="tips">图片规格1187 X 426 您最多可上传3张宣传图</div>
							</div>
						</div>
						<!-- 上传成功后的图片位置 -->
						<div class="imgBox f-oh" id="ent_show_logo_pic">
							{% if crediblefarmpicture %}
								{% for va in crediblefarmpicture %}
								 <div class="imgs f-fl f-pr"><a class="close-btn" href="javascript:;"
								  onclick="closeImgup(this, {{va['id']}});" ></a><ul class="gallery"><li><a href="{{ constant('IMG_URL') }}{{va['picture_path']}}">
								 	<img src="{{ constant('IMG_URL') }}{{va['picture_path']}}" height="120" width="120" alt=""></a></li><li style="height:1px;"><a href=""><img style="opacity:0; filter:alpha(opacity:0);" src="" alt=""></a></li></ul></div>

						<!-- 		<div class="imgs f-fl f-pr">
									<a class="close-btn" href="javascript:;" onclick="closeImgup(this, {{va['id']}});" ></a>
									<img src="{{ constant('IMG_URL') }}{{va['picture_path']}}" height="120" width="120" alt="">
								</div> -->
								{% endfor %}
							{% endif %}
						</div>
						<input class="confirm-btn" type="submit" value="提交">
					</form>
				</div>
			</div>
	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
<!-- 图片放大预览 -->
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/zoom.css" media="all">
<script src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/js/zoom.js"></script>
<style>
  #zoom{ *background:#000; *opacity:1; *filter:alpha(opacity:100);}
  #zoom .previous, #zoom .next, #zoom #previous, #zoom #next{display:none; opacity:0; filter:alpha(opacity:0);}
</style>
<script>
function closeImgup(obj, id) {
    $.getJSON('/member/advert/deleteImg', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
           $(obj).parent().slideUp('200');
	         var count=$("#photo").val();
	         if(count==1){
	             $('#photo').val(''); 
	         }else{
	             $("#photo").val(count-1);
	         }
        }
    });
}
// 删除图片
function closeImg(obj, id) {
    $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
             $(obj).parent().slideUp('200');
             var count=$("#photo").val();
	         if(count==1){
	             $('#photo').val(''); 
	         }else{
	             $("#photo").val(count-1);
	         }
        
        }
    });
}
$(function(){
	setTimeout(function(){
		//银行正面照
		$('#ent_logo_pic').uploadify({
			'swf'      : '/uploadify/uploadify.swf',
			'width': '120',
			'height': '40',
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
				if(data.status) {
				    var count=$('#photo').val();
                    if(count==''){
                       var newcount=1; 
                    }else{
                        var newcount=parseInt(count)+parseInt(1); 
                    }
                    $('#photo').val(newcount);
					$("#ent_show_logo_pic").append(data.html);
				}
			}
		});
	},10);
})
</script>
<style>
	.uploadify{z-index:2; opacity:0; filter:alpha(opacity:0);}
</style>
