{% if is_ajax is defined %}
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
<?php echo $this->
getContent(); ?>
{% else %}
<!DOCTYPE html>
<html>
<head>
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
	{% if keywords != '' %}
	<meta name="Keywords" content="{% if !start_areas %}农产品{% endif %}{{ keywords }}" />
	{% endif %}
	<meta name="Description" content="{% if start_areas %}{{ descript }} {% else %}丰收汇-可靠农产品交易网，为你提供各地{{ sorce }}信息。{% endif %}" />
	<link rel="shortcut icon" href="{{ constant('STATIC_URL') }}mdg/ico/5fengshou.ico" />
	
	<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/base.css">
	<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/index.css">
	<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/page.css">
	<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/wuliu/css/wuliu.css">
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery-1.11.1.min.js"></script>
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/index.js"></script>
	<script type="text/javascript" src="{{ constant('STATIC_URL') }}js/lhgdialog/lhgdialog.min.js?skin=igreen"></script>
	<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
    <script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
    
	<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
	<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
	<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
    <!--时间插件-->
     <script type="text/javascript" src="{{ constant('STATIC_URL')}}/DatePicker/DatePicker/WdatePicker.js"></script>
	<script type="text/javascript" src="{{ constant('STATIC_URL')}}/js/jquery/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{{ constant('STATIC_URL')}}/js/jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
	<script type="text/javascript" src="{{ constant('STATIC_URL')}}/js/jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
	<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL')}}/js/jquery/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL')}}/js/jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
   
</head>
<body>
	<?php echo $this->getContent(); ?></body>
</html>
{% endif %}