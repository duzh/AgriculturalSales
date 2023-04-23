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
    </style>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ocQy04ecp2pLe6SlT4cpyiu7"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
</head>
<body>
    <!--地址搜索-->
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
{{ partial('layouts/footer') }}
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    map.centerAndZoom(new BMap.Point({{lngfirst}},{{latfirst}}),8);
    map.enableScrollWheelZoom();
    map.addControl(new BMap.NavigationControl()); // 添加左上角的标尺工具
    map.addControl(new BMap.ScaleControl());      //添加比例尺
    map.addControl(new BMap.OverviewMapControl()); //添加缩略地图控件

    opts = {
            width : 250,         // 信息窗口宽度
            height: 80,          // 信息窗口高度
            title : "村级服务站" , // 信息窗口标题
            enableMessage:false//设置允许信息窗发送短息
    };
    //初始化数据
    var data_info=[{{str}}];
    //缓冲池
    var markers = [];
    //循环压入聚合
    for(var i=0;i<data_info.length;i++){
        //创建聚合
        var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));
        // 创建标签内容
        var content = data_info[i][2];
        markers.push(marker);
        //map.addOverlay(marker);               // 将标注添加到地图中
        //创建标签
        addClickHandler(content,marker);
    }
    //创建标签
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
    //最简单的用法，生成一个marker数组，然后调用markerClusterer类即可。
    var markerClusterer = new BMapLib.MarkerClusterer(map, {markers:markers});
</script>
<script type="text/javascript">
total={{total}};
$(function(){
   // setTimeout("getmap()",100);
    getmap();
});
var $ld5 = $(".selectAreas");
$ld5.ld({ajaxOptions:{"url":"/ajax/getareasfull"},
    defaultParentId : 0,
    style : {"width" : 140}
});
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
    if(address!="市辖区"){
     setPlace(address);
     }
}
function getmap(){
   var p=$("#pages").val();
   
   if(p<total){
        $.getJSON('/index/showservicemap/', {p:p}, function(data) {
            var jsonobj=eval(data.str);  
            for(var i=0;i<jsonobj.length;i++){  

                //创建聚合
                var marker = new BMap.Marker(new BMap.Point(jsonobj[i].lng,jsonobj[i].lat));
                // 创建标签内容
                var content = jsonobj[i].address;
                markers.push(marker);
                //map.addOverlay(marker);               // 将标注添加到地图中
                //创建标签
                addClickHandler(content,marker);
                new BMapLib.MarkerClusterer(map, {markers:markers});
            }
            //var markerClusterer = new BMapLib.MarkerClusterer(map, {markers:markers}); 
            $("#pages").val(data.pages);
            htime=setTimeout("getmap()",100);
       });
    }else{
        clearTimeout(htime);
    }
}
</script>
