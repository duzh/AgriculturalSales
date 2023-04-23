<!-- 汇资讯样式、js -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/hzx.css">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/hzx.js"></script>


<!-- 头部 start-->
{{ partial('layouts/advisory_header') }}


<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		<div class="bread-crumbs">
			<a href="/advisory/adindex">资讯首页</a>&nbsp;&gt;&nbsp;
			{% if catId=='3' %}新闻{% endif %}
			{% if catId=='7' %}动态{% endif %}
			{% if catId=='6' %}活动{% endif %}
			{% if catId=='8' %}公告{% endif %}
			{% if keywords !='' and catId=='' %} 搜索 {% endif %}
		</div>
		<div class="hzx-cate-left f-fl">
			<!-- 排序 -->
			<div class="small-select f-oh">
				<span class="select-term f-fl clearfix">
				{{orderName}}
				</span>
				<div class="m-page clearfix f-fr">
					{{newsList['newpages'] }}
				</div>
			</div>
			<!-- 列表 -->
			<div class="hzx-cate-list">
				{% for key,val in newsList['items'] %}
				<dl class="clearfix">
					<dt class="f-fl">
						<a href="/advisory/adinfo?id={{ val['id'] }}"><img src="{% if val['thumb']!=''  %}{{ constant('IMG_URL') }}{{ val['thumb'] }}{% else %}http://yncstatic.b0.upaiyun.com//mdg/version2.4/images/detial_b_img.jpg{% endif %}" height="160" width="240"></a>
					</dt>
					<dd class="name f-fr">
						<a href="/advisory/adinfo?id={{ val['id'] }}">{{ val['title'] }}</a>
					</dd>
					<dd class="content f-fr">
						<p>{{ val['description'] }}...</p>
					</dd>
					<dd class="time f-fr f-oh">
						<span class="f-fl">{{ date('Y/m/d',val['addtime']) }} </span>
						<span class="f-fr">阅读数：{{ val['count'] }}</span>
					</dd>
				</dl>
				{% endfor %}
			</div>
			
			<!-- 分页 -->
			<div class="esc-page mt30 mb30 f-tac f-fr">
				{% if newsList["total_pages"]>1 and newsList["total_pages"]!=0 %}
				{{newsList['pages'] }}
                    <span>
                        <label>去</label><input type="text" id="p" value="1" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/><label>页</label>
                    </span>
                    <input class="btn" type="submit" value="确定" onclick="sub()"/>
			
			 <input type="hidden" id="url" value="{{ url }}">
            <input type="hidden" name="tp" id="tp" value="{{newsList['total_pages']}}">
		   {% endif %}
		   </div>
		   </div>
		   
		{% if keys == '' %}
		<div class="hzx-cate-right f-fr">
			<div class="hzx-tuijian-box">
				<div class="title">相关推荐</div>
				{% for key,val in newsList['reList'] %}
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
		</div>
		{% endif %}
	</div>
</div>




<script type="text/javascript">
    function sub(){
         var url    = $("#url").val();
         var p      = $("#p").val();
         var tp     = $("#tp").val();
         if(parseInt(p) > parseInt(tp) || p == '' || p == 0){
         	p = tp;
         } 
            location.href='/advisory/newslist?'+url+'&p='+p;
         
    }
</script>


<!-- 底部 -->
{{ partial('layouts/footer') }}