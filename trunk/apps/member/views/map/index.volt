    <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999999) ?>" ></script>
    <link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">

{{ partial('layouts/shop_header') }}

<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}宣传图</span>
</div>





<div class="shop_decora w960 clearfix">
     {{ partial('layouts/shop_left') }}
     
    <div class="decora_right f-fr">
        <div class="public_img">
            <h6 class="clearfix">
                <span class="f-fs14">店铺宣传图</span>
                <!-- <a class="f-fr" href="#">返回栏目列表</a> -->
            </h6>
            <div class="image" >
                
                <div id='bank_card_pictureshow_img' >
                <?php if(isset($image->agreement_image)) { ?>
                    <!--<img src="{{ image.agreement_image }}" alt="" width='760' height='250' id='www'>-->
                    <ul class="gallery">
                        <li>
                            <a href="{{ image.agreement_image }}">
                                <img src="{{ image.agreement_image }}" alt="" width='760' height='250' id='www'>
                            </a>
                        </li>
                        <li><a href="#"><img src="" /></a></li>
                    </ul>
                <?php }?>

                </div>

                    <p class="f-ff0">请上传图片,图片大小760X250像素</p>
                <p class="f-ff0">重新上传（会替换掉现有图片）</p>
            </div>
                

            <div class="load_img clearfix mt20">
                <div class="btn f-fl">
                    <span id='bank_card_picture'>上传图片</span>
                    <input type="file" value="" />
                </div>
                <!-- <font class="f-fl">文件名称</font> -->
            </div>
        </div>
    </div>
</div>


<script>
            
            // (function(){
            //     $('#bank_card_pictureshow_img').on('click', function(event){
            //         var emBig = $('<div class="emBigImg"></div>');
            //         var emSrc = $(this).children('img').attr('src');
            //         emBig.html('<img src=' +  emSrc + ' />');
            //         $(this).after(emBig);

            //         var emBigW = $('.emBigImg img').width(),
            //             emBigH = $('.emBigImg img').height();

            //         $('.emBigImg').css({
            //             'position' : 'fixed',
            //             'left' : '50%',
            //             'top' : '50%',
            //             'margin-left' : -(emBigW/2),
            //             'margin-top' : -(emBigH/2)
            //         });

            //         event.stopPropagation();
            //     })

            //     $(document).click(function(){
            //         $('.emBigImg').remove();
            //     })

            // })();
            </script>


{{ partial('layouts/footer') }}

<!-- 图片点击放大引入文件 -->
<link rel="stylesheet"  href="{{ constant('STATIC_URL') }}mdg/css/zoom.css" media="all" />
<script src="{{ constant('STATIC_URL') }}mdg/js/zoom.min.js"></script>

<!--<script type="text/javascript" src="js/center_trHover.js"></script>-->
<script type="text/javascript">
    

jQuery(document).ready(function(){
    function bankImg(id,type,show_img){
            //银行正面照
            id.uploadify({
          
                'swf'      : '/uploadify/uploadify.swf',
                'uploader' : '/upload/tmpfile',
                'fileSizeLimit' : '2MB',
                'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
                'formData' : {
                    'sid' : '{{ sid }}',
                    'type' :type,
                    'limit' : 1,
                    'shopId' : {{ data['shop_id'] }}
                },
                'buttonClass' : 'upload_btn',
                'buttonText'  : '上传图片',
                'multi'       : false,
                onDialogOpen : function() {
                    $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
                },
                onUploadSuccess  : function(file, data, response) {
                    data = $.parseJSON(data);
                    alert(data.msg);
                    if(data.status) {
                        show_img.html(data.html);
                    }
                }
          });
    }


    //店铺宣传图
    var timer1 = setTimeout(bankImg($('#bank_card_picture'),12,$('#bank_card_pictureshow_img')),20);
    

});

// // 删除临时图片
// function closeImg(obj, id) {
//   $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
//       alert(data.msg);
//       if(data.state) {
//           $(obj).parents('dl').slideUp();
//       }
//   });
// }

</script>
<style>
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
</style>
