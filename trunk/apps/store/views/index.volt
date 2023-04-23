<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>

<title>{{ title }}</title>
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
		
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/base.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/topBottom.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_decoration.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_manage.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/shop_view.css" /> 
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/main.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/line.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/jquery-1.8.2.min.js"></script>


<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>

<!-- 处理IE6下透明度 -->
<!--[if IE 6]>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/Fixpng.js"></script>
<![endif]-->

</head>

<body>
	<?php echo $this->getContent(); ?>
</body>

</html>
