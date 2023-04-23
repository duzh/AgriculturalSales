<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/trust-farm.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/trust-farm.js"></script>
 <!--头部--> 
{{ partial('layouts/orther_herder') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		 <!--农场头部--> 
		{{ partial('layouts/farm_header') }}
		
		<!-- 列表 -->
		<div class="grid f-oh" id="masonry">
			<ul class="grid-item gallery">
				
			</ul>
			<ul class="grid-item gallery">
				
			</ul>
			<ul class="grid-item gallery">
				
			</ul>
			<ul class="grid-item gallery">
				
			</ul>
		</div>
		<input type="hidden" value="{{count}}" id="count-num">

		
	</div>
</div>
<!--底部-->
{{ partial('layouts/orther_footer') }}
<script>
//瀑布流
var num = 0;
var countNum = parseInt($('#count-num').val());
function gen_li()
{
	var oLi=document.createElement('li');
	// 假设一个数组
	var conArr = {{credible_farm_picture}};

	//var $num = parseInt(Math.random()*countNum); // 设定数组随机下标
	
	oLi.innerHTML='<div class="picture-wall"><a href="http://yncmdg.b0.upaiyun.com'+ conArr[num].picture_path +'"><div class="imgs"><img src="http://yncmdg.b0.upaiyun.com' + conArr[num].picture_path + '" height="260" width="286"></div><div class="imgName">' + conArr[num].title+ '</div><div class="imgCon">'+ conArr[num].desc +'</div></a></div>';
	num ++;
	if(num > countNum){
		return false;
	};
	return oLi;
}
window.onload=function ()
{
	var count=1;
	var aUl=document.getElementById('masonry').getElementsByTagName('ul');
	
	function findLowestUl()
	{
		var min=9999999;
		var minIndex=-1;
		
		for(var i=0;i<aUl.length;i++)
		{
			if(aUl[i].offsetHeight<min)
			{
				min=aUl[i].offsetHeight;
				minIndex=i;
			}
		}
		
		return aUl[minIndex];
	}
	
	if(countNum < 12){
		for(var i=0;i<countNum;i++)
		{
			var oLi=gen_li();
			var oUl=findLowestUl(); 
			
			//每次都往最短的ul里面插入li——保证最终的总高度尽量接近
			oUl.appendChild(oLi);
		}
	}else{
		for(var i=0;i<12;i++)
		{
			var oLi=gen_li();
			var oUl=findLowestUl(); 
			
			//每次都往最短的ul里面插入li——保证最终的总高度尽量接近
			oUl.appendChild(oLi);
		}
	};
	
	window.onscroll=function ()
	{
		//if(count>=4)return;
		
		var scrollTop=document.documentElement.scrollTop||document.body.scrollTop;
		
		if(document.body.offsetHeight-document.documentElement.clientHeight-scrollTop<900)
		{
			if(countNum < 12){
				for(var i=0;i<countNum;i++)
				{
					var oLi=gen_li();
					var oUl=findLowestUl();
					
					//每次都往最短的ul里面插入li——保证最终的总高度尽量接近
					oUl.appendChild(oLi);
				}
			}else{
				for(var i=0;i<12;i++)
				{
					var oLi=gen_li();
					var oUl=findLowestUl();
					
					//每次都往最短的ul里面插入li——保证最终的总高度尽量接近
					oUl.appendChild(oLi);
				}
			};
			
			
			count++;
		}
	};
	
	var oLink = document.createElement('link');
	oLink.rel="stylesheet";
	oLink.href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/zoom.css";
	document.body.appendChild(oLink);

	var oScript = document.createElement('script');
	oScript.src="http://yncstatic.b0.upaiyun.com/mdg/version2.5/js/zoom.min.js";
	document.body.appendChild(oScript);
};
</script>
<style>
		#zoom{ *background:#000; *opacity:0.8; *filter:alpha(opacity:80);}
	</style>