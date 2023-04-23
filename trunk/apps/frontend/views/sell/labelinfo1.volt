<div class="title">
	<span>追溯信息</span>
</div>
{% if product and ( product['product_place'] or product['manure'] or product['pollute'] or product['breed']  or product['process_type'] or product['manure_type'] or (product['manure_amount'] > 0) or product['pesticides_type'] or (product['pesticides_amount'] > 0) )   %}
<h6 class="erji-title">生产过程信息</h6>

<table cellpadding="0" cellspacing="0" width="854" class="table2">
	<tr height="40">
		<th width="118">
			<span>生产基地位置</span>
		</th>
		<td width="341">
			<span>{{product['product_place']}}</span>
		</td>
		<th width="118">
			<span>基地面积</span>
		</th>
		<td width="341">
			<span>{{ sell.farm_areas}}</span>
		</td>
	</tr>
	<tr height="40">
		<th width="118">
			<span>种子质量指标</span>
		</th>
		<td width="341">
			<span>{{ product['seed_quality']}}</span>
		</td>
		<th width="118">
			<span>土地肥力</span>
		</th>
		<td width="341">
			<span>{{product['manure']}}</span>
		</td>
	</tr>
	<tr height="40">
		<th width="118">
			<span>土地污染</span>
		</th>
		<td width="341">
			<span>{{product['pollute']}}</span>
		</td>
		<th width="118">
			<span>品种名</span>
		</th>
		<td width="341">
			<span>{{ product['breed']}}</span>
		</td>
	</tr>
	<tr height="40">
		<th width="118">
			<span>物流信息</span>
		</th>
		<td width="341">
			<span> &nbsp;</span>
		</td>
		<th width="118">
			<span></span>
		</th>
		<td width="341">
			<span></span>
		</td>
	</tr>
</table>
{% endif %}

{% if TagCertifying %}
<div class="safety">
	<div class="name">权威机构安全鉴定：</div>
	<ul class="gallery imgs">
	{% for key , item in TagCertifying %}
		<li>
			<a href="{{ item['path']}}">
				<img src="{{ item['path']}}" height="80" width="80">
			</a>
		</li>
	 {% endfor %}
	</ul>
</div>
{% endif %}
<?php  if( isset($tagsell['imgList'])  && count($tagsell['imgList']) > 0) {?>
<div class="origin-photo">
	<div class="name">产地照片：</div>
	<!-- 滚动 start -->
	<div class="add_big_box" id="add_big_box1">
		<a class="prev_btn" href="javascript:;"></a>
		<div class="f-pr mtauto schj_list_xq">
			<ul class="pa clearfix gallery" style="width:2000px;">
				{% for img in tagsell['imgList'] %}
				<li><a href="{{img['path']}}"><img src="{{img['path']}}" width="160" height="100" /></a></li>
				{% endfor %}
			</ul>
		</div>
		<a class="next_btn" href="javascript:;"></a>
	</div>
	<!-- 滚动 end -->
</div>
<?php } ?>
<?php if(isset($TagManureList) && $TagManureList) { ?>
<div class="use-table">
	<div class="name">肥料使用表</div>
	<table cellpadding="0" cellspacing="0" width="854" class="table3">
		<tr height="30">
			<th width="16%">使用时期</th>
			<th width="16%">名称</th>
			<th width="16%">种类</th>
			<th width="16%">用量(千克/亩)</th>
			<th width="16%">品牌</th>
			<th width="20%">供应商</th>
		</tr>
		 {% for  key , item in TagManureList %}
		<tr height="40">
			<td>{{ item['use_period'] }}</td>
			<td>{{ item['manure_name'] }}</td>
			<td>{{ item['manure_type'] > 0  ? item['manure_type'] == 1 ? '有机肥' :'肥料' : '' }}</td>
			<td>{{ item['manure_amount']}}</td>
			<td>{{ item['manure_brand']}}</td>
			<td>
				<span>{{ item['manure_suppliers']}}</span>
			</td>
		</tr>
		 {% endfor %}
	</table>
</div>
 <?php } ?>

 {% if TagPesticide %}
<div class="use-table">
	<div class="name">农药使用表</div>
	<table cellpadding="0" cellspacing="0" width="854" class="table3">
		<tr height="30">
			<th width="15%">使用时期</th>
			<th width="20%">名称</th>
			<th width="20%">用量(千克/亩)</th>
			<th width="20%">品牌</th>
			<th width="25%">供应商</th>
		</tr>
		{% for  key , item in TagPesticide %}
		<tr height="40">
			<td>{{ item['use_period']}}</td>
			<td>{{ item['pesticide_name']}}</td>
			<td>{{ item['pesticide_amount']}}</td>
			<td>{{ item['pesticide_brand']}}</td>
			<td>
				<span>{{ item['pesticide_suppliers']}}</span>
			</td>
		</tr>
		 {% endfor %}
	</table>
</div>
{% endif %}

<?php if(isset($plantList[0]) && ($plantList[0]['ptype'] || $plantList[0]['begin_date'] || $plantList[0]['end_date'] || $plantList[0]['weather'] || $plantList[0]['comment'] ||  $plantList[0]['imgList']  ) ) { ?>
<h6 class="erji-title">生产环节全程监控</h6>

<table cellpadding="0" cellspacing="0" width="854" class="table4">
	<tr height="30">
		<th width="16%">作业类型</th>
		<th width="16%">开始时间</th>
		<th width="16%">结束时间</th>
		<th width="16%">天气状况</th>
		<th width="20%">作业内容</th>
		<th width="16%">相册</th>
	</tr>
	{% for row in plantList %}
	<tr height="40">
		<td>{{ row['ptype']}}</td>
		<td>{{ row['begin_date'] ? row['begin_date'] : ''}}</td>
		<td>{{ row['end_date'] ? row['end_date'] : ''}}</td>
		<td>{{ row['weather']}}</td>
		<td>{{ row['comment']}}</td>
		<td>
			<a class="chakan_photos" href="javascript:;" id="{{ row['id']}}">查看</a>
		</td>
	</tr>
	{% endfor %}
</table>
<?php } ?>