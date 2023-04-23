<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>׷������</title>
    <link href="{{ constant('STATIC_URL') }}mdg/version2.4/css/MagicZoom.css" rel="stylesheet" type="text/css" media="screen"/>
    <script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/MagicZoom.js" type="text/javascript"></script>
	
</head>
<body>
{{ partial('layouts/page_header') }}
	<!-- ͷ�� -->

	<div class="wrapper pb30">

		<div class="bread-crumbs w1185 mtauto">
			<a href="/">��ҳ</a> > <a href="/tag/index">��׷�ݲ�Ʒ</a> > {{areasName}}{{ sell.title }}
		</div>

		<div class="tract-wrapper w1185 clearfix mtauto">
			<div class="left f-fl">
				<input type="hidden" name="id" value="{{ sell.id }}" id="id">
				<div class="magnifier clearfix">
					<dl class="f-fl">
	                    <dt>
	                    	<a href="{{ curImg }}" class="MagicZoom">
							{% if curImg  %}
	                    		<img src="{{ curImg }}" width="300" height="300" alt="{{sell.title}}��Ӧʱ��:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/>
								{% else %}
                                <img  src="<?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>" width="300" height="300" alt="{{sell.title}}��Ӧʱ��:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/>
                            {% endif %}
	                    	</a>
	                    </dt>
	                    <dd>
	                        <a class="prev_btn" href="javascript:;">��һ��</a>
	                        <div class="ul_box">
	                            <ul>
								{% for key, img in imgs %}
	                                <li class="active"><a href="javascript:;"><img src="{{ constant('IMG_URL') }}{{ img['path'] }}" alt="{{sell.title}}��Ӧʱ��:{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}"/></a></li>
									 {% endfor %}
	                            </ul>
	                        </div>
	                        <a class="next_btn" href="javascript:;">��һ��</a>
	                    </dd>
	                </dl>
					
					
					<div class="detials f-fl">
						<div class="name">
							<strong>{{ sell.title }}</strong>
							[��Ӧ��ţ�{{ sell.sell_sn }}]
						</div>
						<div class="price f-oh">
							<span class="bj f-fl">��Ʒ���ۣ�<i>{{ sell.min_price }}~{{ sell.max_price }}Ԫ/{{ goods_unit[sell.goods_unit] }}</i></span>
							<span class="num f-fr">
								�ۼƽ���
								<i>({{ordercount}})</i>
							</span>
						</div>
						<div class="message clearfix">
							<font>��Ӧ����</font>
							<div class="box f-fwb">  {% if sell.quantity > 0 %}{{ sell.quantity }} {{ goods_unit[sell.goods_unit] }} 
                        {% else %}
                        {{ goods_unit[0] }} 
                        {% endif %}</div>
						</div>
						<div class="message clearfix">
							<font>������</font>
							<div class="box f-fwb">{% if sell.min_number > 0 %}
                        {{ sell.min_number }} {{ goods_unit[sell.goods_unit] }} 
                        {% else %}
                        {{ goods_unit[sell.min_number] }} 
                        {% endif %}</div>
						</div>
						<div class="message clearfix">
							<font>��Ӧ������</font>
							<div class="box">{{ sell.areas_name ? sell.areas_name : '' }}</div>
						</div>
						<div class="message clearfix">
							<font>��Ӧʱ�䣺</font>
							<div class="box">{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</div>
						</div>
						<div class="message clearfix">
							<font>��Ʒ���</font>
							<div class="box"> {% if sell.spec %}{{ sell.spec }}{% endif %}</div>
						</div>
						<div class="line"></div>
						<div class="cg-btn">
							<a href="javascript:newWindows('newbuy', 'ȷ�ϲɹ���Ϣ', '/member/dialog/newbuy/{{ sell.id }}');;">
								<font>�����ɹ�</font>
							</a>
						</div>
						<div class="share">
							<a class="btn1" href="javascript:void(0)">����</a>
							<a href="javascript:void(0);" {% if flag != 1 %} class="btn2" {% endif %} class="btn3" onclick = "collectSel({{ sell.id }});" id="col">{% if flag != 1 %} �ղ� {% else %}ȡ���ղ�{% endif %}</a>
						</div>
						<!-- JiaThis Button BEGIN -->
							<div class="jiathis_style_24x24" style="display:none;">
								<div class="border" {% if flag != 1 %} style="margin-left:32px;" {% else %} style="margin-left:8px;" {% endif %}></div>
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
				<ul class="tract-nav clearfix">
					<li class="active">��ϸ��Ϣ</li>
					{% if sell.is_source == 1 %}
					<li>׷����Ϣ</li>
					{%endif%}
					<li>�û�����</li>
					<li>�ɽ���¼</li>
				</ul>
				<div class="tract-message">

					<!-- ��ϸ��Ϣ -->
					<div class="msgBox all-msg" style="display:block;">

						<table cellpadding="0" cellspacing="0" width="854" class="table1">
							<tr height="40">
								<th width="103">
									<span>��Ӧ���</span>
								</th>
								<td width="356">
									<span>{{ sell.sell_sn }}</span>
								</td>
								<th width="103">
									<span>��ƷƷ��</span>
								</th>
								<td width="356">
									<span>{{ sell.title }}</span>
								</td>
							</tr>
							<tr height="40">
								<th width="103">
									<span>��ƷƷ��</span>
								</th>
								<td width="356">
									<span>{{sell.breed ? sell.breed : '����' }}</span>
								</td>
								<th width="103">
									<span>��Ӧʱ��</span>
								</th>
								<td width="356">
									<span>{{ time_type[sell.stime] }}~{{ time_type[sell.etime] }}</span>
								</td>
							</tr>
							{% if sell.is_source == 1 %}
							<tr height="40">
								<th width="103">
									<span>��Ʒ���</span>
								</th>
								<td width="356">
									<span> {% if sell.spec %}{{ sell.spec }}{%else%} ����{% endif %}</span>
								</td>
								<th width="103">
									<span>����</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagsell['full_address']) ? $tagsell['full_address'] : '';?> &nbsp;</span>
								</td>
							</tr>
							<tr height="40">
								<th width="103">
									<span>������</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagsell['productor']) ? $tagsell['productor']:''; ?> &nbsp;</span>
								</td>
								<th width="103">
									<span>��������</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagsell['product_date']) ? $tagsell['product_date']:''; ?> &nbsp;</span>
								</td>
							</tr>
							<tr height="40">
								<th width="103">
									<span>�ӹ���</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagsell['process_merchant']) ? $tagsell['process_merchant']:''; ?>&nbsp;</span>
								</td>
								<th width="103">
									<span>�ӹ���</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagsell['process_place']) ? $tagsell['process_place']:''; ?>&nbsp; </span>
								</td>
							</tr>
							<tr height="40">
								<th width="103">
									<span>������</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagsell['expiration_date'])? $tagsell['expiration_date'] : ''; ?> </span>
								</td>
								<th width="103">
									<span>�����ȼ�</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagquality['quality_level'])? $tagquality['quality_level'] : ''; ?> </span>
								</td>
							</tr>
							<tr height="40">
								<th width="103">
									<span>ũ�к���</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagquality['pesticide_residue'])? $tagquality['pesticide_residue'] : ''; ?></span>
								</td>
								<th width="103">
									<span>�ؽ�������</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagquality['heavy_metal'])? $tagquality['heavy_metal'] : ''; ?> </span>
								</td>
							</tr>
							<tr height="40">
								<th width="103">
									<span>�Ƿ�ת����</span>
								</th>
								<td width="356">
									<span><?php echo isset($tagquality['is_gene'])&&$tagquality['is_gene']==1 ? '��' : '��';?></span>
								</td>
								<th width="103">
									<span></span>
								</th>
								<td width="356">
									<span></span>
								</td>
							</tr>
						{%endif%}


						</table>
						
						<div class="title">
							<span>��Ʒ����</span>
						</div>

						<div class="product-img">
							{% if sell.scontent  %}
							  {{ sell.scontent.content }}
						   {% else %}
							 {{contents}}
						   {% endif %}
						</div>
						
							<!-- {% if sell.is_source == 1 %}
								{{ partial('sell/labelinfo1') }} 
							{% endif %}
					 -->

						<div class="title">
							<span>�û�����</span>
						</div>

						<div class="table5-box clearfix" id="list">
							<div class="name">
								<span class="pj">�ۼ����ۣ�<?php if ($sell->total_score){ ?> {{ sell.total_score }}<?php }else{?> ����<?php }?></span>
								<font>�����������<?php if ($sell->total_score){ ?> {{ sell.average_score }}��<?php }else{?> ����<?php }?></font>
								{%if sell.total_score >= 0 %}
								<span class="pf">
									{{ sell.tr }}
								</span>
								{%endif%}
							</div>
							<table cellpadding="0" cellspacing="0" width="854" class="table5">
							
							</table>
							<!-- ��ҳ -->
							<div class="supply-hall-page f-fr mt20" id="list_page">

							</div>
						</div>

						<div class="title">
							<span>�ɽ���¼</span>
						</div>

						<div class="table6-box clearfix" id="orders">
							<table cellpadding="0" cellspacing="0" width="854" class="table6">

							</table>
							<!-- ��ҳ -->
							<div class="supply-hall-page f-fr mt20" id="orders_page">

							</div>
						</div>

					</div>

					<!-- ׷�ݲ�Ʒ��Ϣ  -->
					{% if sell.is_source == 1 and  tagsell %}
					<!-- ׷����Ϣ -->
					<div class="msgBox zs-msg">
						
						<div class="title">
							<span>׷����Ϣ</span>
						</div>

						<h6 class="erji-title">����������Ϣ</h6>

						<table cellpadding="0" cellspacing="0" width="854" class="table2">
							<tr height="40">
								<th width="118">
									<span>�������ڣ�</span>
								</th>
								<td width="341">
									<span><?php echo isset($tagsell['product_date']) ? $tagsell['product_date'] : ''?></span>
								</td>

								<th width="118">
									<span>��Ʒ�����ڣ�</span>
								</th>
								<td width="341">
									<span><?php echo isset($tagsell['expiration_date']) ? $tagsell['expiration_date'] : ''?></span>
								</td>
							</tr>
							<tr height="40">
								<th width="118">
									<span>���أ�</span>
								</th>
								<td width="341">
									<span><?php echo isset($tagsell['full_address']) ? $tagsell['full_address'] : ''?></span>
								</td>
								
								<th width="118">
									<span>��ֲ�����</span>
								</th>
								<td width="341">
									<span><?php echo isset($tagsell['plant_area']) ? $tagsell['plant_area'] : '0'?>Ķ</span>
								</td>
							</tr>
						
						</table>


						<div class="use-table">
							<div class="name">������Ϣ</div>
							<table cellpadding="0" cellspacing="0" width="854" class="table3">
								<tr height="30">
									<th width="16%">γ��</th>
									<th width="16%">��ˮ�ȼ�</th>
									
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
							<div class="name">����ʹ�ñ�</div>
							<table cellpadding="0" cellspacing="0" width="854" class="table3">
								<tr height="30">
									<th width="16%">����</th>
									<th width="16%">Ʒ��</th>
									<th width="16%">����</th>
									<th width="16%">����</th>
									<th width="16%">��ѿ��</th>
									<th width="16%">ˮ��</th>
									
								</tr>
								<tr height="40">
					<td><?php echo isset($TagSeed->crops) ? $TagSeed->crops : '';?></td>
					<td><?php echo isset($TagSeed->breed) ? $TagSeed->breed : '';?></td>
					<td><?php echo isset($TagSeed->neatness) ? $TagSeed->neatness : '';?></td>
					<td><?php echo isset($TagSeed->fineness) ? $TagSeed->fineness : '';?></td>
					<td><?php echo isset($TagSeed->sprout) ? $TagSeed->sprout : '';?></td>
					<td><?php echo isset($TagSeed->water) ? $TagSeed->water : '';?></td>
								</tr>
							</table>
						</div>


						<div class="use-table">
							<div class="name">����ʹ�ñ�</div>
							<table cellpadding="0" cellspacing="0" width="854" class="table3">
								<tr height="30">
									<th width="16%">ʱ��</th>
									<th width="16%">����</th>
									<th width="16%">����(ǧ��/Ķ)</th>
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
							<div class="name">ũҩʹ�ñ�</div>
							<table cellpadding="0" cellspacing="0" width="854" class="table3">
								<tr height="30">
									<th width="16%">ʱ��</th>
									<th width="16%">����</th>
									<th width="16%">����(ǧ��/Ķ)</th>
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


							
						<h6 class="erji-title">��������ȫ�̼��</h6>

						<table cellpadding="0" cellspacing="0" width="854" class="table4">
							<tr height="30">
								<th width="16%">��ҵ����</th>
								<th width="16%">��ʼʱ��</th>
								<th width="16%">����ʱ��</th>
						
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
					{%endif%}

					<!-- �û����� -->
					<div class="msgBox pl-msg">
						
						<div class="title">
							<span>�û�����</span>
						</div>

						<div class="table5-box clearfix" id="lists">
							<div class="name">

								<span class="pj">�ۼ����ۣ�<?php if ($sell->total_score){ ?> {{ sell.total_score }}<?php }else{?> ����<?php }?></span>
								<font>�����������<?php if ($sell->total_score){ ?> {{ sell.average_score }}��<?php }else{?> ����<?php }?></font>
								{%if sell.total_score >= 0 %}
								<span class="pf">
									{{ sell.tr }}
								</span>
								{%endif%}
							</div>
							<table cellpadding="0" cellspacing="0" width="854" class="table5">

							</table>
							<!-- ��ҳ -->
							<div class="supply-hall-page f-fr mt20" id="lists_page">

							</div>
						</div>

					</div>

					<!-- �ɽ���¼ -->
					<div class="msgBox cj-msg">
						
						<div class="title">
							<span>�ɽ���¼</span>
						</div>

						<div class="table6-box clearfix" id="orderss">
							<table cellpadding="0" cellspacing="0" width="854" class="table6">

							</table>
							<!-- ��ҳ -->
							<div class="supply-hall-page f-fr mt20" id="orders_pages">

							</div>
						</div>

					</div>

				</div>

			</div>
			<div class="right f-fr">
				

				<!-- �̼���Ϣ -->
				<div class="business-place">
					<div class="title">
						<span>�̼���Ϣ</span>
					</div>
					<dl class="head-photo f-oh">
						<dt class="f-fl">
							{% if sell.picture_path %}
							<img src="{{ constant('IMG_URL') }}{{sell.picture_path}}" height="50" width="50">
							{%else%}
							<?php $cImage =  Mdg\Models\Image::imgmaxsrc($cateid); 
							?>
							<img jqimg="{{cImage}}" src="{{cImage}}" width="50" height="50" />
                            {% endif %}
						</dt>
						<dd class="f-fl">{{ sell.uname }}</dd>
					</dl>
					
					<div class="message">
						<font>���ڵ�����</font>
						<div class="box">{{ sell.address }}</div>
					</div>
					<div class="message">
						<font>��Ӫ��Ʒ��</font>
						<div class="box">{{ sell.goods }} &nbsp;</div>
					</div>
				</div>


				<!-- �̼���Ϣ -->
				{% if shopgoods %}
				<div class="business-place">
					<div class="title">
						<span>�̼���Ϣ</span>
					</div>
					<div class="bp-logo f-tac">
						{% if shopgoods.personal_logo_picture %}
							<img src="{{ constant('IMG_URL') }}{{shopgoods.personal_logo_picture}}" height="80" width="200">
						{%else%}
							<img jqimg="<?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>
                                " src="
                                <?php echo  Mdg\Models\Image::imgmaxsrc($cateid) ?>
                                " width="200" height="80" />
                            {% endif %}
					</div>
					<div class="name">{{ shopgoods.shop_name}}</div>
					<div class="attest">
					<span class="at1">��ҵ��֤</span>
						<span class="at2">����ũ��</span>
					</div>
					<div class="line"></div>
					<div class="message">
						<font>���ŵȼ���</font>
						<div class="box">
							<div class="all-level">
								<!-- 
								**	class = level1 �� �ȼ�1
								**			level2 �� �ȼ�2
								**			level3 �� �ȼ�3
								**			level4 �� �ȼ�4
								**			level5 �� �ȼ�5
								-->
								<span class="level1"></span>
								<!-- <span class="level2"></span>
								<span class="level3"></span>
								<span class="level4"></span>
								<span class="level5"></span> -->
							</div>
						</div>
					</div>
					<!--
					<div class="message">
						<font>��Ӫģʽ��</font>
						<div class="box">����ũ��</div>
					</div>
					-->
					<div class="message">
						<font>���ڵ�����</font>
						<div class="box">{{shopgoods.address}} &nbsp;</div>
					</div>
					<div class="message">
						<font>��Ӫ��Ʒ��</font>
						<div class="box">{{shopgoods.main_product}} &nbsp;</div>
					</div>
					<div class="line"></div>
					<div class="links">
						<a class="link1" href="#">�ղ��̼�</a>
						<a class="link2" href="http://{{ shopgoods.shop_link}}.5fengshou.com">�������</a>
					</div>
				</div>
				{%endif%}
				<!-- ������Ӧ -->
				<div class="also-served">
					<div class="title">
						<span>������Ӧ</span>
					</div>
					<ul class="list">
					 {% for key, val in otherSell %}
						<li>
							<a href="/sell/info/{{ val['id'] }}">
								<div class="name">{{ val['title'] }}</div>
								<div class="f-oh">
									<span class="f-fl">{{ val['min_price'] }}~{{ val['max_price'] }}Ԫ/{{ goods_unit[val['goods_unit']] }}</span>
									<span class="f-fr">{{ date('y-m-d', val['updatetime']) }}</span>
								</div>
							</a>
						</li>
					{% endfor %}

					</ul>
				</div>

				<!-- �ر��Ƽ� -->
				<div class="supply-featured-box">
					<div class="title"><span>�ر��Ƽ�</span></div>
					{% if hot_sell %}
					{% for key, val in hot_sell %}
					<div class="supply-featured-list">
						<a href="/sell/info/{{ val['id'] }}">
							<div class="imgs">
							 {% if val['thumb']  %}
								<img src="{{ constant('IMG_URL') }}{{ val['thumb'] }}" height="130" width="130" alt="{{val['title']}}��Ӧʱ��:{{ time_type[val['stime']] }}~{{ time_type[val['etime']] }}">
								{% else %}
								<img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="142" width="142" alt="{{val['title']}}��Ӧʱ��:{{ time_type[val['stime']] }}~{{ time_type[val['etime']] }}">
								{% endif %}
							</div>
							<div class="imgName">{{ val['title'] }}</div>
							<div class="imgPrice">{{ val['min_price'] }}~{{ val['max_price'] }}Ԫ/{{ goods_unit[val['goods_unit']] }}</div>
							<div class="imgAddress">{{ val['areas_name'] ? val['areas_name'] : '' }}</div>
						</a>
					</div>
					<div class="line"></div>
					{% endfor %}
					{% endif %}
				</div>
			</div>
		</div>

	</div>

	<!-- �ײ� -->
{{ partial('layouts/footer') }}
	<!-- �鿴���ͼƬ���� start -->
	<div class="photo_layer"></div>
	<div class="photo_alert">
		<a href="javascript:;" class="close_btn"></a>
		<a class="prev-btn" href="javascript:;"></a>
		<div class="photo_box">
	        <ul id="photo_box_id">
				
	        </ul>
	    </div>
	    <a class="next-btn" href="javascript:;"></a>
	</div>
	<!-- �鿴���ͼƬ���� end -->

	<!-- ͼƬ����Ŵ�Ч�� ������ŵ�ҳ�������棬body������ǩ֮ǰ�� -->
	<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.4/css/zoom.css" media="all">
	<script src="{{ constant('STATIC_URL') }}mdg/version2.4/js/zoom.min.js"></script>
</body>
</html>
<script type="text/javascript">
<!--
	$(function(){
	getData(1,0,1);
	getDatas(1,1,1);
	getData(1,0,2);
	getDatas(1,1,2);

    (function(){
        var index=0;
        var length=$(".schj_list_xq li").length;
        var i=1;
		$(".schj_list_xq li").eq(0).addClass('active');
        function showImg(i){
            $(".schj_list_xq li").eq(i).addClass("active").siblings().removeClass("active");
        }
        function slideNext(){
            if(index >= 0 && index < length-1) {
                ++index;
                showImg(index);
            }else{
                if(confirm("�Ѿ������һ��,���ȷ�����������")){
                    showImg(0);
                    index=0;
                    if(length > 5){
						aniPx=(length-5)*120+'px'; 
					}
                    $(".schj_list_xq ul").animate({ "left": "+="+aniPx },200);
                    i=1;
                }
                return false;
            }
            if(i<0 || i>length-5){
                return false;
            }                         
            $(".schj_list_xq ul").animate({ "left": "-=120px" },200)
            i++;
        }
        function slideFront(){
            if(index >= 1 ) {
                --index;
                showImg(index);
            }
            if(i<2 || i>length+5){
                return false;
            }
            $(".schj_list_xq ul").animate({ "left": "+=120px" },200)
            i--;
        }
        $(".next_btn").click(function(){
            slideNext();
        });
        $(".prev_btn").click(function(){
            slideFront();
        });
        $(".schj_list_xq li").click(function(){
            index  =  $(".schj_list_xq li").index(this);
            showImg(index);
        });
    })();
    
    //��ᵯ��
    (function(){
        $('.chakan_photos').on('click', function(){
            $('#photo_box_id').html('');
            var sid = $(this).attr('id');
            //ajax �����滻����ͼƬ
            $.ajax({
                url: '/sell/tagplant',
                async:false,
                data: {sid: sid},
                success:function ( data) {
                    $('#photo_box_id').html(data);
                    var now = 0;
                    var $li = $('.photo_alert .photo_box li');
                    function nextImg(){
                        $li.removeClass('active');
                        $li.eq(now).addClass('active');
                        now ++;
                        if(now == $li.length){
                            now = 0;
                        }
                    };
                    function prevImg(){           
                        $li.removeClass('active');
                        $li.eq(now).addClass('active');
                        now --;
                        if(now == -1){
                            now = $li.length - 1;
                        }
                    };
                    $('.prev-btn').on('click', function(){
                        prevImg();
                    });
                    $('.next-btn').on('click', function(){
                        nextImg();
                    });
                }
            });
            $('.photo_layer').show();
            $('.photo_alert').show();
        });
        $('.photo_alert .close_btn').click(function(){
            $(this).parent().hide();
            $('.photo_layer').hide();
        });
    })();
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
					var table = '<tr height="35"><th width="10%"></th><th width="26%" align="left">�ɹ��̼�</th><th width="28%">�ɹ�����</th><th width="26%">�ɹ�����</th><th width="10%"></th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span>ȥ<input type='text' name='pt1' id='orders1'  />ҳ</span>&nbsp;<input class='btn' type='button' value='ȷ��' onclick='subDatas(1)' />";
							$("#orders table").append(str); 
							$("#orders_page").append(li); 
					}
				}

				if(json.type=='comments'){
					$("#list table").empty();
					$("#list_page").empty();
					var table = '<tr height="35"><th width="4%"></th><th width="37%">��������</th><th width="22%">����</th><th width="22%">����ʱ��</th><th width="15%">��Ա��</th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){ 
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span>ȥ<input type='text' name='p1' id='p1' />ҳ</span>&nbsp;<input class='btn' type='button' value='ȷ��' onclick='subData(1)' />";
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
					var table = '<tr height="35"><th width="10%"></th><th width="26%" align="left">�ɹ��̼�</th><th width="28%">�ɹ�����</th><th width="26%">�ɹ�����</th><th width="10%"></th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span>ȥ<input type='text' name='pt2' id='orders2'  />ҳ</span>&nbsp;<input class='btn' type='button' value='ȷ��' onclick='subDatas(1)' />";
							$("#orderss table").append(str); 
							$("#orders_pages").append(li); 
					}
				}

				if(json.type=='comments'){
					$("#lists table").empty();
					$("#lists_page").empty();
					var table = '<tr height="35"><th width="4%"></th><th width="37%">��������</th><th width="22%">����</th><th width="22%">����ʱ��</th><th width="15%">��Ա��</th></tr>';
					var list = json.item;
					var tr_td = '';
					$.each(list,function(index,array){ 
						tr_td += array['tr_td']; 
					 });
					str = table+tr_td;
					var li = json.page;
					if (li !='<b></b>' ){
							li += "<span>ȥ<input type='text' name='p2' id='p2' onkeyup='value=value.replace(/[^\d]/g,'')'  />ҳ</span>&nbsp;<input class='btn' type='button' value='ȷ��' onclick='subData(2)' />";
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
	// �ղ�
	function collectSel(sellId){
		$.ajax({
			type:"POST",
			url:"/sell/collectSell",
			data:{sellId:sellId},
			dataType:"json",
			success:function(msg){		
				if(msg['code'] == 0){
					//location.href="/member/dlogin/index?ref=/sell/info/"+sellId+"&islogin=1";
					newWindows('login', '��¼', "/member/dlogin/index?ref=/sell/info/"+sellId+"&islogin=1");
				} else if(msg['code'] == 4){
					$("#col").attr('class','btn3').html('ȡ���ղ�');
					window.location.reload();
					return;
				} else if( msg['code'] == 6 ){
					$("#col").attr('class','btn2').html('�ղ�');
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
	$(".btn1").click(function(){
		var sty = $(".jiathis_style_24x24").css('display');
		if(sty == 'none'){
			$(".jiathis_style_24x24").css("display","block");
		} else {
			$(".jiathis_style_24x24").css("display","none");
		} 
	});
//-->
</script>