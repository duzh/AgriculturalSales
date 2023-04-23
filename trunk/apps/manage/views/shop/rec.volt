<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/style.css">


  
<body>
<div class="bt2">
  店铺查看

</div>
  <div class="main">
    <div class="main_right">
      <h6>首页推荐</h6>
      <div class="cx">
        <form action="/manage/shop/saverec" enctype="multipart/form-data" method="post">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
          <tbody>
            <tr>
              <td class="cx_title">店铺名称：</td>
              <td class="cx_content">{{ data.shop_name}}</td>
            </tr>

            <tr>
              <td class="cx_title">推荐图片：</td>
              <td class="cx_content">
                    
                    <div id='show_img'></div>
                
                    <input type="file" value="浏览" id="img_upload" name="img_upload" >
              </td>
            </tr>
            <tr>
              <td class="cx_title"></td>
              <td class="cx_content">
                    
                
              </td>
            </tr>

            <tr>
              <td class="cx_title"></td>
              <td class="cx_content">
                <input type="hidden" name='shopid' value='{{data.shop_id}}'>
                    <input type="submit" value="保存"  >
              </td>
            </tr>
            

            </tbody>
          </table>
          </form>
        </div>
        <div align="center" style="margin-top:20px;"></div>
      </div>
      <!-- main_right 结束  --> </div>
    <div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
    <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/jquery/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
    <script>

</script>
 

<script type="text/javascript">
  
jQuery(document).ready(function(){
     setTimeout(function(){
          $('#img_upload').uploadify({
              'swf'      : '/uploadify/uploadify.swf',
              'uploader' : '/upload/tmpfile',
              'formData' : {
                  'sid' : '{{ sid }}',
                  'type': '23',
              },
              'fileSizeLimit' : '2MB',
              'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;',
              'buttonClass' : 'upload_btn',
              'buttonText'  : '浏览',
              'multi'       : false,

              onUploadSuccess  : function(file, data, response) {
                  data = $.parseJSON(data);
                  alert(data.msg);
                  if(data.status) {
                      $('#show_img').html(data.html);
                  }
              }
          })
    },10)
});


</script>
</body>

<style>
.upload_btn {width: 121px;height: 31px;line-height: 42px;text-align: center;background: url(http://yncstatic.b0.upaiyun.com/mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}

</style>