<div class="dialog" id="view_photos">
	<div class="photo_alert f-oh">
		<a class="prev-btn" href="javascript:;"></a>
		<div class="photo_box">
	        <ul>
	            <li class="active"><img src="images/shop-recommend-img1.jpg" width="350" height="350" /></li>
	            <li><img src="images/shop-recommend-img2.jpg" width="350" height="350" /></li>
	            <li><img src="images/shop-recommend-img3.jpg" width="350" height="350" /></li>
	            <li><img src="images/shop-recommend-img1.jpg" width="350" height="350" /></li>
	            <li><img src="images/shop-recommend-img2.jpg" width="350" height="350" /></li>
	            <li><img src="images/shop-recommend-img3.jpg" width="350" height="350" /></li>
	        </ul>
	    </div>
	    <a class="next-btn" href="javascript:;"></a>
	</div>
</div>

	<script>
		$(function(){
			//查看相册
			(function(){
				$('.photo_layer').show();
				$('.photo_alert').show();

				var now = 0;
				var $li = $('.photo_alert .photo_box li');
				function nextImg(){
					now ++;
					$li.removeClass('active');
					$li.eq(now).addClass('active');
					if(now == $li.length - 1){
						now = -1;
					}
				};
				function prevImg(){
					now --;
					$li.removeClass('active');
					$li.eq(now).addClass('active');
					if(now == -1){
						now = $li.length - 1;
					}
				};
				$('.prev-btn').on('click', function(){
					prevImg();
				});
				$('.next-btn').on('click', function(){
					nextImg();
				});
			})();

		});
	</script>

</body>
</html>
