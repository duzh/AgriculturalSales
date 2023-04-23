<!-- 汇资讯样式、js -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/hzx.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/hzx.js"></script>


<!-- 头部 start-->
{{ partial('layouts/advisory_header') }}


	<div class="wrapper">
		<div class="w1190 mtauto f-oh">
			<div class="hzx-carousel clearfix">
				<!--主轮播 start-->
				<div class="hzx-slider f-fl f-oh">
					<div class="slide-images f-fl">
						{% for key,val in noticeList['carousel'] %}
						<div {% if key == 0 %} class="imgs focusIn" {% else %} class="imgs" {% endif %}>
							<a href="/advisory/adinfo?id={{val['id'] }}"><img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}"  height="325" width="485"></a>
						</div>
						{% endfor %}
					</div>
					
					<div class="slide-con f-fl">
					{% for key1,val1 in noticeList['carousel'] %}
						<div {% if key1 == 0 %} class="conBnt active" {% endif %} class="conBnt active">{{ val1['title'] }}</div>

					{% endfor %}
					</div>
				</div>
				<!--主轮播 end-->
		 
				<div class="hzx-tabChange f-fr f-oh">
					<ul class="tab-btn f-oh">
						<li class="active">公告板</li>
						<li class="border">最新公告</li>
					</ul>
					<!-- 公告板 -->

					<div class="tabBox" style="display:block;">
                        {% for key,val in noticeList['notice'] %}
						<div class="name">
							<a href="/advisory/adinfo?id={{ val['id'] }}">{{ val['title'] }}</a>
						</div>
						<div class="time">{{ date('Y/m/d',val['addtime']) }}</div>
						<div class="imgs f-tac">
							<a href="/advisory/adinfo?id={{ val['id'] }}">
								<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}" width="260" height="172" alt="">
							</a>
						</div>
						<div class="con">
							<a href="/advisory/adinfo?id={{ val['id'] }}"><?php if(!empty($val['content'])){echo strip_tags(htmlspecialchars_decode(stripslashes($val['content'])));}?></a>
						</div>
                        {% endfor %}
					</div>

					<!-- 最新公告 -->
					<div class="tabBox">
						<ul class="list">
							{% for key,val in noticeList['newNotice'] %}
							<li>
								<a href="/advisory/adinfo?id={{ val['id'] }}">{{ val['title'] }}</a>
							</li>
							{% endfor %}
						</ul>
					</div>
				</div>
			</div>
			
			<!-- 新闻 -->
			<div class="hzx-news f-oh">
				<div class="left f-fl f-oh">
					<div class="title f-oh">
						<span class="f-fl">新闻</span>
						<a class="f-fr" href="/advisory/newslist?cid={{ catId[0] }}">更多 ></a>
					</div>
					<div class="box f-oh">

						<div class="focus-left f-fl">
							<div class="focusImg hzx-m-focus1">
								<div class="imgBox">
									{% for key,val in newsList['reList'] %}
									<div {% if key == 0 %} class="imgs focusIn" {% else %} class="imgs" {% endif %}>
										<a href="/advisory/adinfo?id={{ val['id'] }}">
											<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="180" width="270" alt="">
											<div class="img-layer"></div>
											<div class="name"><?php echo \Lib\Func::sub_str($val['title'], 10); ?></div>
										</a>
									</div>
									{% endfor %}
								</div>	
								<ul class="imgBtn">
									<li class="active">1</li>
									<li>2</li>
									<li>3</li>
								</ul>
							</div>
							
							{% for key,val in newsList['imgTitle'] %}
							<div class="focus-list">
								<a href="/advisory/adinfo?id={{ val['id'] }}">
									<div class="m-img f-fl">
										<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com/mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="80" width="120" alt="">
									</div>
									<div class="m-name f-fr">{{ val['title'] }}</div>
									<div class="m-con f-fr"><?php echo \Lib\Func::sub_str($val['description'], 50); ?></div>
								</a>
							</div>
							{% endfor %}
						</div>
						<div class="focus-right f-fr">
						{% for key,val in newsList['newsList'] %}
							<div class="r-list">
								<a href="/advisory/adinfo?id={{ val['id'] }}">
									<div class="name">{{ val['title'] }}</div>
									<div class="content"><?php echo \Lib\Func::sub_str($val['description'], 50); ?></div>
									<div class="time">{{ date('Y-m-d',val['addtime']) }}</div>
								</a>
							</div>
						{% endfor %}
						</div>
					</div>
				</div>
				
				<div class="chart f-fr f-oh">
					<div class="m-title">
						<span>排行榜</span>
					</div>
					{% for key,val in newsList['hotList'] %}
					<div class="m-list">
						<a href="/advisory/adinfo?id={{ val['id'] }}">
							<font class="f-fl">
								<span {% if key+1==1 or key+1==2 or key+1==3 %} class="active" {% else %} class="" {% endif %}>{{ key+1 }}</span>
								<?php $arr=$val['title'];echo mb_substr($arr,0,12);?>
							</font>
							<em class="f-fr">{{ val['count'] }}</em>
						</a>
					</div>
					{% endfor %}
				</div>
			</div>
			
			
			<!-- 动态 -->
			<div class="hzx-news hzx-dynamic f-oh">
				<div class="left f-fl f-oh">
					<div class="title f-oh">
						<span class="f-fl">动态</span>
						<a class="f-fr" href="/advisory/newslist?cid={{ catId['2'] }}">更多 ></a>
					</div>
					<div class="box f-oh">
						<div class="focus-left f-fl">
						{% for key,val in dynamicList['reList'] %}
							<div class="focusImg">
								<div class="imgBox">
									<div  {% if key == 0 %} class="imgs focusIn" {% else %} class="imgs" {% endif %}>
										<a href="/advisory/adinfo?id={{ val['id'] }}">
											<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com/mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="180" width="270" alt="">
											<div class="img-layer"></div>
											<div class="name">{{val['title']}}</div>
										</a>
									</div>
								</div>
							</div>
						{% endfor %}	
						</div>
						
						<div class="focus-right f-fr">
						{% for key,val in dynamicList['newsList'] %}
							<div class="r-list">
								<a href="/advisory/adinfo?id={{ val['id'] }}">
									<div class="name">{{ val['title'] }}</div>
									<div class="content"><?php echo \Lib\Func::sub_str($val['description'], 50); ?></div>
									<div class="time">{{ date('Y-m-d',val['addtime']) }}</div>
								</a>
							</div>
						{% endfor %}
						</div>	
					</div>
				</div>
				
				<div class="chart f-fr f-oh">
					<div class="m-title">
						<span>排行榜</span>
					</div>
					{% for key,val in dynamicList['hotList'] %}
					<div class="m-list">
						<a href="/advisory/adinfo?id={{ val['id'] }}">
							<font class="f-fl">
								<span {% if key+1==1 or key+1==2 or key+1==3 %} class="active" {% else %} class="" {% endif %}>{{ key+1 }}</span>
								<?php $arr=$val['title'];echo mb_substr($arr,0,12);?>
									
							</font>
							<em class="f-fr">{{ val['count'] }}</em>
						</a>
					</div>
					{% endfor %}
				</div>
			</div>
		
			<!-- 活动 -->
			<div class="hzx-news hzx-active f-oh">
				<div class="left f-fl f-oh">
					<div class="title f-oh">
						<span class="f-fl">活动</span>
						<a class="f-fr" href="/advisory/newslist?cid={{ catId['1'] }}">更多 ></a>
					</div>
					<div class="box f-oh">

						<div class="focus-left f-fl">
							<div class="focusImg hzx-m-focus2">
								<div class="imgBox">
									{% for key,val in activeList['reList'] %}
									<div {% if key == 0 %} class="imgs focusIn" {% else %} class="imgs" {% endif %} >
										<a href="/advisory/adinfo?id={{ val['id'] }}">
											<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="180" width="270" alt="">
											<div class="img-layer"></div>
											<div class="name"><?php echo \Lib\Func::sub_str($val['title'], 10); ?></div>
										</a>
									</div>
									{% endfor %}
								</div>
								<ul class="imgBtn">
									<li class="active">1</li>
									<li>2</li>
									<li>3</li>
								</ul>
							</div>
							{% for key,val in activeList['imgTitle'] %}	
							<div class="focus-list">
								<a href="/advisory/adinfo?id={{ val['id'] }}">
									<div class="m-img f-fl">
										<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="80" width="120" alt="">
									</div>
									<div class="m-name f-fr">{{ val['title'] }}</div>
									<div class="m-con f-fr"><?php echo \Lib\Func::sub_str($val['description'], 50); ?></div>
								</a>
							</div>
                            {% endfor %}
						</div>
						<div class="focus-right f-fr">
							{% for key,val in activeList['newsList'] %}
							<div class="r-list">
								<a href="/advisory/adinfo?id={{ val['id'] }}">
									<div class="name">{{ val['title'] }}</div>
									<div class="content"><?php echo \Lib\Func::sub_str($val['description'], 50); ?></div>
									<div class="time">{{ date('Y-m-d',val['addtime']) }}</div>
								</a>
							</div>
							{% endfor %}
						</div>
					</div>
				</div>
				
				<div class="albums f-fr f-oh">
					<div class="m-title">
						<span>活动相册</span>
					</div>
					<ul class="list gallery">
					{% for key,val in activeList['hotList'] %}
						<li>
							<a href="/advisory/adinfo?id={{ val['id'] }}">
								<img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{val['thumb']}}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="78" width="78" alt="">
							</a>
						</li>
					{% endfor %}
					</ul>
				</div>
			</div>
		</div>
	</div>




<!-- 底部 -->
{{ partial('layouts/footer') }}
	<!-- 图片放大预览 -->
    <link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/zoom.css" media="all">
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/zoom.min.js"></script>
	<style>
		#zoom{ *background:#000; *opacity:0.8; *filter:alpha(opacity:80);}
	</style>