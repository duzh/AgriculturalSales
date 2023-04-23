{{ partial('layouts/page_header') }}
	<div class="wuliu_body_v2">
		<div class="wuliu_v2 w1190 mtauto">
			<div class="mianbao_v2">
				<a href="#">首页</a>
				<span>&gt;</span>
				<span>物流信息</span>
			</div>
			<div class="wuliu_left_v2 f-fl">
                {{ partial('index/car_info') }}
				<div class="new_info_v2">
					<div class="new_infoTitle_v2">
						<strong>车源信息</strong>
						<a href="/wuliu/car/index">更多&gt;</a>
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
						{% for key ,item in newCarList['items'] %}
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
				</div>

				<div class="new_info_v2">
					<div class="new_infoTitle_v2">
						<strong>货源信息</strong>
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
							<!-- <span class="span_8_v2">预计发车时间</span> -->
							<span class="span_9_v2">详情</span>
						</li>
					</ul>
					<ul class="new_infoTop_ul_v2 new_infoBot_ul_v2">
						{% for item in CargoList['items']%}
						<li class="">
							<span class="span_1_v2">
								<?php echo Mdg\Models\CargoInfo::GetAreaName($item['start_cname'],$item['start_pname'])?></span>
							<span class="span_2_v2">
								<?php echo Mdg\Models\CargoInfo::GetAreaName($item['end_cname'],$item['end_pname'])?>
							</span>
							<span class="span_3_v2">
								<?php echo Mdg\Models\CargoInfo::$_box_type[$item['box_type']] ? Mdg\Models\CargoInfo::$_box_type[$item['box_type']] : '暂无';?>
							</span>
							<span class="span_4_v2">
								<?php echo Mdg\Models\CargoInfo::$_body_type[$item['body_type']] ? Mdg\Models\CargoInfo::$_body_type[$item['body_type']] : '暂无';?>
							</span>
							<span class="span_5_v2">
							{% if item['goods_size'] > 0 %} <font class="li_red_v2" >{{ item['goods_size']}}</font>
						   /方{%  else %} 暂无 {% endif %}
							</span>
							<span class="span_6_v2">
                            {% if item['goods_weight'] > 0 %} <font class="li_red_v2" >{{ item['goods_weight']}}</font>
						   /吨{%  else %} 暂无 {% endif %}</span>
							<span class="span_7_v2">{{item['contact_man'] ? item['contact_man'] : '暂无'}}</span>
							<!-- <span class="span_8_v2">2015-07-01</span> -->
							<span class="span_9_v2"><a href="/wuliu/cargo/look/{{item['goods_id']}}" class="li_chakan_v2">查看</a></span>
						</li>
						{% endfor %}
					</ul>
				</div>

				<div class="new_info_v2">
					<div class="new_infoTitle_v2">
						<strong>专线信息</strong>
						<a href="/wuliu/special/index">更多&gt;</a>
					</div>
					<ul class="new_infoTop_ul_v2">
						<li>
							<span class="span_1_v2">出发地</span>
							<span class="span_2_v2">目的地</span>
							<span class="span_3_v2">单程/返程</span>
							<!-- <span class="span_3_v2">箱型</span>
							<span class="span_4_v2">车体</span> -->
							<span class="span_5_v2">轻货价</span>
							<span class="span_6_v2">重货价</span>
							<span class="span_7_v2">联系人</span>

							<!-- <span class="span_8_v2">预计发车时间</span> -->
							<span class="span_9_v2">详情</span>
						</li>
					</ul>
					<ul class="new_infoTop_ul_v2 new_infoBot_ul_v2">
						{% for key ,item in scList['items'] %}
						<li class="">
						<span class="span_1_v2">
							<?php echo Mdg\Models\CargoInfo::GetAreaName($item['start_cname'],$item['start_pname'])?>
						</span>
						<span class="span_2_v2">
							<?php echo Mdg\Models\CargoInfo::GetAreaName($item['end_cname'],$item['end_pname'])?>
						</span>
						<span class="span_3_v2">{% if item['type']== 1 %}往返{% else %}单程{% endif %}</span>
						<!-- <span class="span_3_v2">高栏</span>
						<span class="span_4_v2">前四后八</span> -->
						<span class="span_5_v2">
							{% if item['light_price'] > 0 %} 
							<font class="li_red_v2" >
								{{ item['light_price']}}
							</font>
						     元/方{%  else %} 面议 
						    {% endif %}
						</span>
						<span class="span_6_v2">
							
						    {% if item['light_price'] > 0 %} 
							<font class="li_red_v2" >
								{{ item['light_price']}}
							</font>
						     元/吨{%  else %} 面议 
						    {% endif %}
						</span>
						<span class="span_7_v2">
							{{ item['contact_man']}}
						</span>
						<!-- <span class="span_8_v2">2015-07-01</span> -->
						<span class="span_9_v2">
							<a href="/wuliu/special/look/{{ item['sc_id']}}" class="li_chakan_v2">查看</a></span>
						</li>
						{% endfor %}
					</ul>
				</div>
			</div>
            <!--右侧-->
			<div class="wuliu_right_v2 f-fr">
				<div class="kuaisu_v2">
					{{ partial('index/car_navs') }}
				</div>
				<div class="kuaisu_v2">
					{{ partial('index/cargo_navs') }}
				</div>
				<div class="kuaisu_v2">
					{{ partial('index/sc_navs') }}
				</div>
			</div>
		</div>
	</div>
{{ partial('layouts/footer') }}
<script type="text/javascript">


$(".car_start").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
        style : {"width" : 118}
});
//  目的地
$(".car_end").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
       style : {"width" : 118}
});

//  
$(".cargo_start").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
       style : {"width" : 118}
});

//  
$(".cargo_end").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
       style : {"width" : 118}
});


$(".startAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    style : {"width" : 118}
});

$(".endAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    style : {"width" : 118}
});


</script>
<script>
// 搜索tab
(function(){
	var tabLi = $('#searchBtn_v2').find('li');
	var tabCon = $('.search_con_v2');

	tabLi.each(function(i){
		$(this).on('click', function(){
			tabLi.removeClass('active');
			$(this).addClass('active');

			tabCon.hide();
			tabCon.eq(i).show();
		})
	})
})();

$('.kuaisu_con_v2').find('a').each(function(){
	if($(this).text() == ''){
		$(this).hide();
	}
})

</script>