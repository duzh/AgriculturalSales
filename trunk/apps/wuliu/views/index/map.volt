{{ partial('layouts/orther_herder') }}
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
		body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";}
		#allmap{width:100%;height:800px;}
		p{margin-left:5px; font-size:14px;}
		.anchorBL{  
       display:none;  
        }  
	   #l-map {
			width:100%; 
			height:500px;
			overflow: hidden;
		}
		
		li{
			line-height:28px;
		}
		.cityList{
			height: 320px;
			width:372px;
			overflow-y:auto;
		}
		.sel_container{
			z-index:9999;
			font-size:12px;
			position:absolute;
			right:0px;
			top:0px;
			width:140px;
			background:rgba(255,255,255,0.8);
			height:30px;
			line-height:30px;
			padding:5px;
		}
		.map_popup {
			position: absolute;
			z-index: 200000;
			width: 382px;
			height: 344px;
			right:0px;
			top:40px;
		}
		.map_popup .popup_main { 
			background:#fff;
			border: 1px solid #8BA4D8;
			height: 100%;
			overflow: hidden;
			position: absolute;
			width: 100%;
			z-index: 2;
		}
		.map_popup .title {
			background: url("http://map.baidu.com/img/popup_title.gif") repeat scroll 0 0 transparent;
			color: #6688CC;
			font-weight: bold;
			height: 24px;
			line-height: 25px;
			padding-left: 7px;
		}
		.map_popup button {
			background: url("http://map.baidu.com/img/popup_close.gif") no-repeat scroll 0 0 transparent;
			cursor: pointer;
			height: 12px;
			position: absolute;
			right: 4px;
			top: 6px;
			width: 12px;
		}	
	</style>
	
	<title>地图列表</title>
</head>
<body>
	<select class="selectAreas" onchange="selectpro()" id="province">
        <option>省</option>
    </select>
    <select class="selectAreas" onchange="selectcity()" id="city">
        <option>市</option>
    </select>
    <select class="selectAreas" onchange="selecttown()" id="town" >
        <option>县/区</option>
    </select>
    <input type="hidden" id="pages" value="{{page}}" >
	<div id="allmap"></div>
</body>
</html>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ocQy04ecp2pLe6SlT4cpyiu7"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/CityList/1.2/src/CityList_min.js"></script>
<!--点聚合-->
<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
{{ partial('layouts/footer') }}
<script type="text/javascript">
    
	
		// 百度地图API功能	
		// 创建Map实例
         map = new BMap.Map("allmap");
		 map.addControl(new BMap.NavigationControl()); // 添加左上角的标尺工具
		 map.addControl(new BMap.ScaleControl());      //添加比例尺
		 map.addControl(new BMap.OverviewMapControl()); //添加缩略地图控件
		 //map.setMapStyle({style:'grassgreen'});          //设置地图样式
		 map.addControl(new BMap.MapTypeControl());           
		 map.centerAndZoom(new BMap.Point({{lngfirst}},{{latfirst}}), 8);   // 初始化地图,设置城市和地图级别。	
		 map.enableScrollWheelZoom();                 //启用滚轮放大缩小
         
         var data_info=[{{str}}];
		 opts = {
					width : 250,     // 信息窗口宽度
					height: 80,     // 信息窗口高度
					title : "县级服务站" , // 信息窗口标题
					enableMessage:false//设置允许信息窗发送短息
		 };
		 
		//循环数据
		for(var i=0;i<data_info.length;i++){
			var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));  // 创建标注
			var content = data_info[i][2];
			map.addOverlay(marker);               // 将标注添加到地图中
			addClickHandler(content,marker);
		}
		total={{total}}; 
	   
		//创建时间
		function addClickHandler(content,marker){

			marker.addEventListener("click",function(e){
				openInfo(content,e)}
			);
		}
		//弹框提示内容
		function openInfo(content,e){
			var p = e.target;
			var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
			var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
			map.openInfoWindow(infoWindow,point); //开启信息窗口
		}

		function setPlace(myValue){
			//map.clearOverlays();    //清除地图上所有覆盖物
			function myFun(){
				var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
				map.centerAndZoom(pp, 9);
				var marker=new BMap.Marker(pp);
			    var label = new BMap.Label("我是id="+i,{offset:new BMap.Size(20,-10)});
				//map.addOverlay(marker,label);    //添加标注
				marker.setAnimation(BMAP_ANIMATION_BOUNCE)
			}
			var local = new BMap.LocalSearch(map, { //智能搜索
			  onSearchComplete: myFun
			});
			
			local.search(myValue);
		}

		$(function(){
			setTimeout("getmap()",200);
			//getmap();
		});
		var $ld5 = $(".selectAreas");
		$ld5.ld({ajaxOptions:{"url":"/ajax/getareasfull"},
		    defaultParentId : 0,
		    style : {"width" : 140}
		});
		function selectpro(){
		   var address = $("#province option:selected ").text();
		    setPlace(address);
		}
		function selectcity(){
			var address = $("#city option:selected ").text();
		    if(address!="市辖区"){
		   	   setPlace(address);
		    }
		}
		function selecttown(){
			var address = $("#town option:selected ").text();
		     setPlace(address);
		}
		function getmap(){
		   var p=$("#pages").val();
		   if(p<total){
			   $.getJSON('/index/showmap/', {p:p}, function(data) {
			   	    var jsonobj=eval(data.str);  
					for(var i=0;i<jsonobj.length;i++){  
					  	var marker = new BMap.Marker(new BMap.Point(jsonobj[i].lng,jsonobj[i].lat));  // 创建标注
						var content = jsonobj[i].address;
						map.addOverlay(marker);               // 将标注添加到地图中
						addClickHandler(content,marker);
					} 
			   	    $("#pages").val(data.pages);
			 	    htime=setTimeout("getmap()",200);
			   });
			}else{
				
			    clearTimeout(htime);
			}
		}
</script>
