{{ partial('layouts/page_header') }}

<!-- 头部 -->

<!-- 采购中心二级导航 -->
<div class="erji-nav">
	<div class="w1190 mtauto clearfix">
		<div class="list">
			<a {% if maxcate=='1'%} class="active" {% endif %} href="/sell/mc1_a0_c0_f_p1">蔬菜</a>
			<a {% if maxcate=='2' %} class="active" {% endif %} href="/sell/mc2_a0_c0_f_p1">水果</a>
			<a {% if maxcate=='7' %} class="active" {% endif %}  href="/sell/mc7_a0_c0_f_p1">粮油</a>
			<a {% if maxcate=='15' %} class="active" {% endif %}  href="/sell/mc15_a0_c0_f_p1">干果</a>
			<a {% if maxcate=='1377' %} class="active" {% endif %} href="/sell/mc1377_a0_c0_f_p1">绿化苗木</a>
			<a {% if maxcate=='22' %} class="active" {% endif %} href="/sell/mc22_a0_c0_f_p1">中药材</a>
			<a {% if maxcate=='8' %} class="active" {% endif %} href="/sell/mc8_a0_c0_f_p1">食用菌</a>
			<a {% if maxcate=='899' %} class="active" {% endif %}  href="/sell/mc899_a0_c0_f_p1">其他</a>
		</div>
	</div>
</div>
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
		{{partial('layouts/navs_cond')}}
		<div class="filter-bList f-oh">
			<!-- 左侧 -->
			<div class="bList-left f-fl">
				<div class="box">
				
					<!-- 筛选条件 -->
					<div class="erji-filter f-oh">
						<!--  
						**	类名checked表示多选框是选中状态
						-->
						<div class="checkBox clearfix f-fl">
							<label onclick="is_img()" id="id_img" class="clearfix f-fl {% if imgis_true %}checked{% endif %}">
								<span ></span>
								<font>可追溯</font>
							</label>
							<!-- 
							**	产地直销添加 
							-->
							<label onclick="is_broker()" id="is_broker" class="clearfix f-fl {% if is_broker %}checked{% endif %}">
								<span></span>
								<font>产地直销</font>
							</label>
						</div>
						<div class="m-page clearfix f-fr">
							<font>{{ newpages }}</font>
						</div>
					</div>
			
					<!-- 筛选商品列表 -->
					{% for key, sell in data %}
					<div class="hall-product-list">
						<div class="m-box clearfix">
							<dl class="clearfix f-fl">
								<dt class="f-fl">
									<a href="/sell/info/{{ sell['id'] }}"><img width="142" height="142"  
										src="{{sell['thumb']}}" alt="{{sell['title']}}"></a>
								</dt>
								<dd class="name f-fr">
									<a href="/sell/info/{{ sell['id'] }}">{{ sell['title'] }}</a>
									{% if sell['is_source']=='1' %}
									<span class="icon2">可追溯</span>
									{% endif %}
									{% if sell['is_broker']== 1 %}
									<span class="dm-icon">产地直销</span>
									{% endif %}			
								</dd>
								<dd class="message f-fr">
									<font>供应量：</font>
									{% if sell['quantity'] > 0 %}
									<?php echo Lib\Func::conversion($sell['quantity']); ?>
									<?php if($sell['goods_unit']){ echo $goods_unit[$sell['goods_unit']]; }else{echo "不限";} ?> 
									{% else %}
									 不限
									{% endif %}
								</dd>
								<dd class="message f-fr">
									<font>供应地：</font><?php echo Lib\Utils::getC($sell["areas_name"]); ?>
								</dd>
								<dd class="message f-fr f-oh">
									<em class="f-fl mr10">
										<font>供应商：</font>{{ sell['uname'] }}
									</em>
									{% if !session.user %}
									   电话号码<a href="javascript:newWindows('newbuy', '确认采购信息', '/member/dlogin/index?ref=/sell/info&islogin=1');" style="color:blue" >登录</a>后可见
									{% else %}
									    <font style="color:red; float:left;">{{sell['mobile']}}</font>
									{% endif %}		
									{% if sell['is_shopgoods']=='1' %}
									<span class="icon1" style="float:left; margin-left:5px;">可信农场</span>
									{% endif %}
								</dd>
								<dd class="message f-fr">
									<font>发布时间：</font>{{date("Y-m-d H:i:s",sell['createtime'])}}
								</dd>
							</dl>
							<div class="operate f-fr">
								<font class="f-db">单价(元/<?php if($sell['goods_unit']){ echo $goods_unit[$sell['goods_unit']]; }else{ echo "不限";} ?>)：</font>
								<strong class="f-db">
								 {% if sell['price_type'] == '1' %}
                                    <?php $arr=\Mdg\Models\SellStepPrice::getprice($sell['id'],1);?>
                                     {% if arr %}{{ arr[0]["price"] }} 起{% endif %}
                                    {% else %}
                                    {{ sell['min_price'] }}~{{ sell['max_price'] }}
                                 {% endif %}
                                     
                                    </strong>
								<div class="btns clearfix">
									<a class="cg-btn f-fl"   href="javascript:newWindows('newbuy', '确认采购信息', '/member/dialog/newbuy/{{ sell['id'] }}');">采  购</a>
									<a {% if sell['is_collectsel'] == 1%} class="collect-btn f-fl active" {%else%} class="collect-btn f-fl" {% endif %}  href="javascript:void(0);" onclick = "collectSel({{ sell['id'] }});" id="col_{{ sell['id'] }}"></a>
								</div>
							</div>
						</div>
					</div>
					{% endfor %}
					<!-- 分页 -->
						<div class="esc-page mt30 mb30 f-tac f-fr">
							{% if total_count>1 %}
							{{ pages }}
							<span>
								<label>去</label>
								<input type="text" name="p"  id="p" onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="1"/>
								<label>页</label>
							</span>

							<input class="btn" type="button" value="确定" onclick="goto()"/>
							{% endif %}
						</div>
				</div>
			</div>
								
			<!-- 右侧 -->
			<div class="bList-right f-fr">
				<!-- 热门供应产品 -->

				<div class="hot-product mb20">
					<div class="title">热门供应产品</div>
					{% for items in special %}
					<div class="list">
						<div class="m-box">
							<dl class="clearfix">							
								<dt class="f-fl">
									<a href="/sell/info/{{items['id']}}">
										{% if items['thumb'] %}
										<img src="{{items['thumb']}}" alt="{{items['title']}}"  height="100" width="100">
										{% else %}
										<img src="http://static.ync365.com/mdg/images/detial_b_img.jpg" height="100" width="100">
										{% endif %}
									</a>
								</dt>
								<dd class="name f-fr">
									<a href="/sell/info/{{items['id']}}">{{items['title']}}</a>
								</dd>
								<dd class="price f-fr">{{items['min_price']}}-{{items['max_price']}} 元/<?php if($items['goods_unit']){ echo $goods_unit[$items['goods_unit']]; }else{ echo "不限";} ?> </dd>
								<dd class="area f-fr"><?php echo Lib\Utils::getC($items["areas_name"] ? $items['areas_name'] : ''); ?></dd>
							</dl>
						</div>
					</div>
					{% endfor %}	
				</div>

				<!-- 成交动态 -->
			<!-- 	<div class="dynamic">
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
			</div>
		</div>
	</div>
</div>




<!-- 底部 -->{{ partial('layouts/footer') }}
<script type="text/javascript">
function goto(){
	var query = window.location.search;
	var p = $('#p').val();
	var count = {{total_count}};
	if(p=='' || isNaN(parseInt(p)) || parseInt(p) > parseInt(count)){
		p = count;
	}
	location.href="/sell/mc{{url['mc']}}_a{{url['a']}}_c{{url['c']}}_f{{url['f']}}_p"+p+query;
}
function pagego(p){

	var query = window.location.search;
	location.href="/sell/mc{{url['mc']}}_a{{url['a']}}_c{{url['c']}}_f{{url['f']}}_p"+p+query;
}
<!--
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
				newWindows('login', '登录', "/member/dlogin/index?ref=/sell/info&islogin=1");
            } else if(msg['code'] == 4){
            	$("#col_"+sellId).addClass('active')
				//window.location.reload();
				return;
			} else if( msg['code'] == 6 ){
				$("#col_"+sellId).removeClass('active')
				//window.location.reload();
				return;
			} else {
				alert(msg['result']);
				return;
			}
		}
	});
}

var query = window.location.search;
if(-1 == query.indexOf('?')) {
	query += '?';
}
if(-1 == query.indexOf('img=')) {
	query += '&img=0';
}
if(-1 == query.indexOf('ib=')) {
	query += '&ib=0';
}
// console.log(query);
function is_img(){
	var url = "/sell/mc{{url['mc']}}_a{{url['a']}}_c{{url['c']}}_f{{url['f']}}_p";
    if($('#id_img').is('.checked')) {
    	url += query.replace(/img=(\d+)/, "img=0");
    } else {
    	url += query.replace(/img=(\d+)/, "img=1");
    }
    console.log(url);
	window.location.href=url.replace(/&ib=(\d+)/, "");
	return;
    
}
function is_broker(){
	var url = "/sell/mc{{url['mc']}}_a{{url['a']}}_c{{url['c']}}_f{{url['f']}}_p";
    if($('#is_broker').is('.checked')) {
    	url += query.replace(/ib=(\d+)/, "ib=0");
    } else {
    	url += query.replace(/ib=(\d+)/, "ib=1");
    }
    console.log(url);
	window.location.href=url.replace(/&img=(\d+)/, "");
	return;  
}
</script>