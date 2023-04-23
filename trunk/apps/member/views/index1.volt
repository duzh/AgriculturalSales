<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "//hm.baidu.com/hm.js?e8c48a2b64e9521ae07d0f67cdd9f867";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
</script>   
<title>{{ title }}</title>
<link rel="shortcut icon" href="{{ constant('STATIC_URL') }}mdg/ico/5fengshou.ico" />

<!-- version 2.4 -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/base.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/index.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/nav-page.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/personal-center.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/jquery-1.11.1.min.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/index.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/slide.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/nav-page.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/personal-center.js"></script>
  
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/topBottom.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/personalCenter.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/registerLogin.css" /> 
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/line.css" />

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator-src.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/navSlide.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/center_navSlide.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/navList_slider.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/topBottom_erji.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
<!-- <script type="text/javascript" src="{{ constant('JS_URL') }}mdg/js/accordion.js"></script> -->
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/fukuan.css" />
<!-- 处理IE6下透明度 -->
<!--[if IE 6]>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/Fixpng.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/news_tip.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_manage.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_decoration.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/about_ynbao.css" />

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/version2.3/css/base_index.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/version2.3/css/index.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/version2.3/css/service-station.css" />
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">

</head>

<body>
	<?php echo $this->getContent(); ?>
  <?php if (isset($messagecount)&&$messagecount>0){ ?>
  <div class="news_tip_alert" id="news_tip_alert">
    <a id="close_btn" class="close_btn" href="javascript:close_btn1()">X</a>
      <p><a href="javascript:close_btn2()" >您有({{messagecount}})条新的消息，请查看</a></p>
      <input type='hidden' id="order_id" >
  </div>
<?php } ?>
  <script>
function close_btn1(){
    $('#news_tip_alert').animate({'bottom': '-132px'});
}
function close_btn2(){
    $('#news_tip_alert').animate({'bottom': '-132px'});
  window.location.href='/member/messageuser/list';
}
jQuery(document).ready(function(){

    function setalert(){
          $.getJSON('/member/ajax/GetCountMessage/',function(data) {
             if(data.state){
                    $('#news_tip_alert').animate({'bottom': '0'});
                 }
          });
     };
    setalert();
});
</script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
</body>
</html>