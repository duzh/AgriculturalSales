<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/css/Detailed.css" />

<!-- S 调用-->
<!-- E 调用-->
<script>
$(function(){	
  $(window).scroll(function() {
	if($(window).scrollTop()>=250){
		$(".topNav").addClass("fixedNav");}else{
		$(".topNav").removeClass("fixedNav");} 
  });});
  </script>
</head>

<body style="background:#FFF;">
<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->

<div class="cl"></div>
<!--E banner-->
<div class="ynCbanner"></div>
<!--Detailed开始-->
<div style="width:100%; overflow:hidden; background:#FFF;">
		<div class="yncDetai">

			<div id="yncDSonbe"></div>
			<div class="yncDSummary"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy11.jpg" alt="" /></div>
			<div class="yncDSummacon"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy12.jpg" /></div>
		    <div class="yncDSuArchives"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy13.jpg" /></div>
			<div class="yncDSuArchivd"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy14.jpg" /></div>
		    <div id="yncDStwo"></div>
		    <div class="yncDSuSystem"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy15.jpg" /></div>
		    <div class="yncDSuSystemc"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy16.jpg" /><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy17.jpg" /></div>
			<div class="yncDSuFunction"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy18.jpg" /></div>
		    <div class="yncDSuFuncct"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy19.jpg" /></div>
		    <div class="yncDSuFuncct"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy20.jpg" /></div>
		    <div id="yncDSthree"></div>
		    <div class="yncDSuprocess"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy21.jpg" /></div>
		    <div class="yncDSuprocet">
		   	<img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy22.jpg" />
		    <img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy23.jpg" />
		    <img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy24.jpg" />
		    <img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy25.jpg" />
		    <img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy26.jpg" />
		    </div>
		    <div class="yncDSuprocUse"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy27.jpg" /></div>
			<div class="yncDSuprocUsec"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy28.jpg" /></div>
		    <div class="yncDSuprocUsed"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy29.jpg" /></div>
		    <div class="yncDSuprocUsec"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy30.jpg" /></div>
		    <div id="yncDSfore"></div>
		    <div class="yncDAdvantage"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy31.jpg" /></div>
		    <div class="yncDAdvantc"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy32.jpg" /></div>
		    <div class="yncDAdvantc"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy33.jpg" /></div>
		    <div class="yncDAdvantc"><img src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy34.jpg" /></div>
		    <div class="yncDAdvantd"></div>
		<div class="cl"></div>
		</div>
		<div class="yncDAdbom"></div>
</div>
<!--Detailed结束-->
<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
