{{ partial('layouts/page_header') }}
<div class="loading-layer" id="maploading" style="z-index:100000"></div>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">

		<div class="bread-crumbs">
			<a href="#">首页</a>&nbsp;>&nbsp;服务站
		</div>
		<form action="/map/map" method="get" >
		<div class="station-filter">
			<div class="query f-oh">
				<font>查询项：</font>
				<!-- <label>
					<input type="radio" class="radio" value="0" name="type" checked />
					<i>全部</i>
				</label> -->
				<label>
					<input type="radio" value="1" class="radio"  name="type" {% if type==1 %}checked{% endif %} />
					<i>县级服务站</i>
				</label>
				<!-- <label>
					<input type="radio" value="2" class="radio" name="type" {% if type==2 %}checked{% endif %}/>
					<i>县级服务站</i>
				</label> -->
			</div>
			<div class="area f-oh">
				<font>地&nbsp;&nbsp;&nbsp;区：</font>
			    <select class="selectAreas" onchange="selectpro()" id="province">
			        <option>请选择</option>
			    </select>
			    <select class="selectAreas" onchange="selectcity()" id="city">
			        <option>请选择</option>
			    </select>
			    <select class="selectAreas"  onchange="selecttown()" id="town" >
			        <option>请选择</option>
			    </select>
			    <input type="hidden" id="addressid" name="addressid" value="{{addressid}}">
			    <input type="hidden" id="address" name="address" value="{{address}}">
			    <input type="hidden" id="size" name="size" value="{{size}}">
			    <input type="hidden" id="pages" value="{{page}}" >
			    <input type="hidden" id="type" value="{{type}}">
				<input type="submit" value="查询">
			</div>
		</div>
	    </form>
		<div class="map-marker f-oh">
			<div class="marker-list f-fl">
				<div class="title f-oh">
					<strong>{{address}}</strong>
					<font>共{{max}}个</font>
				</div>
				<div class="box">

					<!-- 滚动条 -->
					<div class="scrollBar">
						<span></span>
					</div>
					<div class="resultBox">

						<!-- 列表 -->
						{% for key,val in calm %}
						<div class="list">
							<div class="m-box">
								<div class="m-title" onclick='theLocation(this,"{{val['lng']}}","{{val['lat']}}","{{val['url']}}","{{val['content']}}")' style="cursor:pointer;">
									<span></span>
									<strong>{{val["type"]}}</strong>
								</div>
								<div class="message">姓名:{{val["f_name"]}}</div>
								<div class="message">电话：{{val["mobile"]}}</div>
								<div class="message">地址:{{val["address"]}}</div>
							</div>
						</div>
						{% endfor %}
						<!-- 列表 -->
						
						
					</div>

				</div>
			</div>
			<!-- 地图 -->
			<div class="map-location f-fr" id="allmap" >
				
			</div>
		</div>

	</div>
</div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ocQy04ecp2pLe6SlT4cpyiu7"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/CityList/1.2/src/CityList_min.js"></script>

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
		 map.centerAndZoom("{{address}}",{{size}});   // 初始化地图,设置城市和地图级别。	
		 map.enableScrollWheelZoom();                 //启用滚轮放大缩小
         
         var data_info=[{{str}}];

		 opts = {
					width : 402,     // 信息窗口宽度
					height: 212,     // 信息窗口高度
					enableMessage:false//设置允许信息窗发送短息
		 };

		var pointArray = new Array();
		if(data_info!=''){
	        //循环数据
			for(var i=0;i<data_info.length;i++){
	             //alert(data_info[i][1]);
	             if(data_info[i]){
		                 pt = new BMap.Point(data_info[i][0],data_info[i][1]);
					     pointArray[i]=new BMap.Point(data_info[i][0],data_info[i][1]);
				         myIcon = new BMap.Icon(data_info[i][3], new BMap.Size(20,30));
				         marker = new BMap.Marker(pt,{icon:myIcon});  // 创建标注
				         var content = data_info[i][2];
				         // console.log(content);
				         map.addOverlay(marker);               // 将标注添加到地图中
				         addClickHandler(content,marker);
		        }
			}
		}
		if(pointArray!=''){
		map.setViewport(pointArray);
	    }
		total={{total}}; 
		$(function(){
			if(data_info!=''){
                // getmap();
			}else{
				$("#maploading").hide();
			}   
		});
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
			infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
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
		var $ld5 = $(".selectAreas");
		$ld5.ld({ajaxOptions:{"url":"/ajax/getareasfull"},
		    defaultParentId : 0,
			{% if (addressstr) %}
		    texts : [{{ addressstr }}],
		    {% endif %}
		    style : {"width" : 140}
		});
		function selectpro(){
		   var address = $("#province option:selected ").text();
           if($("#province option:selected ").text()!="请选择"){
			   $("#address").val(address);
			   $("#addressid").val($("#province").val());
			   $("#size").val(11);
		   }
		    //setPlace(address);
		}
		function selectcity(){
			   var address = $("#province option:selected ").text()+$("#city option:selected ").text();
			   if($("#city option:selected ").text()!="请选择"){
			       $("#address").val(address);
			       $("#addressid").val($("#city").val());
			       $("#size").val(13);
		       }
		}
		function selecttown(){
			var address = $("#province option:selected ").text()+$("#city option:selected ").text()+$("#town option:selected ").text();
	          if($("#town option:selected ").text()!="请选择"){
		          $("#address").val(address);
				  $("#addressid").val($("#town ").val());
				  $("#size").val(15);
			  }
		     //setPlace(address);
		}
	
		// 用经纬度设置地图中心点
		function theLocation(obj,lng,lat,url,content){
			$('.m-box').removeClass('active');
			$(obj).parent().addClass('active');

			content1=utf8to16(base64decode(content));			
            if(lng!=""&&lat!= ""){
			   new_point = new BMap.Point(lng,lat);
                infoWindow = new BMap.InfoWindow(content1,opts);  // 创建信息窗口对象 
                map.openInfoWindow(infoWindow,new_point); //开启信息窗口
				map.panTo(new_point);
			}
		}
		function getmap(){
		   var p=$("#pages").val();
		   var type=$("#type").val();
		   var address=$("#address").val();
		   if(p<total){
			   $.getJSON('/map/showmap/', {p:p,type:type,address:address}, function(data) {

			   	    var jsonobj=eval(data.str);  
					for(var i=0;i<jsonobj.length;i++){  
					  	//var marker = new BMap.Marker(new BMap.Point(jsonobj[i].lng,jsonobj[i].lat));  // 创建标注
						content2=utf8to16(base64decode(jsonobj[i].address));	

					    //map.addOverlay(marker);               // 将标注添加到地图中
						//addClickHandler(content,marker);
						pt = new BMap.Point(jsonobj[i].lng,jsonobj[i].lat);
					    myIcon = new BMap.Icon(jsonobj[i].url,new BMap.Size(20,30));
						marker = new BMap.Marker(pt,{icon:myIcon});  // 创建标注
	                    map.addOverlay(marker);               // 将标注添加到地图中
					    addClickHandler(content2,marker);
					} 
			   	    $("#pages").val(data.pages);
			 	    htime=setTimeout("getmap()",200);
			   });
			}else{

				$("#maploading").hide();
			   // clearTimeout(htime);
			}
		}


		var base64DecodeChars = new Array(
		　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
		　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
		　　52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
		　　-1,　0,　1,　2,　3,  4,　5,　6,　7,　8,　9, 10, 11, 12, 13, 14,
		　　15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
		　　-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
		　　41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
		function base64encode(str) {
		　　var out, i, len;
		　　var c1, c2, c3;
		　　len = str.length;
		　　i = 0;
		　　out = "";
		　　while(i < len) {
		 c1 = str.charCodeAt(i++) & 0xff;
		 if(i == len)
		 {
		　　 out += base64EncodeChars.charAt(c1 >> 2);
		　　 out += base64EncodeChars.charAt((c1 & 0x3) << 4);
		　　 out += "==";
		　　 break;
		 }
		 c2 = str.charCodeAt(i++);
		 if(i == len)
		 {
		　　 out += base64EncodeChars.charAt(c1 >> 2);
		　　 out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
		　　 out += base64EncodeChars.charAt((c2 & 0xF) << 2);
		　　 out += "=";
		　　 break;
		 }
		 c3 = str.charCodeAt(i++);
		 out += base64EncodeChars.charAt(c1 >> 2);
		 out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
		 out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
		 out += base64EncodeChars.charAt(c3 & 0x3F);
		　　}
		　　return out;
		}
		function base64decode(str) {
		　　var c1, c2, c3, c4;
		　　var i, len, out;
		　　len = str.length;
		　　i = 0;
		　　out = "";
		　　while(i < len) {
		 /* c1 */
		 do {
		　　 c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
		 } while(i < len && c1 == -1);
		 if(c1 == -1)
		　　 break;
		 /* c2 */
		 do {
		　　 c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
		 } while(i < len && c2 == -1);
		 if(c2 == -1)
		　　 break;
		 out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));
		 /* c3 */
		 do {
		　　 c3 = str.charCodeAt(i++) & 0xff;
		　　 if(c3 == 61)
		　return out;
		　　 c3 = base64DecodeChars[c3];
		 } while(i < len && c3 == -1);
		 if(c3 == -1)
		　　 break;
		 out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));
		 /* c4 */
		 do {
		　　 c4 = str.charCodeAt(i++) & 0xff;
		　　 if(c4 == 61)
		　return out;
		　　 c4 = base64DecodeChars[c4];
		 } while(i < len && c4 == -1);
		 if(c4 == -1)
		　　 break;
		 out += String.fromCharCode(((c3 & 0x03) << 6) | c4);
		　　}
		　　return out;
		}
		function utf16to8(str) {
		　　var out, i, len, c;
		　　out = "";
		　　len = str.length;
		　　for(i = 0; i < len; i++) {
		 c = str.charCodeAt(i);
		 if ((c >= 0x0001) && (c <= 0x007F)) {
		　　 out += str.charAt(i);
		 } else if (c > 0x07FF) {
		　　 out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
		　　 out += String.fromCharCode(0x80 | ((c >>　6) & 0x3F));
		　　 out += String.fromCharCode(0x80 | ((c >>　0) & 0x3F));
		 } else {
		　　 out += String.fromCharCode(0xC0 | ((c >>　6) & 0x1F));
		　　 out += String.fromCharCode(0x80 | ((c >>　0) & 0x3F));
		 }
		　　}
		　　return out;
		}
		function utf8to16(str) {
		　　var out, i, len, c;
		　　var char2, char3;
		　　out = "";
		　　len = str.length;
		　　i = 0;
		　　while(i < len) {
		 c = str.charCodeAt(i++);
		 switch(c >> 4)
		 {
		　 case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
		　　 // 0xxxxxxx
		　　 out += str.charAt(i-1);
		　　 break;
		　 case 12: case 13:
		　　 // 110x xxxx　 10xx xxxx
		　　 char2 = str.charCodeAt(i++);
		　　 out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
		　　 break;
		　 case 14:
		　　 // 1110 xxxx　10xx xxxx　10xx xxxx
		　　 char2 = str.charCodeAt(i++);
		　　 char3 = str.charCodeAt(i++);
		　　 out += String.fromCharCode(((c & 0x0F) << 12) |
		　　　　((char2 & 0x3F) << 6) |
		　　　　((char3 & 0x3F) << 0));
		　　 break;
		 }
		　　}
		　　return out;
		}
</script>
