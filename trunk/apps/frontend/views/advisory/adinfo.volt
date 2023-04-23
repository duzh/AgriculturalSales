<!-- 汇资讯样式、js -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/hzx.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/hzx.js"></script>


<!-- 头部 start-->
{{ partial('layouts/advisory_header') }}


<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs">
			<a href="/advisory/adindex">资讯首页</a>&nbsp;&gt;&nbsp;
			{% if catId=='3' %}<a href="/advisory/newslist?cid=3">新闻</a>&nbsp;&gt;&nbsp;列表详情 {% endif %}
			{% if catId=='7' %}<a href="/advisory/newslist?cid=7">动态</a>&nbsp;&gt;&nbsp;列表详情 {% endif %}
			{% if catId=='6' %}<a href="/advisory/newslist?cid=6">活动</a>&nbsp;&gt;&nbsp;列表详情 {% endif %}
			{% if catId=='8' %}<a href="/advisory/newslist?cid=8">公告</a>&nbsp;&gt;&nbsp;列表详情 {% endif %}
		</div>
		<div class="hzx-newsD-left f-fl">
			<div class="title">{{ info['infoMess']['title'] }}</div>
			<div class="time f-oh">
				<span class="f-fl">{{ date('Y-m-d',info['infoMess']['addtime']) }} </span>
				<span class="f-fr">阅读量：{{ info['infoMess']['count'] }}</span>
			</div>
			<div class="all-content">
				<!-- <p>{{ info['infoMess']['description'] }}</p> -->
				<!-- <div class="img f-tac">
					<img src="{{ constant('IMG_URL') }}{{ info['infoMess']['thumb'] }}" alt="" width="626" height="380">
				</div> -->
				<p>{{ info['infoMess']['content'] }}</p>
			</div>

			<!-- 	添加标签  -->
			<div class="add-new-tag clearfix">
				<font>标签：</font>
				<div class="box clearfix">
				{% if tags %}
				{% for val in tags %}
					<a href="/advisory/newslist?keys={{ val }}">
						<div class="tags">
							<span>{{ val }}</span>
						</div>
					</a>
				{% endfor %}
				{% endif %}
				</div>
			</div>
			
			<div class="share-box clearfix">
				<label class="f-fl">分享：</label>
				<!-- JiaThis Button BEGIN -->
				<div class="jiathis_style_24x24 f-fl">
					<div class="border"></div>
					<a class="jiathis_button_qzone"></a>
					<a class="jiathis_button_tsina"></a>
					<a class="jiathis_button_tqq"></a>
					<a class="jiathis_button_weixin"></a>
					<a class="jiathis_button_renren"></a>
					<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
					<a class="jiathis_counter_style"></a>
				</div>
				<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
				<!-- JiaThis Button END -->
			</div>
		</div>
		<div class="hzx-cate-right f-fr">
			<div class="hzx-tuijian-box mb20">
				<div class="title">热门文章</div>
				{% for key,val in info['hotMess'] %}
				<div class="focus-list">
					<a href="/advisory/adinfo?id={{ val['id'] }}">
						<div class="m-img f-fl">
							<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="80" width="120" alt="">
						</div>
						<div class="m-name f-fr">{{ val['title'] }}</div>
						<div class="m-con f-fr">{{ val['description'] }}...</div>
					</a>
				</div>
				{% endfor %}
			</div>
			<div class="hzx-tuijian-box hzx-about-box">
				<div class="title">相关推荐</div>
				{% for key,val in info['recomList'] %}
				<div class="hzx-tj-list">
					<a href="/advisory/adinfo?id={{ val['id'] }}">{{ val['title'] }}</a>
				</div>
				{% endfor %}
			</div>
		</div>

	</div>
</div>


<script type="text/javascript">
    function sub(){
         var url    = $("#url").val();
         var p      = $("#p").val();
         var tp     = $("#tp").val();
         if(p > tp || p == '' || p == 0){
            alert('输入页数有误');
            return;
         } else {
            location.href='/advisory/newslist?'+url+'&p='+p;
         }
    }
</script>


<!-- 底部 -->
{{ partial('layouts/footer') }}