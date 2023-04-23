<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>资讯详情</title>
<style>
.wrapper{ background:#F2F2F2;}
.artical_title{ height:30px; line-height:30px; background:url(http://yncstatic.b0.upaiyun.com/mdg/images/artical_bc.png) repeat-x; overflow:hidden; border-top:solid 2px #05780a; margin:0;}
.artical_title span{ margin-left:10px; color:#05780a; font-size:14px;}
.artical{ background:#fff; padding:24px 30px 8px;}
.artical p{ color:#333; line-height:16px; margin-bottom:16px;}
</style>
</head>

<body>
<div class="wrapper">
	<h6 class="artical_title">
    	<span>{{advisory['title']}}</span>
    </h6>
	<div class="artical">
    	<!-- 编辑内容（可以抽离的部分）start -->
        <p style="line-height: 2em;">
            <span style="font-size: 18px;">
                <span style="font-family: 宋体,SimSun; font-size: 18px;"> {{advisory['content']}}<span>
            </span>
        </p>
        <!-- 编辑内容（可以抽离的部分）end -->        
    </div>
</div>
</body>
</html>
