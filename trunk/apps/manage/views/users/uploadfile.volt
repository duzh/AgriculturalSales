<script type="text/javascript" src="/touxiang/js/cropbox.js"></script>
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}/mdg/js/touxiang/css/style.css" type="text/css" />
<div class="container">
  <div class="imageBox">
    <div class="thumbBox"></div>
    <div class="spinner" style="display: none">Loading...</div>
  </div>
  <div class="action dialog " > 
    <!-- <input type="file" id="file" style=" width: 200px">-->
    <div class="new-contentarea tc"> <a href="javascript:void(0)" class="upload-img">
      <label for="upload-file">上传图像</label>
      </a>
      <input type="file" class="" name="upload-file" id="upload-file" />
    </div>
    <input type="button" id="btnCrop"  class="Btnsty_peyton" value="裁切">
    <input type="button" id="btnZoomIn" class="Btnsty_peyton" value="+"  >
    <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="-" >

  </div>
  <div class="cropped"></div>
</div>
<script type="text/javascript">
$(window).load(function() {
    var options =
    {
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: '/touxiang/images/avatar.png'
    }
    var cropper = $('.imageBox').cropbox(options);
    $('#upload-file').on('change', function(){
        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            cropper = $('.imageBox').cropbox(options);
        }
        reader.readAsDataURL(this.files[0]);
        this.files = [];
  
    })
    $('#btnCrop').on('click', function(){
        var img = cropper.getDataURL();
     
        $('.cropped').html('');
        $('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:64px;margin-top:4px;border-radius:64px;box-shadow:0px 0px 12px #7E7E7E;" ><p>64px*64px</p>');
        $('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:128px;margin-top:4px;border-radius:128px;box-shadow:0px 0px 12px #7E7E7E;"><p>128px*128px</p>');
        $('.cropped').append('<img src="'+img+'" align="absmiddle" style="width:180px;margin-top:4px;border-radius:180px;box-shadow:0px 0px 12px #7E7E7E;"><p>180px*180px</p>');
   
       if(confirm("是否上传裁剪后的图片"))
       {   

             //window.parent.mainframe.dialog.
           if(img){
                jQuery.ajax({
                    type: "POST",
                    url : "/manage/users/savefile/{{user_id}}/{{operating}}",
                    data: {img:img},
                    dataType: "json",
                    async:false,
                    success:function(data){

                       var obj = eval(data);
                        alert(obj.msg);
                        if (obj.state == '1')
                        {
                          window.parent.dialog.close();
                          parent.location.href="/manage/login/index"; 
                        }else{
                           //parent.location.reload();
                           parent.mainFrame.document.getElementById('myimg').src =  obj.path;
                           // $('#myimg').attr('src', obj.path);
                           window.parent.frames["mainFrame"].dialog.close();
                          
                        }
                    }
                });
           }
       }

    })
    $('#btnZoomIn').on('click', function(){
        cropper.zoomIn();
    })
    $('#btnZoomOut').on('click', function(){
        cropper.zoomOut();
    })
});
window.parent.dialog.size(1136,687);


</script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
</div>
</body>
</html>
