
<!-- 头部 start -->
{{ partial('layouts/shop_header') }}
<!-- 头部 end -->
<div class="ur_here w960">
     <span>{{ partial('layouts/ur_here') }}查看店铺</span>
</div>
<div class="shop_decora w960 clearfix">
    {{ partial('layouts/shop_left') }}
    <div class="decora_right f-fr">
    	<div class="shop_address">
        	<h6 class="clearfix">
            	<span class="f-fs14">下边是您的店铺地址</span>
                <!-- <a class="f-fr" href="#">返回栏目列表</a> -->
            </h6>
            <div class="shop_link">
            	<span>店铺链接地址：<a href="http://{{shop.shop_link}}.5fengshou.com/store/index" target="_blank">http://{{shop.shop_link}}.5fengshou.com</a></span>
            </div>
        </div>
    </div>
</div>

<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->
</body>
</html>
