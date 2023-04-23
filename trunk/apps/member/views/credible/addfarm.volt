
<script type="text/javascript" charset="utf-8" src="{{constant('JS_URL')}}/jquery.imgareaselect-0.9.10/scripts/jquery.imgareaselect.pack.js"></script>
<link  rel="stylesheet" type="text/css" href="{{constant('JS_URL')}}/jquery.imgareaselect-0.9.10/css/imgareaselect-default.css">
<style>
    .uploadify{ width:800px; height:800px; position: absolute; left:0; top:0; opacity:0; filter:alpha(opacity:0);}
    .uploadify-queue-item{ display:none;}
</style>
<div class="loadImg">
    <font>上传照片：</font>
    <div class="tip">
        <em>支持 JPG、JPEG、BMP 和 PNG 文件</em>
        <b id="img-tips"></b>
    </div>
    <div class="cutImg-box" style="position:relative;">
        
    	  <input type="file" value="浏览" id="picture_path" name="Filedata" style="position:absolute; left:0; top:0; opacity:0; filter:alpha(opacity:0);">
          <img id="showimg"  src="{{ constant('STATIC_URL') }}active/images/default.jpg"  >
    </div>
    <input  id="caijian" name="candidate_image" type="button" value="裁剪" />
</div>
<script>
$(document).ready(function(){
    $field = $("#picture_path");
    $field.uploadify({//配置uploadify
        'width'    : '800',
        'height'   : '800',
        'buttonText': '上传图片',  //选择按钮显示的字符
        'swf'       : '/uploadify/uploadify.swf?ver=<?php echo rand(0,9999);?>', //swf文件的位置
        'uploader'  : '/upload/uploadtmp', //上传的接收者
        'cancelImg' : '/js/uploadify/uploadify-cancel.png',
        // 'folder'    : '/upload', //上传图片的存放地址
        'auto'      : true,    //选择图片后是否自动上传
        'multi'     : false,   //是否允许同时选择多个(false一次只允许选中一张图片)
        'method'    : 'post',
        'queueSizeLimit' : 1,//最多能选择加入的文件数量
        'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
        'fileTypeDesc': 'Image Files', //允许的格式，详见文档
        'formData' : {
            'sid' : '{{sid}}',
            'type': '{{type}}',
            'count': '1'
        },
        'onUploadSuccess' : function(file, data, response) {  //上传成功后的触发事件
            
            data = $.parseJSON(data);
            if(data.msg){
                alert(data.msg);    
            }
            $("#caijian").show();

            $("#showimg").attr('src',data.path);
            $('#imgtip').val('1');
            
            orignW = data.width,//存储原图的宽高，用于计算
          
            orignH = data.height,
            //aspectRatio = JSON.parse(picFormat)[index].width/JSON.parse(picFormat)[index].height,//提前设定的裁剪宽高比，规定随后裁剪的宽高比例
            //aspectRatio = orignW/orignH,
            aspectRatio =4/3
            frameW = 412,  //原图的缩略图固定宽度，作为一个画布，限定宽度，高度自适应，保证了原图比例
            frameH = 0,
            minWidth =170,
            minHeight=170,
            prevFrameW = 160,  //预览图容器的高宽，宽度固定，高为需要裁剪的宽高比决定
            prevFrameH = 160/aspectRatio,
            rangeX   = 1,  //初始缩放比例
            rangeY   = 1,
            prevImgW = prevFrameW,  //初始裁剪预览图宽高
            prevImgH = prevFrameW;

            imgTar = $("#showimg"),  //画布
           

            frameH = Math.round(frameW*orignH/orignW);//根据原图宽高比和画布固定宽计算画布高，即$imgTar加载上传图后的高。此处不能简单用.height()获取，有DOM加载的延迟
           
            var x1 = (data.width- 210)/2 - 1;
            var y1 = (data.height- 210)/2 - 1;
            var x2 = (data.width - 210)/2 + 210 + 1;
            var y2 = (data.height- 210)/2 + 210 + 1;
            //准备存放图片数据的变量，便于传回裁剪坐标
            var CutJson = new Object();
            CutJson.name = data.filename;
            CutJson.position = {};
    
            //准备好数据后，开始配置imgAreaSelect使得图片可选区
            var imgArea = imgTar.imgAreaSelect({ //配置imgAreaSelect
                instance: true,  //配置为一个实例，使得绑定的imgAreaSelect对象可通过imgArea来设置
                show:true,
                handles: true,   //选区样式，四边上8个方框,设为corners 4个
                fadeSpeed: 300, //选区阴影建立和消失的渐变
                aspectRatio:'1:1', //比例尺
                x1 : x1,
                y1 : y1,
                x2 : x2,
                y2 : y2,
                onInit : function(img,selection){ // 当插件初始化时所调用的函数
                    $('#x1').val(selection.x1);
                    $('#y1').val(selection.y1);
                    $('#x2').val(selection.x2);
                    $('#y2').val(selection.y2);
                    $('#w').val(selection.width);
                    $('#h').val(selection.height);
                },
                onSelectChange: function(img,selection){//选区改变时的触发事件
                    /*selection包括x1,y1,x2,y2,width,height几个量，分别为选区的偏移和高宽。*/

                    rangeX   = selection.width/frameW;  //依据选取高宽和画布高宽换算出缩放比例
                    rangeY   = selection.height/frameH;
                    prevImgW = prevFrameW/rangeX; //根据缩放比例和预览图容器高宽得出预览图的高宽
                    prevImgH = prevFrameH/rangeY;

                    $('#x1').val(selection.x1);
                    $('#y1').val(selection.y1);
                    $('#x2').val(selection.x2);
                    $('#y2').val(selection.y2);
                    $('#w').val(selection.width);
                    $('#h').val(selection.height);
                },

                onSelectEnd: function(img,selection){//放开选区后的触发事件
                    CutJson.position.x1 = selection.x1;
                    CutJson.position.y1 = selection.y1;
                    CutJson.position.x2 = selection.x2;
                    CutJson.position.y2 = selection.y2;
                    CutJson.position.sid = "{{sid}}";
                    CutJson.position.width  = selection.width;
                    CutJson.position.height =selection.height;;
                    CutJson.position.path = data.path;
                    CutJson.position.type = data.type;
                    
                }
            });

            $("#caijian").click(function(){
               
                var x1 = $('#x1').val();
                var y1 = $('#y1').val();
                var x2 = $('#x2').val();
                var y2 = $('#y2').val();
                var w = $('#w').val();
                var h = $('#h').val();
                if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
                    alert("请选取裁剪区!");
                }else{
                    $.ajax({
                        type: "POST",
                        url : "/upload/tmpthumb",
                        data: { name:data.name,sid:"{{sid}}",type:{{type}},position:JSON.stringify(CutJson.position) },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(!data.status){
                                alert(data.msg);    
                            }else{
                                imgTar.attr('src',data.path);
                                imgArea.cancelSelection();
                                //imgArea.setOptions({disable:true});
                                // $("#caijian").hide();               //隐藏裁剪按钮
                                //$(".ws_step03").hide();
                            }
                        }
                    });
                }
            });
        }
    });
});
</script>