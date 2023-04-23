{{ partial('layouts/page_header') }}

<div class="contianer pb30">
{{ partial('layouts/navigate') }}
    <div class="shop_symm w960 clearfix">
    	<div class="symm_left f-fl">
        	{{ partial('layouts/category_left') }}
            {{ partial('layouts/shoper_left') }}
            {{ partial('layouts/comments_left') }}
        </div>
        <!-- 右侧 start -->
        <div class="symm_right f-fr">
        	<div class="shop_intro">
            	<h6>店铺介绍</h6>
                <div class="information f-oh">
                    <div class="f-oh">
                	   <p class="f-ff0">{{shopcredit.shop_desc}}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- 右侧 end -->
    </div>
</div>

<!-- 底部开始 -->
{{ partial('layouts/footer') }}
<!-- 底部结束 -->
</body>
</html>
