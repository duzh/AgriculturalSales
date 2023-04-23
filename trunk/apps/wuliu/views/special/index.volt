{{ partial('layouts/page_header') }}

<div class="wuliu_body_v2">
	<div class="wuliu_v2 w1190 mtauto">
		<div class="mianbao_v2">
			<a href="/">首页</a>
			<span>&gt;</span>
			<a href="/wuliu/index/">物流信息</a>
			<span>&gt;</span>
			<span>专线信息</span>
		</div>

		<div class="wuliu_left_v2 f-fl">
			<div class="search_tab_v2 search_two_v2">
				<div class="searchBtn_v2 f-fl" id="searchBtn_v2">
					<ul>
						<li>
							<span class="zhuanxian_span_v2">专线信息</span>
						</li>
					</ul>
				</div>
				<div class="searchCon_v2 f-fl" id="searchCon_v2">
					{{ form("special/index", "method":"get", "autocomplete" : "off") }}
					<div class="search_con_v2" style="display:block;">
						<div class="search_selectBox_v2">
							<span>出发地：</span>
							<select name="province"  class="wl-select-s startAreas">
								<option value="">省份</option>
							</select>
							<select name="city"  class="wl-select-s startAreas">
								<option value="">城市</option>
							</select>
							<select name="district"  class="wl-select-s startAreas">
								<option value="">县/区</option>
							</select>
						</div>
						<div class="search_selectBox_v2">
							<span>目的地：</span>
						    <select name="endprovince"  class="wl-select-s endAreas ">
								<option value="">省份</option>
							</select>
							<select name="endcity"  class="wl-select-s endAreas ">
								<option value="">城市</option>
							</select>
							<select name="enddistrict"  class="wl-select-s endAreas ">
								<option value="">县/区</option>
							</select>
						</div>
						<div class="search_sub_v2">
							<input type="submit" value="搜索">
						</div>
					</div>
					</form>
				</div>
				<!-- <div class="fabuBtn_con_v2 f-fl">
					<a href="/wuliu/car/new"><span>发布专线</span></a>
				</div> -->
			</div>

			<div class="new_info_v2">
				<div class="new_infoTitle_v2">
					<strong>最新专线信息</strong>
					<a href="/wuliu/special/index">更多&gt;</a>
				</div>
				<ul class="new_infoTop_ul_v2">
					<li>
						<span class="span_1_v2">出发地</span>
						<span class="span_2_v2">目的地</span>
						<span class="span_3_v2">单程/返程</span>
						<span class="span_4_v2">轻货价</span>
						<span class="span_5_v2">重货价</span>
						<span class="span_6_v2">联系人</span>
						<span class="span_7_v2">查看详情</span>
					</li>
				</ul>
				<ul class="new_infoTop_ul_v2 new_infoBot_ul_v2">
			       {% for sc in data %}
						<li class="">
							<span class="span_1_v2">
								<?php echo Mdg\Models\CargoInfo::GetAreaName($sc->start_cname,$sc->start_pname)?>
							</span>
							<span class="span_2_v2">
								<?php echo Mdg\Models\CargoInfo::GetAreaName($sc->end_cname,$sc->end_pname)?>
							</span>
							<span class="span_3_v2">{% if sc.type==1 %}往返{% else %}单程{% endif %}</span>
							<span class="span_4_v2">{% if sc.light_price > 0 %}
							<font class="li_red_v2"> {{sc.light_price }}</font>
							元/方 {% else %}面议{% endif %}</span>
							<span class="span_5_v2">{% if sc.heavy_price > 0 %}
								<font class="li_red_v2">{{sc.heavy_price }}</font>
							元/吨 {% else %}面议 {% endif %}</span>
							<span class="span_6_v2">
								{{ sc.contact_man}}
							</span>
							<span class="span_7_v2">
							    <a href="/wuliu/special/look/{{sc.sc_id}}" class="wl-info-link">查看</a>
						    </span>
						</li>
				{% endfor %}
				</ul>
				<div class="esc-page mt30 mb30 f-tac f-fr">
				{{ pages }}
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
                 {{ partial('index/sc_navs') }}
			</div>
		</div>
	</div>
</div>
{{ partial('layouts/footer') }}
<script type="text/javascript">
	function go(){
	var p=$("#p").val();
	 var count = {{total_count}};
	 if(p>count){
	    p = count;
	 }
	 location.href="/wuliu/special/index?p="+p;
	}
</script>
<script>
(function(){
	$('.wl-new-infoList').hover(function(){
		$(this).toggleClass('wl-new-infoListHover');
	})
})();
$(".startAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (start_areas) %}
    texts : [{{ start_areas }}],
    {% endif %}
    style : {"width" : 118}
});
$(".endAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   {% if (endAreas) %}
    texts : [{{ endAreas }}],
    {% endif %}
    style : {"width" : 118}
});
</script>
<style>
.supply-hall-page{ text-align:right; padding-right: 20px;}
.supply-hall-page a{ display: inline-block; text-align: center; line-height: 28px; height:28px; padding:0 12px; border:1px solid #eee; color:#333; margin:0 3px;}
.supply_hall-page a:hover{ color:#f9ab14;}
.supply-hall-page a.active{ display: inline-block; height:28px; padding:0 12px; border:1px solid #eee; background:#f9ab14; color:#fff;}
.supply-hall-page a.active:hover{ color:#fff;}
</style>