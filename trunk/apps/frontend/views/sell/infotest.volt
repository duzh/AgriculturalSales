<!-- 头部 -->
{{ partial('layouts/page_header') }}
<!--分享的js-->
<div class="wrapper">
	<div class="w1190 mtauto f-oh">

		<div class="bread-crumbs">
			<a href="/">首页</a>&nbsp;>&nbsp;<a href="/tag/index">可追溯产品</a>&nbsp;>&nbsp;{{areasName}}{{ sell.title }}
		</div>
		<div class="tracea-detials f-oh">
			<input type="hidden" name="id" value="{{ sell.id }}" id="id">
			<div class="magnifier clearfix f-fl">
				<dl class="f-fl">
					<dt>
						<a href="{{ curImg }}" class="MagicZoom">
							{% if curImg  %}
	                    		<img src="{{ curImg }}" width="300" height="300" alt="{{sell.title}}供应时间:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/>
								{% else %}
                                <img  src="<?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>" width="300" height="300" alt="{{sell.title}}供应时间:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/>
                            {% endif %}
						</a>
					</dt>
					<dd>
						<a class="prev_btn" href="javascript:;">上一个</a>
						<div class="ul_box">
							<ul>
								{% for key, img in imgs %}
								<li class="active"><a href="javascript:;"><img src="{{ constant('IMG_URL') }}{{ img['path'] }}" alt="{{sell.title}}供应时间:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/></a></li>
								 {% endfor %}
							</ul>
						</div>
						<a class="next_btn" href="javascript:;">下一个</a>
					</dd>
				</dl>
				<div class="detials-box f-fl">
					<div class="name">{{ sell.title }}</div>
					<div class="bh clearfix">
						<font class="f-fl">[供应编号：{{ sell.sell_sn }}]</font>
						<a  href="javascript:void(0)" onclick = "collectSel({{ sell.id }});" {% if flag != 1 %} class="collect f-fr" {% else %} class="collect f-fr active" {% endif %}>{% if flag != 1 %} 收藏 {% else %}取消收藏{% endif %}</a>
						<a onclick="showShare()" class="share f-fr btn1" href="javascript:void(0);">分享</a>
					</div>
					<!-- JiaThis Button BEGIN -->
					<div class="jiathis_style_24x24" style="display:none;">
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
					<div class="b-price">
						<span>
							商品报价：<strong>{{ sell.min_price }}-{{ sell.max_price }}</strong> 元／{{ goods_unit[sell.goods_unit] }}
						</span>
						<font>
							累计评价<br />
							<strong>{{ quantitys }}</strong>
							<!--<strong>{{ordercount}}</strong>-->
						</font>
					</div>
					<div class="message">供 应 量：
						{% if sell.quantity  %}
						<?php echo Lib\Func::conversion($sell->quantity); ?>
						{{ goods_unit[sell.goods_unit] }} 
                        {% else %}
                         不限
                        {% endif %}
					</div>
					<div class="message">起 购 量：
						{% if sell.min_number > 0 %}
                        {{ sell.min_number }} {{ goods_unit[sell.goods_unit] }} 
                        {% else %}
                        {{ goods_unit[sell.min_number] }} 
                        {% endif %}
					</div>
					<div class="message">
						供应地区：{{ sell.areas_name ? sell.areas_name : '' }}
					</div>
					<div class="message">供应时间：{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</div>
					<div class="message">产品规格： {% if sell.spec %}{{ sell.spec }}{% endif %}</div>
					<div class="btns">
						<input class="cg-btn" type="b" value="采购" onclick="newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ sell.id }}');"/>
						<!-- <input class="xj-btn" type="submit" value="询价" /> -->
					</div>
				</div>
			</div>
			<!-- 
			**	根据需求判断显示“公司信息”还是“商家信息”
			-->
			<!-- 公司信息 -->
            <!-- 商家信息 -->
            {% if shopgoods %}
			<div class="company-intro f-fr f-oh"  style="display:block;" >
				
				{% if shopgoods['logo_pic'] %}
				<div class="imgs f-tac">
					<img src="{{shopgoods['logo_pic']}}" height="81" width="223">
				</div>
				{% endif %}
				<div class="line"></div>
				<div class="name">{{shopgoods['farm_name'] }}</div>
				<div class="icon">
					<span>可信农场</span>
				</div>		
				<div class="line"></div>
				<div class="message clearfix">
					<font>农场面积：</font>
					<div class="m-con">{{shopgoods['farm_area']}}亩</div>
				</div>
				<div class="message clearfix">
					<font>所在地区：</font>
					<div class="m-con">{{shopgoods['address']}}</div>
				</div>
				<div class="message clearfix">
					<font>主营产品：</font>
					<div class="m-con"><?php  echo \Lib\Func::sub_str($shopgoods['main_name'], 15);?></div>
				</div>
				<div class="line"></div>
				<div class="btns">
					<!--<a class="collect-btn" href="javascript:void(0);" onclick = "collectFarm({{ shopgoods['farm_id'] }});" 
					id="col_{{ shopgoods['farm_id'] }}" >收藏可信农场</a>-->
				</div>    
			</div>
            {% else %}
            <div class="bussiness-intro f-fr f-oh" style="display:block;">
				<div class="name">商家姓名：{{userinfo["name"]}}</div>
				<div class="icon">
					<span>{{userinfo["userinfo"]}}</span>
				</div>
				<div class="line"></div>
				<div class="message clearfix">
					<font>所在地区：</font>
					<div class="m-con">{{userinfo["address"]}}</div>
				</div>
				<div class="message clearfix">
					<font>主营产品：</font>
					<div class="m-con">{{ userinfo["goods"]}}</div>
				</div>
			</div>
            {% endif %}
		</div>
		
		<div class="tracea-biggerBox f-oh">
			<div class="left f-fl">

				<div class="switch-btn clearfix">
					<span class="active">详细信息</span>
					{% if sell.is_source == 1 and  tagsell %}<span>追溯信息</span>{% endif %}
					<span>用户评论{% if quantitys %}（{{ quantitys}}）{% endif %}</span>
					<span>交易记录</span>
				</div>
				<div class="tracea-tabChange">
					<!-- 详细信息 -->
					<div class="tab-box f-oh" style="display:block;">

						<!-- 块1 -->
						<table cellpadding="0" cellspacing="0" class="tracea-message">
							<tr height="29">
								<td width="243">
									供应编号：{{ sell.sell_sn }}
								</td>
								<td width="243">
									产品品名：{{ sell.title }}
								</td>
								<td width="332">
									产品品种：{{sell.breed ? sell.breed : '暂无' }}
								</td>
							</tr>
							<tr height="29">
								<td width="243">
									供应时间：{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}
								</td>
							{% if sell.is_source == 1 %}
								<td width="243">
									产品规格：{% if sell.spec %}{{ sell.spec }}{%else%} 暂无{% endif %}
								</td>
								<td width="332">
									产地：<?php echo isset($tagsell['full_address']) ? $tagsell['full_address'] : '';?>
								</td>
							</tr>
							<tr height="29">
								<td width="243">
									生产商：<?php echo isset($tagsell['productor']) ? $tagsell['productor']:''; ?> 
								</td>
								<td width="243">
									生产日期：<?php echo isset($tagsell['product_date']) ? $tagsell['product_date']:''; ?>
								</td>
								<td width="332">
									加工商：<?php echo isset($tagsell['process_merchant']) ? $tagsell['process_merchant']:''; ?>
								</td>
							</tr>
							<tr height="29">
								<td width="243">
									加工地：<?php echo isset($tagsell['process_place']) ? $tagsell['process_place']:''; ?>
								</td>
								<td width="243">
									保质期：<?php echo isset($tagsell['expiration_date'])? $tagsell['expiration_date'] : ''; ?>天
								</td>
								<td width="332">
									质量等级：<?php echo isset($tagquality['quality_level'])? $tagquality['quality_level'] : ''; ?>
								</td>
							</tr>
							<tr height="29">
								<td width="243">
									农残含量：<?php echo isset($tagquality['pesticide_residue'])? $tagquality['pesticide_residue'] : ''; ?>
								</td>
								<td width="243">
									重金属含量s：<?php echo isset($tagquality['heavy_metal'])? $tagquality['heavy_metal'] : ''; ?>
								</td>
								<td width="332">
									是否转基因：<?php echo isset($tagquality['is_gene'])&&$tagquality['is_gene']==1 ? '是' : '否';?>
								</td>
							</tr>
							{%endif%}
						</table>
						<div class="tracea-title">
							<span>产品介绍</span>
						</div>
						<div class="tracea-product-intro f-tac">
							{% if sell.scontent  %}
							  {{ sell.scontent.content }}
						   {% else %}
							 {{contents}}
						   {% endif %}
						</div>
					    <!-- 块3 -->
						<div class="tracea-title">
							<span>用户评价</span>
						</div>
						<div class="user-rate f-oh" id="list">
							<div class="message clearfix">
								<font class="f-fl">累计评价：<?php if ($sell->total_score){ ?> {{ sell.total_score }}<?php }else{?> 暂无<?php }?></font>
								<font class="f-fl clearfix">
									<i>与描述相符：<?php if ($sell->total_score){ ?> {{ sell.average_score }}分<?php }else{?> 暂无<?php }?></i>
									{%if sell.total_score >= 0 %}
										{{ sell.tr }}
									{%endif%}
								</font>
							</div>
							<table cellpadding="0" cellspacing="0" width="778" class="r-table">
								
							</table>
							<!-- 分页 -->
							<div class="esc-page mt20 f-tac f-fr mr39" id="list_page">
							</div>
						</div>

						
						
						<!-- 块4 -->
						<div class="tracea-title">
							<span>交易记录</span>
						</div>
						<div class="jy-record" id="orders">
							<table cellpadding="0" cellspacing="0" width="778" class="r-table">

							</table>
							<!-- 分页 -->
							<div class="esc-page mt20 f-tac f-fr mr39" id="orders_page">

							</div>
						</div>
					</div>
					
					
					
					<!-- 块2 -->
					<!-- 追溯信息 -->
					{% if sell.is_source == 1 and  tagsell %}
					<div class="tab-box f-oh">	
						
						<div class="tracea-title">
							<span>追溯信息</span>
						</div>
						<div class="tracea-erjiMsg">
							<div class="m-title">生产过程信息</div>
							<table cellpadding="0" cellspacing="0" width="100%" class="m-message">
								<tr height="29">
									<td width="50%">
										生产日期：<?php echo isset($tagsell['product_date']) ? $tagsell['product_date'] : ''?>
									</td>
									<td width="50%">
										产品保质期：<?php echo isset($tagsell['expiration_date']) ? $tagsell['expiration_date'] : ''?>天
									</td>
								</tr>
								<tr height="29">
									<td width="50%">
										产地：<?php echo isset($tagsell['full_address']) ? $tagsell['full_address'] : ''?>
									</td>
									<td width="50%">
										种植面积：<?php echo isset($tagsell['plant_area']) ? $tagsell['plant_area'] : '0'?>亩
									</td>
								</tr>
								<tr height="29">
									<td width="50%">
										土地污染：
									</td>
									<td width="50%">
										品种名：<?php echo isset($TagSeed->breed) ? $TagSeed->breed : '';?>
									</td>
								</tr>
								<tr height="29">
									<td colspan="2">
										物流信息：未发货，物流单位未确认
									</td>
								</tr>
							</table>
							<div class="use-table">
								<div class="m-title">产地信息</div>
								<table cellpadding="0" cellspacing="0" width="778" class="u-message">
									<tr height="30">
										<th width="50%">纬度</th>
										<th width="50%">雨水等级</th>
									</tr>
									
									<tr height="40">
						<td>
						<?php echo isset($tagsell['latitude']) ? 'N'.$tagsell['latitude'] : '';?>
						<?php echo isset($tagsell['longitude']) ? 'S'.$tagsell['longitude'] : '';?>
						</td>
						<td>
						<?php 

						echo isset($rainwater[$tagsell['rainwater']]) ? $rainwater[$tagsell['rainwater']] : ''; ?>

						</td>
									</tr>
									
								</table>
							</div>
							<div class="use-table">
								<div class="m-title">种子使用表</div>
								<table cellpadding="0" cellspacing="0" width="778" class="u-message">
									<tr height="30">
									    <th width="16%">作物</th>
										<th width="16%">品种</th>
										<th width="16%">净度</th>
										<th width="16%">纯度</th>
										<th width="16%">发芽率</th>
										<th width="16%">水分</th>
									</tr>
									{% for key, item in TagManureList %}
									<tr height="40">
								<td><?php echo isset($TagSeed->crops) ? $TagSeed->crops : '';?></td>
								<td><?php echo isset($TagSeed->breed) ? $TagSeed->breed : '';?></td>
								<td><?php echo isset($TagSeed->neatness) ? $TagSeed->neatness : '';?></td>
								<td><?php echo isset($TagSeed->fineness) ? $TagSeed->fineness : '';?></td>
								<td><?php echo isset($TagSeed->sprout) ? $TagSeed->sprout : '';?></td>
								<td><?php echo isset($TagSeed->water) ? $TagSeed->water : '';?></td>
									</tr>
									{% endfor %}
								</table>
							</div>
							<div class="use-table">
								<div class="m-title">肥料使用表</div>
								<table cellpadding="0" cellspacing="0" width="778" class="u-message">
									<tr height="30">
									    <th width="16%">时间</th>
										<th width="16%">名称</th>
										<th width="16%">用量(千克/亩)</th>
									</tr>
									{% for key, item in TagManureList %}
										<tr height="40">
											<td>{{ item['use_period']}}</td>
											<td>{{ item['manure_name']}}</td>
											<td>{{ item['manure_amount']}}</td>
										</tr>
								    {% endfor %}
								</table>
							</div>
							<div class="use-table">
								<div class="m-title">农药使用表</div>
								<table cellpadding="0" cellspacing="0" width="778" class="u-message">
									<tr height="30">
										<th width="16%">时间</th>
										<th width="16%">名称</th>
										<th width="16%">用量(千克/亩)</th>
									</tr>
									{% for key ,item in TagPesticide %}
									<tr height="40">
										<td>{{ item['use_period']}}</td>
										<td>{{ item['pesticide_name']}}</td>
										<td>{{ item['pesticide_amount']}}</td>
									</tr>
									{% endfor %}
								</table>
							</div>

							<div class="m-title">生产环节全程监控</div>
							<table cellpadding="0" cellspacing="0" width="778" class="p-table">
								<tr height="30">
								    <th width="16%">作业类型</th>
									<th width="16%">开始时间</th>
									<th width="16%">结束时间</th>
								</tr>
								{% for row in plantList %}
								<tr height="40">
									<td>{{ row['ptype']}}</td>
									<td>{{ row['begin_date'] ? row['begin_date'] : ''}}</td>
									<td>{{ row['end_date'] ? row['end_date'] : ''}}</td>
								</tr>
								{% endfor %}
							</table>
						</div>
						
					</div>
					{% endif %}
					
					
					<!-- 用户评论 -->
					<div class="tab-box f-oh">
						<!-- 块3 -->
						<div class="tracea-title">
							<span>用户评价</span>
						</div>
						<div class="user-rate f-oh" id="lists">
							<div class="message clearfix">
								<font class="f-fl">累计评价：<?php if ($sell->total_score){ ?> {{ sell.total_score }}<?php }else{?> 暂无<?php }?></font>
								<font class="f-fl clearfix">
									<i>与描述相符：<?php if ($sell->total_score){ ?> {{ sell.average_score }}分<?php }else{?> 暂无<?php }?></i>
									{%if sell.total_score >= 0 %}
										{{ sell.tr }}
									{%endif%}
								</font>
							</div>
							<table cellpadding="0" cellspacing="0" width="778" class="r-table">
								
							</table>
							<!-- 分页 -->
							<div class="esc-page mt20 f-tac f-fr mr39" id="lists_page">

							</div>
						</div>
					</div>
					
					
					<!-- 块4 -->
					<!-- 详细信息 -->
					<div class="tab-box f-oh">						
						<div class="tracea-title">
							<span>交易记录</span>
						</div>
						<div class="jy-record" id="orderss">
							<table cellpadding="0" cellspacing="0" width="778" class="r-table">

							</table>
							<!-- 分页 -->
							<div class="esc-page mt20 f-tac f-fr mr39" id="orders_pages">

							</div>
						</div>
					</div>
				</div>
			</div>

			
			<div class="right f-fr">
				<!-- 他还提供 -->
				<div class="also-serve mb20">
					<div class="title">他还提供</div>
					{% for key, val in otherSell %}
					<div class="list f-oh">
						<a href="/sell/info/{{ val['id'] }}">
							<span class="f-fl">
								[{{ val['title'] }}] <i>{{ val['min_price'] }}-{{ val['max_price'] }}</i>元/{{ goods_unit[val['goods_unit']] }}
							</span>      
							<font class="f-fr">{{ date('y-m-d', val['updatetime']) }}</font>
						</a>
					</div>
					{% endfor %}
				</div>
				<!-- 同类产品推荐 -->
				<div class="hot-product">
					<div class="title">同类产品推荐</div>
					{% if hot_sell %}
					{% for key, val in hot_sell %}
					<div class="list">
						<div class="m-box">
							<dl class="clearfix">
								<dt class="f-fl">
									<a href="/sell/info/{{ val['id'] }}"> {% if val['thumb']  %}
										<img src="{{ constant('IMG_URL') }}{{ val['thumb'] }}" height="92" width="92" alt="{{val['title']}}供应时间:{{ time_type[val['stime']] }}~{{ time_type[val['etime']] }}">
										{% else %}
										<img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="92" width="92" alt="{{val['title']}}供应时间:{{ time_type[val['stime']] }}~{{ time_type[val['etime']] }}">
										{% endif %}
									</a>
								</dt>
								<dd class="name f-fr">
									<a href="/sell/info/{{ val['id'] }}">{{ val['title'] }}</a>
								</dd>
								<dd class="price f-fr">{{ val['min_price'] }}-{{ val['max_price'] }} 元/{{ goods_unit[val['goods_unit']] }}</dd>
								<dd class="area f-fr">{{ val['areas_name'] ? val['areas_name'] : '' }}</dd>
							</dl>
						</div>
					</div>
					{% endfor %}
					{% endif %}
				</div>
			</div>
		</div>

	</div>
</div>

<!-- 底部 -->
{{ partial('layouts/footer') }}

<script type="text/javascript">
	$(function(){
		getData(1,0,1);
		getDatas(1,1,1);
		getData(1,0,2);
		getDatas(1,1,2);

    //(function(){
        //var index=0;
        //var length=$(".schj_list_xq li").length;
        //var i=1;
		//$(".schj_list_xq li").eq(0).addClass('active');
        //function showImg(i){
            //$(".schj_list_xq li").eq(i).addClass("active").siblings().removeClass("active");
        //}
        //function slideNext(){
            //if(index >= 0 && index < length-1) {
                //++index;
                //showImg(index);
            //}else{
                //if(confirm("已经是最后一张,点击确定重新浏览！")){
                    //showImg(0);
                    //index=0;
                    //if(length > 5){
						//aniPx=(length-5)*120+'px'; 
					//}
                    //$(".schj_list_xq ul").animate({ "left": "+="+aniPx },200);
                    //i=1;
                //}
                //return false;
            //}
            //if(i<0 || i>length-5){
                //return false;
            //}                         
            //$(".schj_list_xq ul").animate({ "left": "-=120px" },200)
            //i++;
        //}
        //function slideFront(){
            //if(index >= 1 ) {
                //--index;
                //showImg(index);
            //}
            //if(i<2 || i>length+5){
                //return false;
            //}
            //$(".schj_list_xq ul").animate({ "left": "+=120px" },200)
            //i--;
        //}
        //$(".next_btn").click(function(){
            //slideNext();
        //});
        //$(".prev_btn").click(function(){
            //slideFront();
        //});
        //$(".schj_list_xq li").click(function(){
            //index  =  $(".schj_list_xq li").index(this);
            //showImg(index);
        //});
    //})();
    
    //相册弹层
    //(function(){
        //$('.chakan_photos').on('click', function(){
            //$('#photo_box_id').html('');
            //var sid = $(this).attr('id');
            ////ajax 请求替换本地图片
            //$.ajax({
                //url: '/sell/tagplant',
                //async:false,
                //data: {sid: sid},
                //success:function ( data) {
                    //$('#photo_box_id').html(data);
                    //var now = 0;
                    //var $li = $('.photo_alert .photo_box li');
                    //function nextImg(){
                        //$li.removeClass('active');
                        //$li.eq(now).addClass('active');
                        //now ++;
                        //if(now == $li.length){
                            //now = 0;
                        //}
                    //};
                    //function prevImg(){           
                        //$li.removeClass('active');
                        //$li.eq(now).addClass('active');
                        //now --;
                        //if(now == -1){
                            //now = $li.length - 1;
                        //}
                    //};
                    //$('.prev-btn').on('click', function(){
                        //prevImg();
                    //});
                    //$('.next-btn').on('click', function(){
                        //nextImg();
                    //});
                //}
            //});
            //$('.photo_layer').show();
            //$('.photo_alert').show();
        //});
        //$('.photo_alert .close_btn').click(function(){
            //$(this).parent().hide();
            //$('.photo_layer').hide();
        //});
    //})();
});

	function getData(page, is_page, type){  
		var sellid = $('#id').val();
		if (type==1){
			var urls = '/goodscomments/goodscomments';
		}else{
			var urls =  '/orders/orderslist';
		}
		$.ajax({ 
			type: 'GET', 
			url: urls, 
			data: {'p':page,'sell_id':sellid,'is_page':is_page,'type':0}, 
			dataType:'json', 
			success:function(json){ 
				if(json.type=='orders'){
					$("#orders table").empty();
					$("#orders_page").empty();
					var table = '<tr height="32"><th align="left" width="50%"><em>采购商家</em></th><th width="25%">采购数量</th><th width="25%">采购时间</th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span><label>去</label><input type='text' name='pt1' id='orders1'  /><label>页</label></span>&nbsp;<input class='btn' type='button' value='确定' onclick='subDatas(1)' />";
							$("#orders table").append(str); 
							$("#orders_page").append(li); 
					}
				}

				if(json.type=='comments'){
					$("#list table").empty();
					$("#list_page").empty();
					var table = '<tr height="32"><th align="left" width="40%"><em>评价内容</em><th width="20%">评分</th><th width="20%">发布时间</th><th width="20%">用户名</th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){ 
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span><label>去</label><input type='text' name='p1' id='p1' /><label>页</label></span>&nbsp;<input class='btn' type='button' value='确定' onclick='subData(1)' />";
							$("#list table").append(str); 
							$("#list_page").append(li); 
					}
				}
			}
		}); 
	} 

	function getDatas(page, is_page, type){  
		var sellid = $('#id').val();
		if (type==1){
			var urls = '/goodscomments/goodscomments';
		}else{
			var urls =  '/orders/orderslist';
		}
		$.ajax({ 
			type: 'GET', 
			url: urls, 
			data: {'p':page,'sell_id':sellid,'is_page':is_page,'page_size':10,'type':1}, 
			dataType:'json', 
			success:function(json){ 
				if(json.type=='orders'){
					$("#orderss table").empty();
					$("#orders_pages").empty();
					var table = '<tr height="32"><th align="left" width="50%"><em>采购商家</em></th><th width="25%">采购数量</th><th width="25%">采购时间</th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span><label>去</label><input type='text' name='pt2' id='orders2'  /><label>页</label></span>&nbsp;<input class='btn' type='button' value='确定' onclick='subDatas(1)' />";
							$("#orderss table").append(str); 
							$("#orders_pages").append(li); 
					}
				}
				if(json.type=='comments'){
					$("#lists table").empty();
					$("#lists_page").empty();
					var table = '<tr height="32"><th align="left" width="40%"><em>评价内容</em><th width="20%">评分</th><th width="20%">发布时间</th><th width="20%">用户名</th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){ 
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span><label>去</label><input type='text' name='p2' id='p2' onkeyup='value=value.replace(/[^\d]/g,'')'  /><label>页</label></span>&nbsp;<input class='btn' type='button' value='确定' onclick='subData(2)' />";
							$("#lists table").append(str); 
							$("#lists_page").append(li); 
					}
				}
			}
		}); 
	} 

	function subData(type){
		if(type == 1){
			var p = $('#p1').val();
			getData(p,0,1);
		}
		if(type == 2){
			var p = $('#p2').val();
			getDatas(p,1,1);
		}
	}

	function subDatas(type){
		if(type == 1){
			var p = $('#orders1').val();
			getData(p,0,2);
		}
		if(type == 2){
			var p = $('#orders2').val();
			getDatas(p,1,2);
		}
	}
	// 收藏
	function collectSel(sellId){
		$.ajax({
			type:"POST",
			url:"/sell/collectSell",
			data:{sellId:sellId},
			dataType:"json",
			success:function(msg){		
				if(msg['code'] == 0){
					//location.href="/member/dlogin/index?ref=/sell/info/"+sellId+"&islogin=1";
					newWindows('login', '登录', "/member/dlogin/index?ref=/sell/info/"+sellId+"&islogin=1");
				} else if(msg['code'] == 4){
					$("#col").attr('class','btn3').html('取消收藏');
					window.location.reload();
					return;
				} else if( msg['code'] == 6 ){
					$("#col").attr('class','btn2').html('收藏');
					window.location.reload();
					return;
				} else {
					alert(msg['result']);
					window.location.reload();
					return;
				}
			}
		});
	}
	
	function showShare(){
		if($('.jiathis_style_24x24').css('display') == 'none'){
			$('.jiathis_style_24x24').show();
		}else{
			$('.jiathis_style_24x24').hide();
		};
	};

	//店铺收藏
	function collectFarm(farmId){
		$.ajax({
			type:"POST",
			url:"/indexfarm/collectFarm",
			data:{farmId:farmId},
			dataType:"json",
			success:function(msg){
				if(msg['code'] == 0){
					newWindows('login', '登录', "/member/dlogin/index?ref=/sell/info&islogin=1");
	            } else if(msg['code'] == 4){
	            	alert(msg['result']);
					$("#col_"+farmId).html('<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/trusted-farm/cancel_farm_collect.png">');
					//window.location.reload();
					return;
				} else if( msg['code'] == 6 ){
					alert(msg['result']);
					$("#col_"+farmId).html('<img src="{{ constant('STATIC_URL')}}/mdg/version2.4/images/trusted-farm/farm_collect.png">');
					//window.location.reload();
					return;
				} else {
					alert(msg['result']);
					return;
				}
			}
		});
	}
</script>




<!-- 放大镜 -->
<link href="{{ constant('STATIC_URL') }}mdg/version2.5/css/MagicZoom.css" rel="stylesheet" media="screen"/>
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/MagicZoom.js"></script>
<style>
	.MagicZoomBigImageCont{ border:solid 1px #f9ab14;}
</style>
<!-- 图片放大预览 -->
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/zoom.css" media="all">
<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/zoom.min.js"></script>
<style>
	#zoom{ *background:#000; *opacity:0.8; *filter:alpha(opacity:80);}
</style>