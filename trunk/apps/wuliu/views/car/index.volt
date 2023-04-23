{{ partial('layouts/page_header') }}
	<div class="wuliu_body_v2">
		<div class="wuliu_v2 w1190 mtauto">
			<div class="mianbao_v2">
				<a href="/">首页</a>
				<span>&gt;</span>
				<a href="/wuliu/index/">物流信息</a>
				<span>&gt;</span>
				<a href="/wuliu/car/index/">车源信息</a>
			</div>

			<div class="wuliu_left_v2 f-fl">
				<div class="search_tab_v2 search_two_v2">
					<div class="searchBtn_v2 f-fl" id="searchBtn_v2">
						<ul>
							<li>
								<span class="cheyuan_span_v2">车源信息</span>
							</li>
						</ul>
					</div>
					<div class="searchCon_v2 f-fl" id="searchCon_v2">
						{{ form("car/index", "method":"get", "autocomplete" : "off", 'id' : 'createCar') }}
						<div class="search_con_v2" style="display:block;">
							<div class="search_selectBox_v2">
								<span>出发地：</span>
							    <select name="start_pid" class='car_start wl-select-s' id="">
					              <option value="">省份</option>
					            </select>
					            <select name="start_cid" class='car_start wl-select-s' id="">
					              <option value="">城市</option>
					            </select>

					            <select name="start_did" class='car_start wl-select-s' id="">
					              <option value="">县/区</option>
					            </select>
							</div>
							<div class="search_selectBox_v2">
								<span>目的地：</span>
							    <select name="end_pid" class='car_end wl-select-s' id="">
					              <option value="">省份</option>
					            </select>
					            <select name="end_cid" class='car_end wl-select-s' id="">
					              <option value="">城市</option>
					            </select>
					            <select name="end_did" class='car_end wl-select-s' id="">
					              <option value="">县/区</option>
					            </select>
							</div>
							<div class="search_sub_v2">
								<input type="submit" value="搜索">
							</div>
						</div>
						</form>
					</div>
					<div class="fabuBtn_con_v2 f-fl">
						<a href="/wuliu/car/new"><span>发布车源</span></a>
					</div>
				</div>

				<div class="new_info_v2">
					<div class="new_infoTitle_v2">
						<strong>最新车源信息</strong>
						<a href="#">更多&gt;</a>
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
							<span class="span_8_v2">预计发车时间</span>
							<span class="span_9_v2">详情</span>
						</li>
					</ul>
					<ul class="new_infoTop_ul_v2 new_infoBot_ul_v2">
						{% for key ,item in data['items'] %}
						  <li class="">
                            <span class="span_1_v2">
                            {{
							item['start_dname'] ? item['start_dname'] : ( item['start_cname'] ? item['start_cname'] : (item['start_pname'] ? item['start_pname'] : "暂无"))
							}}
                            </span>
							<span class="span_2_v2">{{
							item['end_dname'] ? item['end_dname'] : ( item['end_cname'] ? item['end_cname'] : (item['end_pname'] ? item['end_pname'] : "暂无"))
							}}</span>
							<span class="span_3_v2"><?php echo isset($_BOX_TYPE[$item['box_type']]) ? $_BOX_TYPE[$item['box_type']] : ''; ?></span>
							<span class="span_4_v2"><?php echo isset($_BODY_TYPE[$item['body_type']]) ? $_BODY_TYPE[$item['body_type']] : ''; ?></span>
							<span class="span_5_v2">
							{% if item['light_goods'] > 0 %} <font class="li_red_v2" >{{ item['light_goods']}}</font>
						   元/吨{%  else %} 面议 {% endif %}
					
						    </span>
							<span class="span_6_v2">
							{% if item['heavy_goods'] > 0 %} <font class="li_red_v2">{{ item['heavy_goods']}}</font>
						    元/吨{%  else %} 面议 {% endif %}
						    </span>
							<span class="span_7_v2">{{ item['contact_man']}}</span>
							<span class="span_8_v2">{{ item['depart_time'] ? date('Y-m-d' , item['depart_time'] ) : '' }}</span>
							<span class="span_9_v2">
							<a href="/wuliu/car/get/{{ item['car_id']}}" class="li_chakan_v2">查看</a>
							</span>
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
						<strong>车源快速导航</strong>
						<a href="/wuliu/car/index">更多&gt;</a>
					</div>
					<div class="kuaisu_con_v2">
						{% for key ,item in carNavs %}
						{% if item['start_pid']!=0 %}
						<a href="/wuliu/car/index?start_pid={{ item['start_pid']}}">{{ item.start_pname }}</a>
						{% endif %}
						{% endfor %}
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
function go(){
var p=$("#p").val();
 var count = {{total_count}};
 if(p>count){
    p = count;
 }
 location.href="/wuliu/car/index?p="+p;
}
$(function(){

	$(".car_start").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
	    defaultParentId : 0,
	    {% if start_areas %}
	    texts:['{{ start_areas}}'],
	    {% endif %}

	    style : {"width" : 118}
	});
	//  目的地
	$(".car_end").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
	    defaultParentId : 0,
	    {% if end_areas %}
	    texts:['{{ end_areas}}'],
	    {% endif %}
	       style : {"width" : 118}
	});
});
</script>
{{ partial('layouts/footer') }}
<style>
.supply-hall-page{ text-align:right; padding-right: 20px;}
.supply-hall-page a{ display: inline-block; text-align: center; line-height: 28px; height:28px; padding:0 12px; border:1px solid #eee; color:#333; margin:0 3px;}
.supply_hall-page a:hover{ color:#f9ab14;}
.supply-hall-page a.active{ display: inline-block; height:28px; padding:0 12px; border:1px solid #eee; background:#f9ab14; color:#fff;}
.supply-hall-page a.active:hover{ color:#fff;}
</style>
</body>
</html>