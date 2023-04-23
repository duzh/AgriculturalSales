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
			<div class="cheyuanTable_v2">
				<table>
					<tr>
						<td colspan="4"><strong class="cheyuan_title_v2"><?php echo Mdg\Models\CargoInfo::GetAreaName($cargoinfo['start_cname'],$cargoinfo['start_pname'])?>到<?php echo Mdg\Models\CargoInfo::GetAreaName($cargoinfo['end_cname'],$cargoinfo['end_pname'])?>的货源信息</strong></td>
					</tr>
					<tr class="tr_bg_v2">
						<td colspan="4"><span class="table_title_v2">车主信息</span></td>
					</tr>
					<tr>
						<td width="20%"><span class="table_span_right">车源编号：</span></td>
						<td width="30%"><span class="table_span_left">{{cargoinfo['goods_no'] ? cargoinfo['goods_no'] : '暂无'}}</span></td>
						<td width="20%"><span class="table_span_right">发布日期：</span></td>
						<td width="30%"><span class="table_span_left">{{ cargoinfo['sendtime'] ? cargoinfo['sendtime'] : '暂无'}}</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">联系人：</span></td>
						<td><span class="table_span_left">{{cargoinfo['contact_man'] ? cargoinfo['contact_man'] : '暂无'}}</span></td>
						<td><span class="table_span_right">手机号：</span></td>
						<td id="span_id">
							<span class="table_span_left">
						         	<?php   if($cargoinfo['contact_phone']){
											echo substr_replace($cargoinfo['contact_phone'],'****',3,4);
								    }else{
											echo $cargoinfo['phone_number'];
								    }
								    ?>

						        <a href="javascript:;" onclick="funclook('http://yncmdg.b0.upaiyun.com/{{cargoinfo['phone_img']}}')">查看</a>
							</span>
						</td>
					</tr>
					<tr class="tr_bg_v2">
						<td colspan="4"><span class="table_title_v2">货物信息</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">货物名：</span></td>
						<td><span class="table_span_left">{{cargoinfo['goods_name'] ? cargoinfo['goods_name'] : '暂无'}}</span></td>
						<td><span class="table_span_right">货物种类：</span></td>
						<td><span class="table_span_left"><?php echo Mdg\Models\CargoInfo::$_goods_type[$cargoinfo['goods_type']]?></span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">重量：</span></td>
						<td><span class="table_span_left">{{cargoinfo['goods_weight']!=0.00 ? cargoinfo['goods_weight'] : 0}}吨</span></td>
						<td><span class="table_span_right">体积：</span></td>
						<td><span class="table_span_left">{{cargoinfo['goods_size']!=0.00 ? cargoinfo['goods_size'] : 0}}方</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">期望运费：</span></td>
						<td><span class="table_span_left">{{cargoinfo['except_price']!=0.00 ? cargoinfo['except_price'] : 0}}元/吨</span></td>
						<td><span class="table_span_right">车体长度：</span></td>
						<td><span class="table_span_left">{{cargoinfo['except_length']!=0.00 ? cargoinfo['except_length'] : 0}}米</span></td>
					</tr>
					<tr class="tr_bg_v2">
						<td colspan="4"><span class="table_title_v2">期望流向和车辆要求</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">出发地：</span></td>
						<td><span class="table_span_left">{% if cargoinfo['start_pname']%}{{cargoinfo['start_pname']}}{{cargoinfo['start_cname']}}{{cargoinfo['start_dname']}}{% else %}暂无{% endif %}</span></td>
						<td><span class="table_span_right">目的地：</span></td>
						<td><span class="table_span_left">{% if cargoinfo['end_pname']%}{{cargoinfo['end_pname']}}{{cargoinfo['end_cname']}}{{cargoinfo['end_dname']}}{% else %}暂无{% endif %}</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">是否长期：</span></td>
						<td><span class="table_span_left">
							{% if cargoinfo['is_long']==1 %}
								是
							{% else %}
								否
						{% endif %}
						</span></td>
						<td><span class="table_span_right">运行方式：</span></td>
						<td><span class="table_span_left">往返</span></td>
					</tr>
					<tr>
						<td><span class="table_span_right">需要车辆类型：</span></td>
						<td><span class="table_span_left"><?php if(isset($cargoinfo['box_type'])){echo Mdg\Models\CargoInfo::$_box_type[$cargoinfo['box_type']];}else{echo '暂无';}?></span></td>
						<td><span class="table_span_right">需要车辆长度：</span></td>
						<td><span class="table_span_left">{{cargoinfo['except_length']!=0.00 ? cargoinfo['except_length'] : 0}}米</span></td>
					</tr>
					<tr>
						<td colspan=""><span class="table_span_right">结算方式：</span></td>
						<td colspan="3"><span class="table_span_left">{{cargoinfo['settle_type'] ? cargoinfo['settle_type'] : '无'}}</span></td>
					</tr>
					<tr>
						<td colspan=""><span class="table_span_right">备注：</span></td>
						<td colspan="3"><span class="table_span_left"><?php echo mb_substr($cargoinfo['demo'],0,100) ? mb_substr($cargoinfo['demo'],0,100)."..." : '无';?></span></td>
					</tr>
				</table>
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
{{ partial('layouts/footer') }}
<script>
function funclook(value){
	$("#span_id").html("<img src="+value+">");
}
</script>