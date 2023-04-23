<!--头部-->
{{ partial('layouts/member_header') }}
<div class="wrapper">
	<div class="w1190 mtauto f-oh">
	    <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}报价列表</span>
        </div>
		<!-- 左侧 -->
		{{ partial('layouts/navs_left') }}
		<!-- 右侧 -->
		<div class="center-right f-fr">

			<div class="bj-cate f-oh pb30">
				<div class="title f-oh">
					<span>报价列表</span>
				</div>
				<div class="tips">共有 <i>{{ count }}</i> 家给您报价</div>
				
				<div class="box">
				{% for key, val in data.items %}
					<div class="m-title f-oh">
						<span class="f-fl">
							<i>{{ val.sellname }}</i>  给您的报价
						</span>
						<em class="f-fr">报价时间：{{ date('Y-m-d H:i:s', val.addtime) }} </em>
					</div>
					<div class="m-box">
						<div class="message">价格：{{ val.price }}元/{{ goods_unit[purchase.goods_unit] }}</div>
						<div class="message">规格描述：{{ val.spec }}</div>
						<div class="message">供应地：{{ val.saddress }}</div>
						<div class="message">手机号：{{ val.sphone }}</div>
						<div class="message">姓名：{{ val.sellname }}</div>
						{% if val.state %}
						<input class="yet-btn" type="button" value="已采购" disabled />
						{% else %}
						<input class="confirm-btn" type="button" value="确认采购" onclick="newWindows('confirmquo', '确认采购信息', '/member/dialog/confirmquo/{{ val.id }}');" />
						{% endif %}
					</div>
				{% endfor %}	
				</div>
				<!-- 分页 -->
				{% if pages and data.items %}
				<form action="/member/purchase/quolist" method="get">
				<div class="esc-page mt30 mb30 f-tac f-fr mr30">
						{{ pages }}
					<span>
						<label>去</label>
						<input type="text" name="p" value="1"  onkeyup="value=value.replace(/[^\d]/g,'') " onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/>
						<label>页</label>
					</span>
					<input  type="hidden" value="{{ total_count }}" name="total"/>
					<input  type="hidden" value="{{ purid }}" name="purid"/>
					<input class="btn" type="submit" value="确定" />
				</div>
				</form>
				{% endif %}
			</div>

		</div>		

	</div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}
