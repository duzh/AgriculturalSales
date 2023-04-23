
<!-- 主体内容开始 -->
<div class="wrapper f-oh" style="background:#f2f2f2;">
<div class="ur_here w960">
	<span><a href="javascript:;">首页</a>&nbsp;>&nbsp;<a href="javascript:;">供应大厅</a>&nbsp;>&nbsp;作业相册</span>
</div>
<div class="gy_zyxc_box w960">
	<div class="title">作业相册</div>
    <ul class="clearfix">
        {% for val in data %}
    	<li>
        	<a href="#">
            	<div class="imgs">
                	<img src="images/shop-recommend-img1.jpg" width="180" height="180" />
                </div>
                <div class="msg"><?php echo $_operate_type[$te] ?$_operate_type[$te] : ''; ?></div>
            </a>
        </li>
        {% endfor %}

    	
    </ul>
</div>
</div>
<!-- 主体内容结束 -->
