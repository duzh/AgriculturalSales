<?php if(isset($isajax)&&$isajax){ ?>
<?php }else{ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/> -->
<!--百度统计-->
<script>
    
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "//hm.baidu.com/hm.js?88aad2902f4d83623cc6166f7cd1af0b";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-65072905-1', 'auto');
      ga('send', 'pageview');


</script>
     
<title>{{ title }}</title>
<!--样式-->
<link rel="shortcut icon" href="{{ constant('STATIC_URL') }}mdg/ico/5fengshou.ico" />
<?php $dev=DEV_MODE?>
{% if dev =='master'%}
<!-- version 2.5 js -->
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-1.11.1.min.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/personal-center.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/index.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/page.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery.validator.js"></script>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/zh_CN.js"></script>
<!-- dialog -->
<script src="{{ constant('STATIC_URL') }}mdg/lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script src="{{ constant('STATIC_URL') }}mdg/js/dialog_call.js"></script>
<!-- add 旧版本样式 -->
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/oldStyle/personal-center.css"> 
<script src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/oldStyle/personal-center.js"></script>
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.4/css/trusted-farm/trusted-farm.css">
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/css/news_tip.css" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/css/shop_manage.css" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/css/shop_decoration.css" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/css/about_ynbao.css" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com//mdg/version2.3/css/service-station.css" />
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com//mdg/version2.4/css/escrow/escrow.css">
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.4/css/line.css">
<!-- version 2.5 css -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/base.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/index.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/page.css">
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/personal-center.css">
<!--上传图片-->
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css?var=<?= rand(1, 9999999) ?>">
<!--下拉列表-->
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<!--ue编辑器-->
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<!--时间插件-->
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
{% else %}
<!-- version 2.5 js -->
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-1.11.1.min.js"></script>
<script src="/mdgtest/version2.5/js/personal-center.js"></script>
<script src="/mdgtest/version2.5/js/index.js"></script>
<script src="/mdgtest/version2.5/js/page.js"></script>
<script src="/mdgtest/version2.5/js/jquery.validator.js"></script>
<script src="/mdgtest/version2.5/js/zh_CN.js"></script>
<!-- dialog -->
<script src="{{ constant('STATIC_URL') }}mdg/lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script src="{{ constant('STATIC_URL') }}mdg/js/dialog_call.js"></script>
<!-- add 旧版本样式 -->
<link rel="stylesheet" href="/mdgtest/version2.5/oldStyle/personal-center.css"> 
<script src="/mdgtest/version2.5/oldStyle/personal-center.js"></script>
<link rel="stylesheet" href="/mdgtest/version2.4/css/trusted-farm/trusted-farm.css">
<link rel="stylesheet" type="text/css" href="/mdgtest/css/news_tip.css" />
<link rel="stylesheet" type="text/css" href="/mdgtest/css/shop_manage.css" />
<link rel="stylesheet" type="text/css" href="/mdgtest/css/shop_decoration.css" />
<link rel="stylesheet" type="text/css" href="/mdgtest/css/about_ynbao.css" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com//mdg/version2.3/css/service-station.css" />
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com//mdg/version2.4/css/escrow/escrow.css">
<link rel="stylesheet" href="/mdgtest/version2.4/css/line.css">
<!-- version 2.5 css -->
<link rel="stylesheet" href="/mdgtest/version2.5/css/base.css">
<link rel="stylesheet" href="/mdgtest/version2.5/css/index.css">
<link rel="stylesheet" href="/mdgtest/version2.5/css/page.css">
<link rel="stylesheet" href="/mdgtest/version2.5/css/personal-center.css">
<!--上传图片-->
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css?var=<?= rand(1, 9999999) ?>">
<!--下拉列表-->
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<!--ue编辑器-->
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<!--时间插件-->
<script type="text/javascript" src="{{ constant('STATIC_URL') }}DatePicker/DatePicker/WdatePicker.js"></script>
{% endif %}

</head>
<?php } ?>
<body>
<!-- 消息提示 -->
<?php echo $this->getContent(); ?>


</body>
</html>
