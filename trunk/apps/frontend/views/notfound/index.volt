{{ partial('layouts/page_header') }} 
	<div class="wrapper">
		<div class="w1190 mtauto f-oh">

			<div class="undifined-page f-oh">
				<div class="links" id="back-home">
					<a class="link1" href="#">
						立即跳转到首页（<i id="num">3</i>）&gt;
					</a>
					<a class="link2" href="/">返回首页&nbsp;&gt;</a>
				</div>
			</div>

		</div>
	</div>

{{ partial('layouts/footer') }}
<script>
	$(function(){
		var i=3;
		var timer=null;

		function dao(){
			$('#num').text(i);
			i--;
			if(i==0){
				clearInterval(timer);
				location.href='/';
			}
		};
		dao();
		timer = setInterval(dao, 1000);
	});
</script>