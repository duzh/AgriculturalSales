<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/css/artical.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/js/jquery-1.9.1.min.js"></script>
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
<style>
.fixedNav{position:fixed;top:0px;left:0px;width:100%;z-index:100000;_position:absolute;_top:expression(eval(document.documentElement.scrollTop))}
.ynCbanner{overflow:hidden;width:100%;overflow:hidden;background:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy1.jpg) top center no-repeat;height:540px}
.content{width:100%}
.content h1{width:100%;height:280px;overflow:hidden;background:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy2.png) #fff no-repeat center center}
.activebg{position:relative;width:100%;height:600px;background:#fff;background-attachment:fixed;background-position:center 0;background-repeat:no-repeat}
.fisrtbg{background-image:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/fisrtbg.jpg)}
.secondbg{background-image:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/avd.jpg)}
.thirdbg{background-image:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy8.jpg)}
.thirdbg2{background-image:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy9.jpg)}
.txtwrap p{font-size:14px;letter-spacing:0.12em}
.qfigure{width:100%;height:584px;background:#fff}
.qw960{position:relative;width:1160px;height:584px;margin:0 auto}
.qcallAnimate{position:absolute;left:-32px;bottom:0;width:664px;height:490px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy3.png) no-repeat 0 0}
.qcall .txtwrap{text-align:right;font-size:14px;position:absolute;right:7px;top:115px;width:566px}
.qcall h2{width:100%;height:0;overflow:hidden;padding-top:135px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy4.png) no-repeat top right}
.qcall p{width:100%;margin-bottom:12px;line-height:25px;text-align:right;opacity:0.6}
.figs{width:487px;float:right;padding-top:44px;min-height:77px}
.figs li{padding:58px 0 0;color:#a0a0a0;font-size:14px}
.qcall .figs{background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/qcall_figs.png) no-repeat 165px 41px}
.qcall .figs li{float:right;border-left:1px solid #e9e9e9;text-align:center}
.qcall .figs li.f01{width:109px;text-align:right}
.qcall .figs li.f02{width:109px}
.qcall .figs li.f03{width:156px;border:none}
.qcallWrap{position:relative;width:251px;height:446px;overflow:hidden;left:57px;top:72px}
.qcallWrap .pic1{position:absolute;left:0;top:0;width:255px;height:450px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/qcall_fig1.png) no-repeat 0 0;z-index:2;opacity:0;filter:Alpha(opacity=0)}
.qcallbtn{position:absolute;left:200px;top:295px;width:30px;height:30px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/point.png) no-repeat 0 0;z-index:1;opacity:0;filter:Alpha(opacity=0)}
.qfileAnimate{position:absolute;right:34px;bottom:0;width:383px;height:584px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/qfile_fig0.png) no-repeat 0 0}
.qfile .txtwrap{position:absolute;left:7px;top:115px;width:450px}
.qfile h2{width:100%;height:0;overflow:hidden;padding-top:140px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy5.png) no-repeat 0 0;position:relative;z-index:9}
.qfile p{width:90%;margin-bottom:12px;padding-left:5px;line-height:25px;opacity:0.6}
.qfileAnimat2e{position:absolute;right:34px;bottom:0;width:683px;height:584px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy7.jpg) no-repeat center center}
.txtwrap_pa{width:410px;height:198px;background:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy6.png) no-repeat}
.txtwrap_pc{width:448px;height:279px;background:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy13.png) no-repeat}
.qblogAnimate{position:absolute;left:80px;bottom:45px;width:518px;height:518px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy11.png) no-repeat 0 0}
.txtwrap2{text-align:right;font-size:14px;position:absolute;right:80px;margin-top:80px;background:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy12.png) no-repeat}
.txtwrap2 div{width:448px;height:383px;background:url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy12.png) no-repeat}
.qblog .txtwrap{position:absolute;right:7px;top:120px;width:495px}
.qblog p{width:100%;margin-bottom:12px;line-height:30px;text-align:right;opacity:0.6}
.morefigs{width:100%;height:87px;padding-top:43px;border-top:1px solid #e5e5e5}
.morefigs a{display:block;width:332px;height:0;overflow:hidden;padding-top:45px;margin:0 auto;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/morefigs.png) no-repeat 0 0}
a.knmore:link, a.knmore:visited{color:#12b7f5}
.qfileAnimat3e{position:absolute;right:34px;bottom:0;width:683px;height:584px;background:transparent url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy14.png) no-repeat center center}
.txtwrapd{position:absolute;left:7px;top:115px;width:450px}
.txtwrapd p{float:left}
.qblog .txtwrapd p{width:100%;margin-bottom:12px;line-height:30px;text-align:left;opacity:0.6}
.flashBox{float:left;background:url(http://im-img.qq.com/online/../{{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/loading.gif) no-repeat 50% 40%;width:910px;height:auto}
.overlay{display:none;position:absolute;left:0;top:0;z-index:9999;width:100%;height:100%;background-color:#000;opacity:0.6;filter:Alpha(opacity=60)}
#flashDiv{position:absolute;left:50%;top:47px;width:978px;height:721px;margin:0 0 0 -489px;display:none;z-index:10000;position:fixed}
.togame{clear:both;padding-right:80px;height:31px;text-align:right}
.clbom{ border-bottom:#E5E5E5 1px solid;}
@keyframes fadeUp {
    0% { opacity: 0;transform:translateY(-20px)}
    100% { opacity: 1;transform:translateY(0px)}
}
@-moz-keyframes fadeUp {
    0% {opacity: 0;transform:translateY(-20px)}
    100% {opacity: 1;transform:translateY(0px)}
}
@-webkit-keyframes fadeUp {
    0% {opacity: 0;-webkit-transform:translateY(-20px)}
    100% {opacity: 1;-webkit-transform:translateY(0px)}
}

@-o-keyframes fadeUp {
    0% { opacity: 0;-o-transform:translateY(-20px)}
    100% { opacity: 1;-o-transform:translateY(0px)}
}
@-ms-keyframes fadeUp {
    0% { opacity: 0;-ms-transform:translateY(-20px)}
    100% { opacity: 1;-ms-transform:translateY(0px)}
}

@keyframes fadeIn {
    0% { opacity: 0}
    100% { opacity: 1}
}
@-moz-keyframes fadeIn {
    0% {opacity: 0}
    100% {opacity: 1}
}
@-webkit-keyframes fadeIn {
    0% {opacity: 0}
    100% {opacity: 1}
}
@-o-keyframes fadeIn {
    0% { opacity: 0}
    100% { opacity: 1}
}
@-ms-keyframes fadeIn {
    0% { opacity: 0}
    100% { opacity: 1}
}

@keyframes widthall {
    0%{ width: 30%}
    100% { width: 100%}
}
@-moz-keyframes widthall {
     0%{ width: 30%}
    100% { width: 100%}
}
@-webkit-keyframes widthall {
     0%{ width: 30%}
    100% { width: 100%}
}
@-o-keyframes widthall {
     0%{ width: 30%}
    100% { width: 100%}
}
@-ms-keyframes widthall{
     0%{ width: 30%}
    100% { width: 100%}
}

@keyframes zoomIn {
    0%{ transform: scale(0)}
    60%{ transform: scale(1.1)}
    100% { transform: scale(1)}
}
@-moz-keyframes zoomIn {
    0%{ transform: scale(0)}
    60% { transform: scale(1.1)}
    100% { transform: scale(1)}
}
@-webkit-keyframes zoomIn {
    0%{ -webkit-transform: scale(0)}
    60% { -webkit-transform: scale(1.1)}
    100% { -webkit-transform: scale(1)}
}
@-o-keyframes zoomIn {
    0%{ -o-transform: scale(0)}
    90% { -o-transform: scale(1.1)}
    100% { -o-transform: scale(1)}
}
@-ms-keyframes zoomIn{
    0%{ -ms-transform: scale(0)}
    60% { -ms-transform: scale(1.1)}
    100% { -ms-transform: scale(1)}
}

@keyframes moveleft {
    0% { transform:translateX(100%);opacity: 1}
    100% { transform:translateX(0);opacity: 1}
}
@-moz-keyframes moveleft {
    0% { transform:translateX(100%);opacity: 1}
    100% { transform:translateX(0);opacity: 1}
}
@-webkit-keyframes moveleft {
    0% {-webkit-transform:translateX(100%);opacity: 1}
    100% { -webkit-transform:translateX(0);opacity: 1}
}

@-o-keyframes moveleft {
    0% { -o-transform:translateX(100%);opacity: 1}
    100% { -o-transform:translateX(0);opacity: 1}
}
@-ms-keyframes moveleft {
    0% { -ms-transform:translateX(100%);opacity: 1}
    100% { -ms-transform:translateX(0);opacity: 1}
}

@keyframes click {
    0% { transform:translateY(0px) translateX(0px)}
    80% { transform:translateY(80px) translateX(-10px) scale(1);opacity: 1}
    90% { transform:translateY(80px) translateX(-10px) scale(1);opacity: 1}
    100% { transform:translateY(80px) translateX(-10px) scale(1.5);opacity: 0}
}
@-moz-keyframes click {
    0% { transform:translateY(0px) translateX(0px)}
    80% { transform:translateY(80px) translateX(-10px) scale(1);opacity: 1}
    90% { transform:translateY(80px) translateX(-10px) scale(1);opacity: 1}
    100% { transform:translateY(80px) translateX(-10px) scale(1.5);opacity: 0}
}
@-webkit-keyframes click {
    0% { -webkit-transform:translateY(0px) translateX(0px)}
    80% { -webkit-transform:translateY(80px) translateX(-10px) scale(1);opacity: 1}
    90% { -webkit-transform:translateY(80px) translateX(-10px) scale(1);opacity: 1}
    100% { -webkit-transform:translateY(80px) translateX(-10px) scale(1.5);opacity: 0}
}

@-o-keyframes click {
    0% { -o-transform:translateY(0px) translateX(0px)}
    80% { -o-transform:translateY(-240px) translateX(-107px) scale(1);opacity: 1}
    90% { -o-transform:translateY(-240px) translateX(-107px) scale(1);opacity: 1}
    100% { -o-transform:translateY(-240px) translateX(-107px) scale(1.5);opacity: 0}
}
@-ms-keyframes click {
    0% { -ms-transform:translateY(0px) translateX(0px)}
    80% { -ms-transform:translateY(-240px) translateX(-107px) scale(1);opacity: 1}
    90% { -ms-transform:translateY(-240px) translateX(-107px) scale(1);opacity: 1}
    100% { -ms-transform:translateY(-240px) translateX(-107px) scale(1.5);opacity: 0}
}

@media screen and (min-width:1921px) {
    .crossbanenr li{ width: 100%;left: 50%;margin-left: -50%}
    .crossbanenr li.pcqqbg, .crossbanenr li.mbqqbg, .crossbanenr li.padqqbg,.fisrtbg, .secondbg, .thirdbg{ background-size: 100%}
}

@media screen and (max-width:1440px) {
    .fisrtbg{ background-image: url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/bg1_1600.jpg)}
    .secondbg{ background-image: url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/avds.jpg)}
    .thirdbg{ background-image: url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/bg3_1600.jpg)}
	.thirdbg2{ background-image: url({{ constant('STATIC_URL') }}/mdg/zhuanti/Propaganda/images/ynCxCy9.jpg)}
}

@media screen and (max-width:1200px) and (min-height:1400px) {
    .crossbanenr li.pcqqbg, .crossbanenr li.mbqqbg, .crossbanenr li.padqqbg,.crossbanenr li.watchbg, .fisrtbg, .secondbg, .thirdbg{ background-size: cover}
}
@media screen and (min-width:1200px) and (min-height:900px) {
    .crossbanenr li.pcqqbg, .crossbanenr li.mbqqbg, .crossbanenr li.padqqbg,.crossbanenr li.watchbg, .fisrtbg, .secondbg, .thirdbg{ background-size: cover}
}
</style>
<body>
<!-- 头部开始 -->
{{ partial('layouts/page_header') }}
<!-- 头部结束 -->
<div class="cl"></div>
<!--banenr-->
<div class="ynCbanner"></div>
<!--S cont-->
  <div class="content"> 
   <h1></h1> 
   <div id="fisrtbg" class="activebg fisrtbg" data-stellar-background-ratio="0.03"></div> 
   <div class="qfigure qcall"> 
    <div class="qw960"> 
     <div class="txtwrap"> 
      <h2>可靠农产品溯源系统</h2> 
      <p>丰收汇可靠农产品溯源系统通过信息化手段，<br />对农民产品生产记录全程进行“电子化”管理，为农产品建立透明的“身份档案”</p> 
      <p><a class="knmore" href="/index/propagandainfo/#yncDSonbe" target="_blank">了解更多 &gt;</a></p> 
      <ul class="figs"> 
       <li class="f01">关键环境数据</li> 
       <li class="f02">耕种/肥料/药记录</li> 
       <li class="f03">生长周期图片</li> 
      </ul> 
     </div> 
     <div class="qcallAnimate"> 
     </div> 
    </div> 
   </div> 
   <div id="secondbg" class="activebg secondbg" data-stellar-background-ratio="0.05"></div> 
   <div class="qfigure qfile"> 
    <div class="qw960"> 
     <div class="txtwrap"> 
      <h2></h2> 
      <p class="txtwrap_pa"></p> 
      <p><a class="knmore" href="/index/propagandainfo/#yncDStwo" target="_blank">了解更多 &gt;</a></p> 
     </div> 
     <div class="qfileAnimat2e"> 
     </div> 
    </div> 
   </div> 
   <div id="thirdbg" class="activebg thirdbg" data-stellar-background-ratio="0.05"></div> 
   <div class="qfigure qblog"> 
    <div class="qw960"> 
     <div class="txtwrap2"> 
      <div></div> 
      <p><a target="_blank" href="/index/propagandainfo/#yncDSthree" class="knmore">了解更多 &gt;</a></p> 
     </div> 
     <div class="qblogAnimate"> 
     </div> 
    </div> 
   </div> 
   <div id="thirdbg2" class="activebg thirdbg2" data-stellar-background-ratio="0.05"></div> 
   <div class="qfigure qblog"> 
    <div class="qw960"> 
     <div class="txtwrapd"> 
      <p class="txtwrap_pc"></p> 
      <p><a target="_blank" href="/index/propagandainfo/#yncDSfore" class="knmore">了解更多 &gt;</a></p> 
     </div> 
     <div class="qfileAnimat3e"> 
     </div> 
    </div> 
   </div> 
  </div>
<!--E cont-->
<div class="cl"></div>
<div class="clbom"></div>
<div class="cl"></div>

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
</body>
</html>
