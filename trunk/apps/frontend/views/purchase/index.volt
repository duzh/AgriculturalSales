{{ partial('layouts/page_header') }}

<!-- 头部 -->

<!-- 采购中心二级导航 -->
<div class="erji-nav">
	<div class="w1190 mtauto clearfix">
		<div class="list">
			<a {% if maxcate=='1'%} class="active" {% endif %} href="/purchase/mc1_a0_c0_f_p1">蔬菜</a>
			<a {% if maxcate=='2' %} class="active" {% endif %} href="/purchase/mc2_a0_c0_f_p1">水果</a>
			<a {% if maxcate=='7' %} class="active" {% endif %}  href="/purchase/mc7_a0_c0_f_p1">粮油</a>
			<a {% if maxcate=='15' %} class="active" {% endif %}  href="/purchase/mc15_a0_c0_f_p1">干果</a>
			<a {% if maxcate=='1377' %} class="active" {% endif %} href="/purchase/mc1377_a0_c0_f_p1">绿化苗木</a>
			<a {% if maxcate=='22' %} class="active" {% endif %} href="/purchase/mc22_a0_c0_f_p1">中药材</a>
			<a {% if maxcate=='8' %} class="active" {% endif %} href="/purchase/mc8_a0_c0_f_p1">食用菌</a>
			<a {% if maxcate=='899' %} class="active" {% endif %}  href="/purchase/mc899_a0_c0_f_p1">其他</a>
		</div>
	</div>
</div>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		{{partial('layouts/navs_cond')}}
		<div class="filter-bList f-oh">
			<!-- 左侧 -->
			<div class="bList-left f-fl">

					
					<!-- 筛选条件 -->
					<div class="erji-filter f-oh">
						<!--  
						**	类名checked表示多选框是选中状态
						-->
						<div class="m-page clearfix f-fr">
							<font>{{ newpages }}</font>
						</div>
					</div>

				<div class="box">
					<!-- 筛选商品列表 -->
					{% for key, pur in data %}
					<div class="purchas-product-list">
						<div class="m-box clearfix">
							<dl class="clearfix f-fl">
								<dd class="name">
									<p>{{ pur['title'] }}</p>
								</dd>
								<dd class="message">
									<font>采购数量：</font>
									{% if pur['quantity']  > 0 %}
									<?php echo Lib\Func::conversion($pur['quantity']);?>
									{% endif %}
									{% if pur['quantity']  == 0 %}
									不限
									{% endif %}
									<?php  if($pur['goods_unit']){ echo $unit = Mdg\Models\Purchase::$_goods_unit[$pur['goods_unit']];}?>
								</dd>
								<dd class="message">
									<font>采购地区：</font>{{ pur['areas_name'] }}
								</dd>
								<dd class="message">
									<font>规格：</font>{{ pur['content']}}
								</dd>
								<dd class="message f-oh">
									<em class="f-fl mr10">
										<font>采购商：</font>{{ pur['username'] }}
									</em>
									{% if !session.user %}
									   电话号码<a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dlogin/index?ref=/purchase/index&islogin=1');" style="color:blue" >登录</a>后可见
									{% else %}
									    <font style="color:red;float:left;margin-right:12px">{{pur['mobile']}}</font>
									{% endif %}		
									{% if pur['cai'] %}
									<span class="icon3">{{pur['cai']}}</span>
                                    {% endif %}
								</dd>
								<dd class="message">
									<font>发布时间：</font>{{pur['pubtime']}}
								</dd>
							</dl>
							<div class="operate f-fr">
								<font class="f-db">
									<strong><?php echo $countQuo = Mdg\Models\PurchaseQuotation::countQuo($pur['id']);?>家</strong>报价
								</font>
								<div class="btns clearfix">
									<a class="cg-btn f-fl" href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{pur['id']}}');">报  价</a>
									<a class={% if pur['is_collectsel'] == 1%} "collect-btn f-fl active" {%else%} "collect-btn f-fl" {% endif %}   href="javascript:void(0);" onclick = "collectSel({{ pur['id'] }});" id="col_{{ pur['id'] }}"></a>
								</div>
							</div>
						</div>
					</div>
					{% endfor %}
					<!-- 分页 -->
				         
						<div class="esc-page mt30 mb30 f-tac f-fr">
							{% if total_count > 1 %}
							{{ pages }}
							<span>	
								<label>去</label>
								<input type="text" name="p"  id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1" />
								<label>页</label>
							</span>
							<input type="hidden" value="{{total}}" id="total">
							<input class="btn" type="button" value="确定" onclick="goto()"/>
							{% endif %}
						</div>
						
				</div>
			</div>
			<!-- 右侧 -->
			<div class="bList-right f-fr">
				<!-- 热门供应产品 -->
<!--
				<div class="hot-product mb20">
					<div class="title">热门供应产品</div>
					{% for items in special %}
					<div class="list">
						<div class="m-box">
							<dl class="clearfix">							
								<dt class="f-fl">
									<a href="/sell/info/{{items['id']}}">
										{% if items['thumb'] %}
										<img src="{{items['thumb']}}" alt="{{items['title']}}">
										{% else %}
										<img src="http://static.ync365.com/mdg/images/detial_b_img.jpg">
										{% endif %}
									</a>
								</dt>
								<dd class="name f-fr">
									<a href="/sell/info/{{items['id']}}">{{items['title']}}</a>
								</dd>
								<dd class="price f-fr">{{items['min_price']}}-{{items['max_price']}} 元/公斤</dd>
								<dd class="area f-fr"><?php echo Lib\Utils::getC($items["areas_name"] ? $items['areas_name'] : ''); ?></dd>
							</dl>
						</div>
					</div>
					{% endfor %}	
				</div>
-->
				<!-- 成交动态 -->
<!-- 				<div class="dynamic">
					<div class="title">成交动态</div>
					<div class="box">
						<ul class="list">
							{% for key, order in orders %}
							<li>
								<div class="name">{{ order['pubtime'] }}</div>
								<div class="content">
									{{ order['areas_name'] }}{{ order['purname'] }}成功以 {{ order['price'] }}
									元/{{ order['goods_unit'] ? goods_unit[order['goods_unit']] : ''}}
									<br />
									采购了
									{{ order['quantity'] }}{{ order['goods_unit'] ? goods_unit[order['goods_unit']]  : '' }}
									{{ order['goods_name']}}
								</div>
							</li>
							{% endfor %}
						</ul>
					</div>
				</div> -->
				<!-- add 2015.11.27 企业采购信息 -->
				<div class="company-sellMsg">
					<div class="title">企业采购信息</div>
					{% if purchaseorderby %}
						{% for key,val in purchaseorderby%}
						<div class="box f-oh">
							<div class="cs-title">{{val['title']}}</div>
							<div class="msg">
								采购数量：{{val['quantity']}}{{ val['goods_unit'] ? goods_unit[val['goods_unit']] : ''}}<br />
								采购地区：<?php echo Lib\Utils::c_strcut($val["areas_name"],16); ?><br />
								规格：<?php echo Lib\Utils::c_strcut($val["content"],32); ?><br />
								采购企业：{{val['company_name']}}
							</div>
							<a class="quo-btn" href="javascript:newWindows('newquo', '确定报价', '/member/dialog/newquo/{{val['id']}}');">报价</a>
						</div>
						<div class="cs-line"></div>
	                    {% endfor %}
					{% endif %}
				
					
				</div>
				<!-- add 2015.11.27 企业采购信息 -->
			</div>
		</div>
	</div>
</div>




<!-- 底部 -->{{ partial('layouts/footer') }}
<script type="text/javascript">

function collectSel(purId){
    $.ajax({
        type:"POST",
        url:"/purchase/collectPurchase",
        data:{purId:purId},
        dataType:"json",
        success:function(msg){
            if(msg['code'] == 0){
                newWindows('login', '登录', "/member/dlogin/index?ref=/purchase/index&islogin=1");
            } else if(msg['code'] == 4){
                $("#col_"+purId).addClass('active');
                return;
            } else if( msg['code'] == 6 ){
                $("#col_"+purId).removeClass('active');
                return;
            } else {
                alert(msg['result']);
                return;
            }
        }
    });
}

function goto(){
	var query = window.location.search;
	var p = $('#p').val();
	var count = {{total_count}};
	if(p=='' || isNaN(parseInt(p)) || parseInt(p) > parseInt(count)){
		p = count;
	}
	location.href="/purchase/mc{{url['mc']}}_a{{url['a']}}_c{{url['c']}}_f{{url['f']}}_p"+p+query;
}
function pagego(p){
	var query = window.location.search;
	location.href="/purchase/mc{{url['mc']}}_a{{url['a']}}_c{{url['c']}}_f{{url['f']}}_p"+p+query;
}
</script>