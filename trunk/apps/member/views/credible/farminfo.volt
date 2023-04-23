<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">

<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}农场基本信息</span>
        </div>	
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="farm-basicInfo f-oh pb30">
				<div class="title f-oh">
					<span>农场基本信息</span>
					<a href="http://{{crediblefarminfo.url}}{{ constant('CUR_DEMAIN') }}/indexfarm/index" target="_Blank">农场预览</a>
				</div>
				<form action="/member/credible/farminfosave" method="post" id="farmInfo-form">
					<div class="message clearfix">
						<font>农场名：</font>
						<div class="inputBox f-pr">
							<?php echo $this->tag->textField(array("farm_name","readonly"=>"true")); ?>
						</div>
					</div>
					<div class="message clearfix">
						<font>农场简介：</font>
						<div class="areaBox f-pr" id="textArea">
							<?php echo $this->tag->textArea(array("desc","name"=>"desc")) ?>
							<div class="tips">
								您最多可以输入<em class="numbers" id="">75</em>个字
							</div>
							<input type="hidden" id="srNum" name="srNum" value="" />
						</div>
					</div>
					<div class="message clearfix">
						<font>农场LOGO：</font>
						<div class="loadBox f-pr">
							<div class="loadBtn">
								<input class="btn1" type="button" value="+上传图片">
								<input class="btn2" type="file" id="ent_logo_pic" >						
							</div>
							<input name="farm-logo" type="hidden" value="{% if logo_pic %}1{% endif %}" id="ent_logo_pic_hide"> 
							<div class="tips">图片大小不超过2M，图片规格：157 X 77，支持jpg、png格式（使用高质量图片，可提高成交的机会）</div>
							<div class="imgBox f-oh mt10" id="ent_show_logo_pic">
								{% if logo_pic %}
					                <div class="imgs f-fl f-pr">
					                  <a class="close-btn" href="javascript:;" onclick="closeImgup(this, 268);" ></a>
					                  <ul class="gallery">
					                    <li>
					                      <a href="{{ constant('IMG_URL') }}{{crediblefarminfo.logo_pic}}">
					                        <img src="{{ constant('IMG_URL') }}{{crediblefarminfo.logo_pic}}" height="77" width="157" alt="">
					                      </a>
					                    </li>
					                    <li style="height:1px;">
					                      <a href="">
					                        <img style="opacity:0; filter:alpha(opacity:0);" src="" alt="">
					                      </a>
					                    </li>
					                  </ul>
					                 </div>
					            {% endif %}
				              </div>
						</div>
					</div>
					<div class="message clearfix">
						<font>农场图片：</font>
						<div class="loadBox f-pr">
							<div class="loadBtn">
								<input class="btn1" type="button" value="+上传图片">
								<input class="btn2" type="file" id='ent_img_pic' />
							</div>
							<input name="farm-photo" type="hidden" value="{% if img_pic %}1{% endif %}" id="ent_img_pic_hide">
							<div class="tips">图片规格220 X 220,该图片将用于可信农场推荐位、可信农场列表页、搜索页</div>
							<div class="imgBox f-oh mt10" id="ent_show_img_pic">
								{% if img_pic %}
					                <div class="imgs f-fl f-pr">
					                  <a class="close-btn" href="javascript:;" onclick="closeImgup(this, 268);" ></a>
					                  <ul class="gallery">
					                    <li>
					                      <a href="{{ constant('IMG_URL') }}{{crediblefarminfo.img_pic}}">
					                        <img src="{{ constant('IMG_URL') }}{{crediblefarminfo.img_pic}}" height="77" width="157" alt="">
					                      </a>
					                    </li>
					                    <li style="height:1px;">
					                      <a href="">
					                        <img style="opacity:0; filter:alpha(opacity:0);" src="" alt="">
					                      </a>
					                    </li>
					                  </ul>
					                 </div>
					            {% endif %}
				              </div>
						</div>
					</div>
					<input class="confirm-btn" type="submit" value="确定">
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
$(function(){
		//验证
		$('#farmInfo-form').validator({
			fields: {
		        'farm-name' : '农场名:required;',
		        'desc'      : '农场简介:required;length[~75];',
		        'farm-logo' : '农场LOGO:required;',
		        'farm-photo': '农场图片:required;'
		    }
		});
});
</script>
<script>
//清除动态插入css和js方法
// function removejscssfile(filename, filetype){
//     //判断文件类型
//     var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "";
//     //判断文件名
//     var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "";
//     var allsuspects=document.getElementsByTagName(targetelement);
//     //遍历元素， 并删除匹配的元素
//     for (var i=allsuspects.length; i>=0; i--){
//         if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1){
//         	//allsuspects[i].parentNode.removeChild(allsuspects[i]);
//         	document.body.removeChild(allsuspects[i]);
//         	//alert(allsuspects[i].src);
//         };      
//     }
// }

function bankImg(id,type,show_img,tip_id){
    //银行正面照
    id.uploadify({
        'swf'      : '/uploadify/uploadify.swf?ver=<?php echo rand(0,9999);?>',
        'width':'120',
        'height':'40',
        'uploader': '/upload/tmpfile',
        'fileSizeLimit': '1MB',
        'fileTypeExts': '*.jpg;*.png;*.jpeg;*.bmp;*.png',
        'formData': {
            'sid': '{{sid}}',
            'type': type,
            'isclos':1
        },
        'buttonClass': 'upload_btn',
        'buttonText': '上传图片',
        'multi': false,
        onDialogOpen: function() {
            $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
        },
        onUploadSuccess: function(file, data, response) {
            //清除插入的css和js 
            // removejscssfile("http://yncstatic.b0.upaiyun.com/mdg/version2.5/js/zoom.min.js", "js");
            // removejscssfile("http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/zoom.css", "css");
            data = $.parseJSON(data);
            alert(data.msg);
            if (data.status) {
                //show_img.attr("src":data.path);

                tip_id.val('1');
                show_img.html(data.html);

    //             var oLink = document.createElement('link');
				// oLink.rel="stylesheet";
				// oLink.href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/zoom.css";
				// document.body.appendChild(oLink);

				// var oScript = document.createElement('script');
				// oScript.src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/js/zoom.min.js";
				// document.body.appendChild(oScript);

            }else{
            	tip_id.val();
            }
        }
    });
};
bankImg($('#ent_logo_pic'),35,$('#ent_show_logo_pic'),$('#ent_logo_pic_hide'));
bankImg($('#ent_img_pic'),43,$('#ent_show_img_pic'),$('#ent_img_pic_hide'));
</script>
<style>
	.uploadify{z-index:2; opacity:0; filter:alpha(opacity:0);}
</style>
