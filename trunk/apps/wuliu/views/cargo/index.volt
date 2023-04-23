{{ partial('layouts/page_header') }}
<div class="wuliu_body_v2">
	<div class="wuliu_v2 w1190 mtauto">
		<div class="mianbao_v2">
			<a href="/">首页</a>
			<span>&gt;</span>
			<a href="/wuliu/index/">物流信息</a>
			<span>&gt;</span>
			<span>货源信息</span>
		</div>

		<div class="wuliu_left_v2 f-fl">
			<div class="search_tab_v2 search_two_v2">
				<div class="searchBtn_v2 f-fl" id="searchBtn_v2">
					<ul>
						<li>
							<span class="huoyuan_span_v2">货源信息</span>
						</li>
					</ul>
				</div>
				<div class="searchCon_v2 f-fl" id="searchCon_v2">
					{{ form("cargo/index", "method":"post","id":"addpur") }}
					<div class="search_con_v2" style="display:block;">
						<div class="search_selectBox_v2">
							<span>出发地：</span>
						       <select class="wl-select-s selectAreas" name="start_pid">
									<option value="">请选择</option>

								</select>
								<select class="wl-select-s selectAreas" name="start_cid">
									<option value="">请选择</option>

								</select>
								<select class="wl-select-s selectAreas" name="start_did">
									<option value="">请选择</option>

								</select>
						</div>
						<div class="search_selectBox_v2">
							<span>目的地：</span>
							<select class="wl-select-s endselectAreas" name="end_pid">
								<option value="">请选择</option>
							</select>
							<select class="wl-select-s endselectAreas" name="end_cid">
								<option value="">请选择</option>
							</select>
							<select class="wl-select-s endselectAreas" name="end_did">
								<option value="">请选择</option>
							</select>
						</div>
						<div class="search_sub_v2">
							<input type="submit" value="搜索">
						</div>
					</div>
					</form>
				</div>
				<div class="fabuBtn_con_v2 f-fl">
					<a href="/wuliu/cargo/new"><span>发布货源</span></a>
				</div>
			</div>

			<div class="new_info_v2">
				<div class="new_infoTitle_v2">
					<strong>最新货源信息</strong>
					<a href="/wuliu/cargo/index">更多&gt;</a>
				</div>
				<ul class="new_infoTop_ul_v2">
					<li>
						<span class="span_1_v2">出发地</span>
						<span class="span_2_v2">目的地</span>
						<span class="span_3_v2">箱型</span>
						<span class="span_4_v2">车体</span>
						<span class="span_5_v2">轻货价</span>
						<span class="span_6_v2">重货价</span>
						<span class="span_7_v2">联系人</span>
					<!-- 	<span class="span_8_v2">预计发车时间</span> -->
						<span class="span_9_v2">详情</span>
					</li>
				</ul>
				<ul class="new_infoTop_ul_v2 new_infoBot_ul_v2">
					{% for item in data['items']%}
						<li class="">
						<span class="span_1_v2"><?php echo Mdg\Models\CargoInfo::GetAreaName($item['start_cname'],$item['start_pname'])?></span>
						<span class="span_2_v2"><?php echo Mdg\Models\CargoInfo::GetAreaName($item['end_cname'],$item['end_pname'])?></span>
						<span class="span_3_v2">{{item['goods_name'] ? item['goods_name'] : '暂无'}}</span>
						<span class="span_4_v2"><?php echo Mdg\Models\CargoInfo::$_body_type[$item['body_type']] ? Mdg\Models\CargoInfo::$_body_type[$item['body_type']] : '暂无';?></span>

						<span class="span_5_v2">
							{% if item['goods_weight'] > 0 %} <font class="li_red_v2" >{{ item['goods_weight']}}</font>
						   /吨{%  else %} 暂无 {% endif %}
						</span>
						<span class="span_6_v2">
							{% if item['goods_size'] > 0 %} <font class="li_red_v2" >{{ item['goods_size']}}</font>
						   /方{%  else %} 暂无 {% endif %}
						</span>
						<span class="span_7_v2">{{item['contact_man'] ? item['contact_man'] : '暂无'}}</span>
						<!-- <span class="span_8_v2">2015-07-01</span> -->
						<span class="span_9_v2"><a href="/wuliu/cargo/look/{{item['goods_id']}}" class="li_chakan_v2">查看</a></span>
						</li>
					{% endfor %}	
				</ul>
			    <div class="esc-page mt30 mb30 f-tac f-fr">
					{{ data['pages'] }}
					<span>
						<label>去</label>
						<input type="text" name="p"  id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1"/>
						<label>页</label>
					</span>
					<input class="btn" type="button" value="确定" onclick="go()"/>
				</div>
			</div>
		</div>

		<div class="wuliu_right_v2 f-fr">
			<div class="kuaisu_v2">
				<div class="kuaisu_title_v2">
					<strong>货源快速导航</strong>
					<a href="/wuliu/cargo/index">更多&gt;</a>
				</div>
				<div class="kuaisu_con_v2">
					 {% for key ,item in CarGoNavs %}
					 {% if item['start_pid']!=0 %}
					 <a href="/wuliu/cargo/index/{{ item.start_pid}}">{{ item.start_pname}}</a>
					 {% endif %}
					 {% endfor %}
				</div>
			</div>
		</div>
	</div>
</div>
<!-- 底部 -->{{ partial('layouts/footer') }}
<script type="text/javascript">
	function go(){
	var p=$("#p").val();
	 var count = {{total_count}};
	 if(p>count){
	    p = count;
	 }
	 location.href="/wuliu/cargo/index?p="+p;
	}
</script>
<script>
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
defaultParentId : 0,
{% if start_areas %}
texts : ['{{start_areas}}'],
{% endif %}
style : {"width" : 118}
});
//  目的地
$(".endselectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if end_areas %}
    texts:['{{end_areas}}'],
    {% endif %}
    style : {"width" :118}
});
</script>
<style>
.supply-hall-page{ text-align:right; padding-right: 20px;}
.supply-hall-page a{ display: inline-block; text-align: center; line-height: 28px; height:28px; padding:0 12px; border:1px solid #eee; color:#333; margin:0 3px;}
.supply_hall-page a:hover{ color:#f9ab14;}
.supply-hall-page a.active{ display: inline-block; height:28px; padding:0 12px; border:1px solid #eee; background:#f9ab14; color:#fff;}
.supply-hall-page a.active:hover{ color:#fff;}
</style>