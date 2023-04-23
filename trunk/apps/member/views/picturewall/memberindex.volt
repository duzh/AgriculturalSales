	<div class="kuai1 f-oh">

		<div class="tFarm-header w1185 mtauto f-oh">
			<div class="m-contianer f-oh">
				<div class="m-logo f-fl">
					<img src="{{ constant('STATIC_URL') }}mdg/version2.4/images/trusted-farm/m-logo.png">
				</div>
				<div class="line f-fl"></div>
				<div class="m-content">
					<div class="name">
						<strong>大草原农庄</strong>
						<font>可信农场</font>
					</div>
					<div class="intro">
						<strong>大草原农庄</strong>位于内蒙古XX大草原，成立于1997年，曾连续5年获得国家优质农场称号。主营甜玉米、红辣椒。
					</div>
				</div>
			</div>
		</div>

		<div class="tFarm-nav w1185 mtauto f-oh">
			<div class="nav-list f-tac">
				<a href="#">农场首页</a> |
				<a href="#">种植过程</a> |
				<a class="active" href="#">所有产品</a> |
				<a href="#">资质认证</a> |
				<a href="#">图片墙</a> 
			</div>
		</div>
	<input type="hidden" value="{{count}}" id="count-num">
		<!-- 列表 -->
		<div class="grid f-oh" id="masonry">
			<ul class="grid-item">
				
			</ul>
			<ul class="grid-item">
				
			</ul>
			<ul class="grid-item">
				
			</ul>
			<ul class="grid-item">
				
			</ul>
		</div>

	</div>

	<!-- 底部 -->

	<script>
	//瀑布流
	var num = 0;
	function gen_li()
	{
		var oLi=document.createElement('li');
		// 假设一个数组
		var conArr = {{credible_farm_picture}};
		var countNum = parseInt($('#count-num').val());

		//var $num = parseInt(Math.random()*countNum); // 设定数组随机下标
		
		oLi.innerHTML='<div class="picture-wall"><a href="#"><div class="imgs"><img src="' + conArr[num].picture_path + '" height="250" width="266"></div><div class="imgName">' + conArr[num].title+ '</div><div class="imgCon">'+ conArr[num].desc +'</div></a></div>';
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
		
		for(var i=0;i<12;i++)
		{
			var oLi=gen_li();
			var oUl=findLowestUl(); 
			
			//每次都往最短的ul里面插入li——保证最终的总高度尽量接近
			oUl.appendChild(oLi);
		}
		
		window.onscroll=function ()
		{
			//if(count>=4)return;
			
			var scrollTop=document.documentElement.scrollTop||document.body.scrollTop;
			
			if(document.body.offsetHeight-document.documentElement.clientHeight-scrollTop<900)
			{
		
				for(var i=0;i<12;i++)
				{
					var oLi=gen_li();
					var oUl=findLowestUl();
					
					//每次都往最短的ul里面插入li——保证最终的总高度尽量接近
					oUl.appendChild(oLi);
				}
				
				count++;
			}
		};
	};
	
	// 创建li
	// 
	
	// var imgDate = {{credible_farm_picture}};

	// function createLi(){
	// 	var oLi=document.createElement('li');
	// 	return oLi;	
	// }


	// window.onload = function(){
	// 	var aUl=document.getElementById('masonry').getElementsByTagName('ul');

	// 	function createAll(){
	// 		var i=0;
	// 		var timer=setInterval(function(){
	// 			i++;
	// 			var oLi=createLi();
				
	// 			//把ul变成数组
	// 			var arr=[];
				
	// 			for(var j=0; j<aUl.length; j++){
	// 				arr[j]=aUl[j];
	// 			}
				
	// 			//给数组排序
	// 			arr.sort(function(ul1,ul2){
	// 				return ul1.offsetHeight-ul2.offsetHeight;
	// 			});

	// 			if(i > imgDate.length){
	// 				return false;
	// 				clearInterval(timer);
	// 			}else{
	// 				arr[0].appendChild(oLi);
	// 				var oLiHTML = '';
	// 				oLiHTML += '<div class="picture-wall"><a href="#"><div class="imgs">';
	// 				oLiHTML += '<img src="{{constant("ITEM_IMG")}}' + imgDate[i-1].picture_path +'" height="250" width="266">';
	// 				oLiHTML += '</div><div class="imgName">' + imgDate[i-1].title + '</div><div class="imgCon">' + imgDate[i-1].desc + '</div></a></div>';
	// 				oLi.innerHTML=oLiHTML;
	// 				//关闭定时器
	// 				if(i==10){
	// 					clearInterval(timer);
	// 				}
	// 			}
	// 		},10)
	// 	}

	// 	createAll();

		
	// 	window.onscroll=function(){
	// 		var scrollT=document.documentElement.scrollTop || document.body.scrollTop;
	// 		var windowH = document.documentElement.clientHeight || document.body.clientHeight;
	// 		var scrollH = document.documentElement.scrollHeight || document.body.scrollHeight;

	// 		var oLiLength = document.getElementById('masonry').getElementsByTagName('li').length;

	// 		var scrollBottom=scrollT+windowH;
			
	// 		if(imgDate.length > 10){
	// 			if(scrollBottom>=scrollH && oLiLength < imgDate.length){
	// 				createAll();
	// 			}	
	// 		}else{
	// 			return false;
	// 		}
	// 	}
	// }
	</script>
</body>
</html>